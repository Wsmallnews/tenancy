<?php

namespace App\Filament\Resources\ProjectManages;

use App\Filament\Resources\ProjectManages\Schemas\ProjectManageForm;
use App\Filament\Resources\ProjectManages\Schemas\ProjectManageInfolist;
use App\Filament\Resources\ProjectManages\Tables\ProjectManagesTable;
use App\Models\ProjectManage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ProjectManageResource extends Resource
{
    protected static ?string $model = ProjectManage::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $navigationLabel = '项目管理';

    protected static string | UnitEnum | null $navigationGroup = '研究成果';

    protected static ?string $slug = 'project-manages';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '项目管理';

    protected static ?string $pluralModelLabel = '项目管理';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return ProjectManageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectManageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectManagesTable::configure($table);
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
            'index' => Pages\ListProjectManages::route('/'),
            'create' => Pages\CreateProjectManage::route('/create'),
            'view' => Pages\ViewProjectManage::route('/{record}'),
            'edit' => Pages\EditProjectManage::route('/{record}/edit'),
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
