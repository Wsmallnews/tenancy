<?php

namespace App\Filament\Resources\Shares;

use App\Filament\Resources\Shares\Pages;
use App\Filament\Resources\Shares\Schemas\ShareForm;
use App\Filament\Resources\Shares\Schemas\ShareInfolist;
use App\Filament\Resources\Shares\Tables\SharesTable;
use App\Models\Share;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ShareResource extends Resource
{
    protected static ?string $model = Share::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = '共享';

    protected static string | UnitEnum | null $navigationGroup = '种质资源库(圃)';

    protected static ?string $slug = 'shares';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '共享';

    protected static ?string $pluralModelLabel = '共享';

    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return ShareForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ShareInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SharesTable::configure($table);
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
            'index' => Pages\ListShares::route('/'),
            'create' => Pages\CreateShare::route('/create'),
            'view' => Pages\ViewShare::route('/{record}'),
            'edit' => Pages\EditShare::route('/{record}/edit'),
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
