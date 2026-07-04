<?php

namespace App\Filament\Resources\Preserves;

use App\Filament\Resources\Preserves\Schemas\PreserveForm;
use App\Filament\Resources\Preserves\Schemas\PreserveInfolist;
use App\Filament\Resources\Preserves\Tables\PreservesTable;
use App\Models\Preserve;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PreserveResource extends Resource
{
    protected static ?string $model = Preserve::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedArchiveBoxArrowDown;

    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::ArchiveBoxArrowDown;

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
        return PreservesTable::configure($table);
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
