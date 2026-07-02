<?php

namespace App\Filament\Platform\Resources\Teams\Pages;

use App\Filament\Platform\Resources\Roles\Schemas\RoleForm;
use App\Filament\Platform\Resources\Teams\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ManageRoles extends ManageRelatedRecords
{
    protected static string $resource = TeamResource::class;

    protected static ?string $title = '租户角色';

    protected static ?string $modelLabel = '租户角色';

    protected static ?string $pluralModelLabel = '租户角色';

    protected static ?string $relationshipTitle = '租户角色';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string $relationship = 'roles';

    protected static bool $shouldSkipAuthorization = true;      // @sn todo 跳过授权

    public function form(Schema $schema): Schema
    {
        $recordTenant = $this->getOwnerRecord();        // 关系所属租户
        setPermissionsTeamId($recordTenant->id);

        return RoleForm::teamConfigure($schema, $recordTenant->id);
    }

    public function table(Table $table): Table
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
                    ->label('角色名称'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->toggleable()
                    ->sortable(),
            ])
            ->modelLabel(self::$modelLabel)
            ->pluralModelLabel(self::$pluralModelLabel)
            ->headerActions([
                // Actions\CreateAction::make(),
            ]);
    }
}
