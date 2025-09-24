<?php

namespace App\Filament\Platform\Resources\PlatformUsers;

use BackedEnum;
use App\Enums\Activities\LogEvent;
use App\Filament\Platform\Resources\PlatformUsers\Pages;
use App\Filament\Platform\Resources\PlatformUsers\Schemas\PlatformUserForm;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class PlatformUserResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;

    protected static ?string $model = User::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

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

    public static function form(Schema $schema): Schema
    {
        return PlatformUserForm::configure($schema);
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
                    ->color('warning'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->toggleable()
                    ->sortable(),
            ])
            ->searchPlaceholder('搜索管理员姓名、邮箱等...')
            ->filters([
                //
            ])
            ->recordActions([
                // ActivityLogTimelineTableAction::make('Activities')
                //     ->label('操作记录')
                //     ->activitiesUsing(function (?Model $record, ActivityLogTimelineTableAction $component) {
                //         return \App\Models\Activity::query()
                //             ->with(['subject', 'causer'])
                //             ->where(function (Builder $query) use ($record, $component) {
                //                 $query->where(function (Builder $q) use ($record) {
                //                     $q->where('causer_type', $record->getMorphClass())
                //                         ->where('causer_id', $record->getKey());
                //                 })->when($component->getWithRelations(), function (Builder $query, array $relations) use ($record) {
                //                     foreach ($relations as $relation) {
                //                         $model = get_class($record->{$relation}()->getRelated());
                //                         $query->orWhere(function (Builder $q) use ($record, $model, $relation) {
                //                             $q->where('subject_type', (new $model)->getMorphClass())
                //                                 ->whereIn('subject_id', $record->{$relation}()->pluck('id'));
                //                         });
                //                     }
                //                 });
                //             })
                //             ->latest()
                //             ->limit($component->getLimit())
                //             ->get();
                //     })
                //     ->modifyTitleUsing(function ($state) {
                //         return $state['description'];
                //     })
                //     ->timelineIcons(LogEvent::getIcons(true))
                //     ->timelineIconColors(LogEvent::getColors(true))
                //     ->limit(10),
                Actions\EditAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
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
        return __('filament-shield::filament-shield.nav.group');        // 和角色放到一个组
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])->where('user_type', 'platform');
    }
}
