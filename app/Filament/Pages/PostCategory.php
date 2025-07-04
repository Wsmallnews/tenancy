<?php

namespace App\Filament\Pages;

use App\Enums\PostCategories\Status;
use App\Models\PostCategory as PostCategoryModel;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Infolists;
use Filament\Support\Enums\Alignment;
use Kalnoy\Nestedset\QueryBuilder;
use Studio15\FilamentTree\Components\TreePage;

class PostCategory extends TreePage
{
    protected static ?string $title = '资讯分类';

    protected static ?string $navigationLabel = '资讯分类';

    protected static ?string $navigationParentItem = '资讯管理';

    protected static ?string $navigationGroup = '内容管理';

    protected static ?string $slug = 'post-categories';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '资讯分类';

    protected static ?string $pluralModelLabel = '资讯分类';

    protected static ?int $navigationSort = 1;

    public static function getModel(): string|QueryBuilder
    {
        if (Filament::getTenant()) {
            return PostCategoryModel::scoped(['team_id' => Filament::getTenant()->id]);
        } else {
            return PostCategoryModel::class;
        }
    }

    public static function getCreateForm(): array
    {
        return static::getSchemas();
    }

    public static function getEditForm(): array
    {
        return static::getSchemas();
    }

    public static function getInfolistColumns(): array
    {
        return [
            Infolists\Components\TextEntry::make('remark')
                ->label('备注')
                ->visible(fn($state): bool => $state ? true : false),
            Infolists\Components\IconEntry::make('status')
                ->label('状态'),
        ];
    }



    private static function getSchemas()
    {
        return [
            Forms\Components\TextInput::make('name')->label('分类名称')
                ->placeholder('请输入分类名称')
                ->required(),
            Forms\Components\Textarea::make('remark')->label('备注'),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Radio::make('status')
                        ->label('状态')
                        ->default(Status::Normal)
                        ->inline()
                        ->options(Status::class)
                        ->columnSpan(1),
                ])->columns(2),
        ];
    }
}
