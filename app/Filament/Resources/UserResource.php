<?php

namespace App\Filament\Resources;

use App\Enums\Activities\LogEvent;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class UserResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $tenantOwnershipRelationshipName = 'teams';

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '管理员';

    protected static ?string $slug = 'admins';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '管理员';

    protected static ?string $pluralModelLabel = '管理员';

    protected static ?int $navigationSort = -2;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('基础信息')->schema(self::getBaseFormsComponent()),
                ])->columns(2)->columnSpan(2),
                Forms\Components\Section::make('分配角色')->schema([
                    Forms\Components\Select::make('roles')
                            ->relationship(name: 'roles', titleAttribute: 'name')
                            ->saveRelationshipsUsing(function (Model $record, $state) {
                                $record->roles()->syncWithPivotValues($state, [config('permission.column_names.team_foreign_key') => getPermissionsTeamId()]);
                            })
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    // Forms\Components\Radio::make('status')
                    //     ->label('状态')
                    //     ->default(Status::Normal)
                    //     ->inline()
                    //     ->options(Status::class),
                ])->columns(1)->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('管理员名称'),
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('头像')
                    ->circular()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('邮箱')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('角色组')
                    // ->formatStateUsing(fn ($state): array => is_array($state) ? array_map(fn($name): string => __($name), $state) : [__($state)])
                    ->badge()
                    ->toggleable()
                    ->color('warning')
                    ,
            ])
            ->searchPlaceholder('搜索管理员姓名、邮箱等...')
            ->filters([
                //
            ])
            ->actions([
                ActivityLogTimelineTableAction::make('Activities')
                    ->label('操作记录')
                    ->activitiesUsing(function (?Model $record, ActivityLogTimelineTableAction $component) {
                        return \App\Models\Activity::query()
                            ->with(['subject', 'causer'])
                            ->where(function (Builder $query) use ($record, $component) {
                                $query->where(function (Builder $q) use ($record) {
                                    $q->where('causer_type', $record->getMorphClass())
                                        ->where('causer_id', $record->getKey());
                                })->when($component->getWithRelations(), function (Builder $query, array $relations) use ($record) {
                                    foreach ($relations as $relation) {
                                        $model = get_class($record->{$relation}()->getRelated());
                                        $query->orWhere(function (Builder $q) use ($record, $model, $relation) {
                                            $q->where('subject_type', (new $model)->getMorphClass())
                                                ->whereIn('subject_id', $record->{$relation}()->pluck('id'));
                                        });
                                    }
                                });
                            })
                            ->latest()
                            ->limit($component->getLimit())
                            ->get();
                    })
                    ->modifyTitleUsing(function ($state) {
                        return $state['description'];
                    })
                    ->timelineIcons(LogEvent::getIcons(true))
                    ->timelineIconColors(LogEvent::getColors(true))
                    ->limit(10),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }


    public static function getCluster(): ?string
    {
        return Utils::getResourceCluster() ?? static::$cluster;
    }


    public static function getNavigationGroup(): ?string
    {
        return Utils::isResourceNavigationGroupEnabled()
            ? __('filament-shield::filament-shield.nav.group')
            : '';
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return Utils::getSubNavigationPosition() ?? static::$subNavigationPosition;
    }



    public static function isScopedToTenant(): bool
    {
        return Utils::isScopedToTenant();
    }

    public static function canGloballySearch(): bool
    {
        return Utils::isResourceGloballySearchable() && count(static::getGloballySearchableAttributes()) && static::canViewAny();
    }


    public static function getBaseFormsComponent(): array
    {
        return [
            Forms\Components\TextInput::make('name')->label('管理员名称')
                ->placeholder('请输入管理员名称')
                ->required(),
            Forms\Components\FileUpload::make('avatar_url')->label('头像')
                ->avatar()
                ->required()
                ->directory('users/avatars')
                ->openable()
                ->uploadingMessage('头像上传中...'),
            Forms\Components\TextInput::make('email')->label('邮箱')
                ->placeholder('请输入登录邮箱')
                ->required(),
            Forms\Components\TextInput::make('password')
                ->label(__('filament-panels::pages/auth/edit-profile.form.password.label'))
                ->placeholder('不修改则留空')
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->rule(Password::default())
                ->autocomplete('new-password')
                ->dehydrated(fn($state): bool => filled($state))
                ->dehydrateStateUsing(fn($state): string => Hash::make($state))
                // ->same('passwordConfirmation')       // 是否需要确认密码
                ->live(debounce: 500),
        ];
    }
}
