<?php

namespace App\Filament\Pages;

use App\Enums\PostCategories\Status;
use App\Models\PostCategory as PostCategoryModel;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas;
use UnitEnum;
use Wsmallnews\FilamentNestedset\Pages\NestedsetPage;

class PostCategory extends NestedsetPage
{
    use HasPageShield;

    public string $emptyLabel = '图文分类为空';

    protected static ?string $model = PostCategoryModel::class;

    protected static ?string $modelLabel = '图文分类';

    protected static ?string $title = '图文分类';

    protected static ?string $navigationLabel = '图文分类';

    protected static ?string $navigationParentItem = '图文管理';

    protected static string | UnitEnum | null $navigationGroup = '内容管理';

    protected static ?string $slug = 'post-categories';

    protected static string $recordTitleAttribute = 'name';

    protected static ?string $pluralModelLabel = '图文分类';

    protected static ?int $navigationSort = 1;

    public function createSchema($arguments): array
    {
        return $this->schema($arguments);
    }

    public function editSchema($arguments): array
    {
        return $this->schema($arguments);
    }


    public function infolistSchema(): array
    {
        return [
            Infolists\Components\TextEntry::make('remark')
                ->label('备注')
                ->visible(fn($state): bool => $state ? true : false),
            Infolists\Components\IconEntry::make('status')
                ->label('状态'),
        ];
    }



    protected function schema(array $arguments): array
    {
        return [
            Forms\Components\TextInput::make('name')->label('分类名称')
                ->placeholder('请输入分类名称')
                ->required(),
            Forms\Components\Textarea::make('remark')->label('备注'),

            Forms\Components\Radio::make('status')
                ->label('状态')
                ->default(Status::Normal)
                ->inline()
                ->options(Status::class),
        ];
    }
}
