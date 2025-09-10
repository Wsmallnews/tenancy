<?php

namespace App\Filament\Resources\AccurateIdentifies;

use App\Filament\Resources\AccurateIdentifies\Pages;
use App\Filament\Resources\AccurateIdentifies\Schemas\AccurateIdentifyForm;
use App\Filament\Resources\AccurateIdentifies\Schemas\AccurateIdentifyInfolist;
use App\Filament\Resources\AccurateIdentifies\Tables\AccurateIdentifiesTable;
use App\Models\AccurateIdentify;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class AccurateIdentifyResource extends Resource
{
    protected static ?string $model = AccurateIdentify::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = '精准鉴定';

    protected static string | UnitEnum | null $navigationGroup = '种质资源库(圃)';

    protected static ?string $slug = 'accurate-identifies';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '精准鉴定';

    protected static ?string $pluralModelLabel = '精准鉴定';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return AccurateIdentifyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AccurateIdentifyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AccurateIdentifiesTable::configure($table);
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
            'index' => Pages\ListAccurateIdentifies::route('/'),
            'create' => Pages\CreateAccurateIdentify::route('/create'),
            'view' => Pages\ViewAccurateIdentify::route('/{record}'),
            'edit' => Pages\EditAccurateIdentify::route('/{record}/edit'),
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
