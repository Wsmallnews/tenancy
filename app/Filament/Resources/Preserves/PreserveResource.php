<?php

namespace App\Filament\Resources\Preserves;

use BackedEnum;
use App\Features\Common;
use App\Filament\Resources\Preserves\Pages;
use App\Filament\Resources\Preserves\Schemas\PreserveForm;
use App\Filament\Resources\Preserves\Schemas\PreserveInfolist;
use App\Models\Preserve;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PreserveResource extends Resource
{
    protected static ?string $model = Preserve::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = '保存';

    protected static string | UnitEnum | null $navigationGroup = '种质资源库(圃)';

    protected static ?string $slug = 'preserves';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '保存';

    protected static ?string $pluralModelLabel = '保存';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PreserveForm::configure($schema);
    }


    public static function infolist(Schema $schema): Schema
    {
        return PreserveInfolist::configure($schema);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('preserve_no')
                    ->label('保存编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('preserve_position')
                    ->label('保存位置')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('appraise.cover')
                    ->label('种质封面图')
                    ->collection('cover')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.resource_no')
                    ->label('全国统一编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.name')
                    ->label('种质中文名')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.en_name')
                    ->label('种质外文名')
                    ->searchable()
                    ->toggleable(),
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
            ->searchPlaceholder('搜索保存编号、保存位置等...')
            ->filtersFormWidth(Width::Medium)
            ->filters([
                ...Common::createUpdateRangeFilter(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                    Actions\ForceDeleteBulkAction::make(),
                    Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListPreserves::route('/'),
            'create' => Pages\CreatePreserve::route('/create'),
            'view' => Pages\ViewPreserve::route('/{record}'),
            'edit' => Pages\EditPreserve::route('/{record}/edit'),
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
