<?php

namespace App\Filament\Resources\Companies;

use BackedEnum;
use App\Filament\Resources\Companies\Pages;
use App\Filament\Resources\Companies\Schemas\CompanyForm;
use App\Filament\Resources\Companies\Tables\CompaniesTable;
use App\Models\Company;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = '单位管理';

    protected static string | UnitEnum | null $navigationGroup = '种质目录';

    protected static ?string $slug = 'companies';
    
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '单位';

    protected static ?string $pluralModelLabel = '单位管理';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return CompanyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompaniesTable::configure($table);
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


    /**
     * 保存前处理数据,然后处理结果保存到数据库，CreateCompany & EditCompany 中调用
     */
    public static function operDistrictInfo($data): array
    {
        $district = $data['district'] ?? [];
        $data['province_name'] = $district['province_name'] ?? null;
        $data['province_id'] = $district['province_id'] ?? null;
        $data['city_name'] = $district['city_name'] ?? null;
        $data['city_id'] = $district['city_id'] ?? null;
        $data['district_name'] = $district['district_name'] ?? null;
        $data['district_id'] = $district['district_id'] ?? null;
        unset($data['district']);

        return $data;
    }
}
