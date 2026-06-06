<?php

namespace App\Filament\Resources\PhenotypeIdentifies;

use App\Filament\Resources\PhenotypeIdentifies\Schemas\PhenotypeIdentifyForm;
use App\Filament\Resources\PhenotypeIdentifies\Schemas\PhenotypeIdentifyInfolist;
use App\Filament\Resources\PhenotypeIdentifies\Tables\PhenotypeIdentifiesTable;
use App\Models\PhenotypeIdentify;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PhenotypeIdentifyResource extends Resource
{
    protected static ?string $model = PhenotypeIdentify::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentMinus;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::DocumentMinus;

    protected static ?string $navigationLabel = '表型鉴定';

    protected static string|UnitEnum|null $navigationGroup = '种质资源库(圃)';

    protected static ?string $slug = 'phenotype-identifies';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '表型鉴定';

    protected static ?string $pluralModelLabel = '表型鉴定';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return PhenotypeIdentifyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PhenotypeIdentifyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PhenotypeIdentifiesTable::configure($table);
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
            'index' => Pages\ListPhenotypeIdentifies::route('/'),
            'category' => Pages\CategoryPhenotypeIdentifies::route('/category/{categoryId}'),
            'create' => Pages\CreatePhenotypeIdentify::route('/create'),
            'view' => Pages\ViewPhenotypeIdentify::route('/{record}'),
            'edit' => Pages\EditPhenotypeIdentify::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
