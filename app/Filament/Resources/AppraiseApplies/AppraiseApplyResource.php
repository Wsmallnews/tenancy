<?php

namespace App\Filament\Resources\AppraiseApplies;

use App\Filament\Resources\AppraiseApplies\Schemas\AppraiseApplyForm;
use App\Filament\Resources\AppraiseApplies\Schemas\AppraiseApplyInfolist;
use App\Filament\Resources\AppraiseApplies\Tables\AppraiseAppliesTable;
use App\Models\AppraiseApply;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class AppraiseApplyResource extends Resource
{
    protected static ?string $model = AppraiseApply::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::DocumentCheck;

    protected static ?string $navigationLabel = '用种申请';

    protected static string|UnitEnum|null $navigationGroup = '种质资源库(圃)';

    protected static ?string $slug = 'appraise-applies';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '用种申请';

    protected static ?string $pluralModelLabel = '用种申请';

    protected static ?int $navigationSort = 9;

    public static function form(Schema $schema): Schema
    {
        return AppraiseApplyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AppraiseApplyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AppraiseAppliesTable::configure($table);
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
            'index' => Pages\ListAppraiseApplies::route('/'),
            'view' => Pages\ViewAppraiseApply::route('/{record}'),
            'edit' => Pages\EditAppraiseApply::route('/{record}/edit'),
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
