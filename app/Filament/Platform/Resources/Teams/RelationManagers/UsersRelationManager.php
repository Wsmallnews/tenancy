<?php

namespace App\Filament\Platform\Resources\Teams\RelationManagers;

use App\Filament\Platform\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $relatedResource = UserResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                filament()->getTenancyScopeName(),              // 租户作用域(禁用)
            ]))
            // ->modifyQueryUsing(function (Builder $query) {
            //     $recordTenant = $this->getOwnerRecord();
            //     setPermissionsTeamId($recordTenant->id);

            //     $query->with(['roles'])->withoutGlobalScopes([
            //         filament()->getTenancyScopeName(),              // 租户作用域(禁用)
            //     ]);

            //     $tenant = Filament::getTenant();
            //     setPermissionsTeamId($tenant->id);
            // })
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
                // Tables\Columns\TextColumn::make('roles.name')
                //     // ->state(function ($record) {
                //     //     $recordTenant = $this->getOwnerRecord();
                //     //     setPermissionsTeamId($recordTenant->id);
                //     //     $roles = $record->roles()->withoutGlobalScopes([
                //     //         filament()->getTenancyScopeName(),              // 租户作用域(禁用)
                //     //     ])->get();

                //     //     $tenant = Filament::getTenant();
                //     //     setPermissionsTeamId($tenant->id);

                //     //     return $roles->pluck('name');
                //     // })
                //     ->label('角色组')
                //     // ->formatStateUsing(fn ($state): array => is_array($state) ? array_map(fn($name): string => __($name), $state) : [__($state)])
                //     ->badge()
                //     ->toggleable()
                //     ->color('warning'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->toggleable()
                    ->sortable(),
            ])
            ->headerActions([
                Actions\CreateAction::make(),
                Actions\AttachAction::make(),
            ])
            ->recordActions([
                // ...
                Actions\ViewAction::make(),
                Actions\DetachAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    // ...
                    Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
