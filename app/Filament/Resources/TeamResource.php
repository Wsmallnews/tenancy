<?php

namespace App\Filament\Resources;

use App\Enums\Teams\Status;
use App\Filament\Resources\TeamResource\Pages;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Artisan;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '租户';

    protected static ?string $navigationGroup = '租户管理';

    protected static ?string $slug = 'teams';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '租户';

    protected static ?string $pluralModelLabel = '租户';

    protected static ?int $navigationSort = 1;

    protected static bool $isScopedToTenant = false;        // 只有初始用户可访问

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('基础信息')->schema([
                        Forms\Components\TextInput::make('name')->label('租户名称')
                            ->placeholder('请输入租户名称')
                            ->required(),
                        Forms\Components\FileUpload::make('avatar_url')->label('头像')
                            ->avatar()
                            ->required()
                            ->directory('users/avatars')
                            ->openable()
                            ->uploadingMessage('头像上传中...'),
                    ]),
                ])->columns(2)->columnSpan(2),
                Forms\Components\Section::make('状态')->schema([
                    Forms\Components\TextInput::make('slug')->label('标识')
                            ->placeholder('请输入租户标识')
                            ->regex('/^[A-Za-z0-9_]+$/')
                            ->required(),
                    Forms\Components\Radio::make('status')
                        ->label('状态')
                        ->default(Status::Enable)
                        ->inline()
                        ->options(Status::class),
                ])->columns(1)->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('租户名称')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('头像')
                    ->circular()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('标识')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('状态')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->toggleable()
                    ->sortable(),
            ])
            ->deferFilters()        // 延迟过滤,用户点击 apply 按钮后才会应用过滤器
            ->defaultSort('id', 'desc')
            ->searchPlaceholder('搜索租户名称')
            ->filtersFormWidth(MaxWidth::Medium)
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\Group::make()->schema([
                            Forms\Components\DatePicker::make('created_from')->label('创建开始时间')->columnSpan(1),
                            Forms\Components\DatePicker::make('created_until')->label('创建结束时间')->columnSpan(1),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('updated_at')
                    ->form([
                        Forms\Components\Group::make()->schema([
                            Forms\Components\DatePicker::make('updated_from')->label('更新开始时间')->columnSpan(1),
                            Forms\Components\DatePicker::make('updated_until')->label('更新结束时间')->columnSpan(1),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['updated_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('updated_at', '>=', $date),
                            )
                            ->when(
                                $data['updated_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('updated_at', '<=', $date),
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('init')
                    ->label('初始化')
                    ->form([
                        Forms\Components\Select::make('user_id')
                            ->label('选择超管')
                            ->required()
                            ->options(User::query()->pluck('name', 'id'))
                            ->createOptionForm(UserResource::getBaseFormsComponent())
                            ->createOptionUsing(function (array $data): int {
                                $user = User::create($data);
                                return $user->id;
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function (Tables\Actions\Action $action, Team $team, array $data): void {
                        // 检测是否已经绑定了该用户
                        if ($team->users()->where('user_id', $data['user_id'])->exists()) {
                            $action->failure();
                            return;
                        }
                        
                        // 租户与用户绑定
                        $team->users()->attach($data['user_id']);

                        // 获取当前面板的 ID
                        $panelId = Filament::getCurrentPanel()->getId();

                        // 创建 超级管理角色，并且绑定管理员到该角色
                        $exitCode = Artisan::call('shield:super-admin', [
                            '--panel' => $panelId,
                            '--tenant' => $team->id,
                            '--user' => $data['user_id']
                        ]);

                        if ($exitCode !== 0) {
                            $action->failure();
                            return;
                        }
                        $action->success();
                    })
                    ->successNotificationTitle('初始化成功')
                    ->failureNotificationTitle('初始化失败')
                    ->icon('heroicon-m-adjustments-horizontal')
                    ->color('warning')
                    ->visible(fn(Team $team): bool => $team->users()->count() === 0),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
