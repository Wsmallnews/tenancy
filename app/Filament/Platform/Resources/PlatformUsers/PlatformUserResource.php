<?php

namespace App\Filament\Platform\Resources\PlatformUsers;

use BackedEnum;
use App\Enums\Activities\LogEvent;
use App\Filament\Platform\Resources\PlatformUsers\Pages;
use App\Filament\Platform\Resources\PlatformUsers\Schemas\PlatformUserForm;
use App\Models\User;
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
use Wsmallnews\Support\Filament\Resources\ActivityLogs\Concerns\CauserTimelineAction;

class PlatformUserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = '管理员';

    protected static ?string $slug = 'admins';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '管理员';

    protected static ?string $pluralModelLabel = '管理员';

    protected static ?int $navigationSort = -2;

    public static function form(Schema $schema): Schema
    {
        return PlatformUserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),
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
                CauserTimelineAction::make()
                    ->label('操作日志')
                    ->modifyQueryUsing(fn ($query) => $query->whereNull('team_id'))
                    ->color('info'),
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
