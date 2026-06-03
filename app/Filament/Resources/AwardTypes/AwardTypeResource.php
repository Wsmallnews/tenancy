<?php

namespace App\Filament\Resources\AwardTypes;

use App\Filament\Resources\AwardTypes\Exports\AwardTypeExporter;
use App\Filament\Resources\AwardTypes\Schemas\AwardTypeForm;
use App\Models\AwardType;
use BackedEnum;
use Filament\Actions;
use Filament\Actions\ExportAction as FilamentExportAction;
use Filament\Actions\ExportBulkAction as FilamentExportBulkAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class AwardTypeResource extends Resource
{
    protected static ?string $model = AwardType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = '奖项类型';

    protected static string|UnitEnum|null $navigationGroup = '研究成果';

    protected static ?string $navigationParentItem = '奖项';

    protected static ?string $slug = 'award-types';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '奖项类型';

    protected static ?string $pluralModelLabel = '奖项类型';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return AwardTypeForm::configure($schema);
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
                    ->label('类型名称'),
                Tables\Columns\TextColumn::make('order_column')
                    ->label('排序')
                    ->alignCenter()
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
            ->reorderable('order_column')
            ->defaultSort('order_column', 'asc')
            ->searchPlaceholder('搜索奖项类型')
            ->filters([
                //
            ])
            ->headerActions([
                FilamentExportAction::make()
                    ->exporter(AwardTypeExporter::class)
                    ->icon(Heroicon::ArrowDownTray)
                    ->color('gray'),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    FilamentExportBulkAction::make()
                        ->exporter(AwardTypeExporter::class)
                        ->icon(Heroicon::ArrowDownTray)
                        ->color('gray'),
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAwardTypes::route('/'),
        ];
    }
}
