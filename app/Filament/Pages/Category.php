<?php

namespace App\Filament\Pages;

use App\Models\Category as CategoryModel;
use Kalnoy\Nestedset\QueryBuilder;
use Studio15\FilamentTree\Components\TreePage;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Infolists;

class Category extends TreePage
{
    public static function getModel(): string|QueryBuilder
    {
        if (Filament::getTenant()) {
            return CategoryModel::scoped(['team_id' => Filament::getTenant()->id]);
        } else {
            return CategoryModel::class;
        }
    }

    public static function getCreateForm(): array
    {
        return [
            Forms\Components\TextInput::make('name')->label('分类名称')
                ->placeholder('请输入分类名称')
                ->required(),
            Forms\Components\Textarea::make('remark')->label('备注'),
        ];
    }

    public static function getEditForm(): array
    {
        return [
            Forms\Components\TextInput::make('name')->label('分类名称')
                ->placeholder('请输入分类名称')
                ->required(),
            Forms\Components\Textarea::make('remark')->label('备注'),
        ];
    }

    public static function getInfolistColumns(): array
    {
        return [
            Infolists\Components\TextEntry::make('remark')->label('备注'),
        ];
    }
}
