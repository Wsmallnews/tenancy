<?php

namespace App\Filament\Resources\Personnels;

use BackedEnum;
use App\Filament\Resources\Personnels\Pages;
use App\Filament\Resources\Personnels\Schemas\PersonnelForm;
use App\Filament\Resources\Personnels\Tables\PersonnelsTable;
use App\Models\Personnel;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PersonnelResource extends Resource
{
    protected static ?string $model = Personnel::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = '成员管理';

    protected static string | UnitEnum | null $navigationGroup = '属性选项';

    protected static ?string $slug = 'personnels';
    
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '成员';

    protected static ?string $pluralModelLabel = '成员管理';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PersonnelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersonnelsTable::configure($table);
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
            'index' => Pages\ListPersonnels::route('/'),
            'create' => Pages\CreatePersonnel::route('/create'),
            'edit' => Pages\EditPersonnel::route('/{record}/edit'),
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
