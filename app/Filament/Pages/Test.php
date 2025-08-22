<?php

namespace App\Filament\Pages;

use App\Enums\Navigations\Status;
use App\Enums\Navigations\Type as NavigationTypeEnum;
use App\Features\NavigationType;
use App\Models\Navigation as NavigationModel;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Infolists;
use Kalnoy\Nestedset\QueryBuilder;
use Wsmallnews\FilamentNestedset\Pages\NestedsetPage;

use Filament\Resources\Components\Tab;

class Test extends NestedsetPage
{

    public string $emptyLabel = '测试Test 数据为空';

    protected static ?string $model = NavigationModel::class;
    
    protected static ?string $modelLabel = '测试管理';

    protected static ?string $title = '测试';

    protected static ?string $navigationLabel = '测试';

    protected static ?string $navigationGroup = '内容管理';

    protected static ?string $slug = 'tests';

    protected static string $recordTitleAttribute = 'name';

    protected static ?string $pluralModelLabel = '测试啊管理';

    protected static ?int $navigationSort = 1;

    protected static ?string $tabFieldName = 'active';

    public function getTabs(): array
    {
        return [
            'web' => Tab::make()->label('Website Navigation'),
            'shop' => Tab::make()->label('Shop Navigation')
        ];
    }


    public function createSchema($arguments): array
    {
        return $this->schema($arguments);
    }

    public function editSchema($arguments): array
    {
        return $this->schema($arguments);
    }


    protected function schema(array $arguments): array
    {
        return [
            Forms\Components\Select::make('type')
                // ->helperText('如果存在子导航，父导航设置的 跳转链接/路由等将失效')
                ->label('导航类型')
                ->options(NavigationTypeEnum::class)
                ->default(NavigationTypeEnum::Route)
                ->live()
                ->required(),
            Forms\Components\TextInput::make('name')->label('导航名称')
                ->placeholder('请输入导航名称')
                ->required(),
            Forms\Components\Textarea::make('description')->label('描述'),

            Forms\Components\TextInput::make('slug')
                ->label('导航标识')
                ->unique(ignorable: fn(?NavigationModel $record): ?NavigationModel => $record)
                ->required()
                ->maxLength(255)
                ->visible(function (Get $get) {
                    // 只有内容 和 页面 需要设置标识
                    return in_array($this->getNavigationType($get('type')), [NavigationTypeEnum::Page, NavigationTypeEnum::Content]);
                }),
            Forms\Components\Radio::make('status')
                ->label('导航状态')
                ->options(Status::class)
                ->default(Status::Normal)
                ->required()
        ];
    }


    public function infolistSchema(): array
    {
        return [
            Infolists\Components\TextEntry::make('description')
                ->label('描述')
                ->visible(fn($state): bool => $state ? true : false),
            Infolists\Components\IconEntry::make('status')
                ->label('状态'),
        ];
    }


    private function getNavigationType($type)
    {
        return $type instanceof NavigationTypeEnum ? $type : NavigationTypeEnum::tryFrom($type);
    }
}
