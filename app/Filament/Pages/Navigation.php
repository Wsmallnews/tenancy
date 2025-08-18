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
use Studio15\FilamentTree\Components\TreePage;

class Navigation extends TreePage
{
    protected static ?string $title = '导航管理';

    protected static ?string $navigationLabel = '导航管理';

    protected static ?string $navigationGroup = '内容管理';

    protected static ?string $slug = 'navigations';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '导航管理';

    protected static ?string $pluralModelLabel = '导航管理';

    protected static ?int $navigationSort = 1;

    public static function getModel(): string|QueryBuilder
    {
        if (Filament::getTenant()) {
            return NavigationModel::scoped(['team_id' => Filament::getTenant()->id, 'active' => 'web']);
        } else {
            return NavigationModel::class;
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
            Infolists\Components\TextEntry::make('description')
                ->label('描述')
                ->visible(fn($state): bool => $state ? true : false),
            Infolists\Components\IconEntry::make('status')
                ->label('状态'),
        ];
    }



    private static function getSchemas()
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
                    return in_array(static::getNavigationType($get('type')), [NavigationTypeEnum::Page, NavigationTypeEnum::Content]);
                }),

            Forms\Components\SpatieMediaLibraryFileUpload::make('banner')->label('导航Banner')
                ->collection('banner')
                ->image()
                ->openable()
                ->downloadable()
                ->uploadingMessage('Banner 上传中...')
                ->imagePreviewHeight('100')
                ->visible(function (Get $get) {
                    // 只有内容 和 页面 需要设置 Banner
                    return in_array(static::getNavigationType($get('type')), [NavigationTypeEnum::Page, NavigationTypeEnum::Content]);
                }),

            Forms\Components\Select::make('options.target')
                ->label('跳转类型')
                ->options([
                    '_self' => '当前窗口',
                    '_blank' => '新窗口',
                ])
                ->default('_self')
                ->required()
                ->visible(function (Get $get) {
                    // 没有子导航了，就显示跳转类型
                    return static::getNavigationType($get('type')) != NavigationTypeEnum::Child;
                }),

            Forms\Components\Group::make()
                ->relationship('content')
                ->schema([
                    Forms\Components\RichEditor::make('content')
                        ->fileAttachmentsDirectory('contents/' . date('Ymd'))
                        ->label('内容详情')
                        ->required(),
                ])
                ->visible(function (Get $get) {
                    // 没有子导航了，就显示跳转类型
                    return static::getNavigationType($get('type')) == NavigationTypeEnum::Page;
                }),

            Forms\Components\TextInput::make('options.url')
                ->label('跳转链接')
                ->required()
                ->visible(function (Get $get) {
                    // Url 类型显示 跳转链接
                    return static::getNavigationType($get('type')) == NavigationTypeEnum::Url;
                }),

            Forms\Components\TextInput::make('options.route')
                ->label('路由名称')
                ->required()
                ->visible(function (Get $get) {
                    // 跳转路由,填写路由名称
                    return static::getNavigationType($get('type')) == NavigationTypeEnum::Route;
                }),

            Forms\Components\Fieldset::make('url_params')
                ->label('请求参数')
                ->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Toggle::make('has_routes')
                                ->label('路由参数')
                                ->default(false)
                                ->helperText('如果有路由参数，则开启当前选项')
                                ->required()
                                ->live(),
                            Forms\Components\KeyValue::make('routes')
                                ->label('路由参数')
                                ->helperText('路由参数, 没有则不设置')
                                ->reorderable()
                                ->required()
                                ->visible(fn(Get $get): bool => $get('has_routes')),
                        ])
                        ->columns(1)
                        ->columnSpan(1),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Toggle::make('has_queries')
                                ->label('查询参数')
                                ->default(false)
                                ->helperText('如果有查询参数，则开启当前选项')
                                ->required()
                                ->live(),
                            Forms\Components\KeyValue::make('queries')
                                ->label('查询参数')
                                ->helperText('查询参数, 拼接在地址栏后面, 没有则不设置')
                                ->reorderable()
                                ->required()
                                ->visible(fn (Get $get): bool => $get('has_queries')),
                        ])
                        ->columns(1)
                        ->columnSpan(1),
                ])->visible(function (Get $get) {
                    // 内容类型的导航，选了内容类型，并且内容类型有 form 表单
                    return static::getNavigationType($get('type')) == NavigationTypeEnum::Route;
                })
                ->columns(2)
                ->statePath('options._url_params'),

            Forms\Components\Select::make('options.type')
                ->label('内容类型')
                ->options(NavigationType::make()->getOptions())
                ->live()
                ->required()
                ->visible(function (Get $get) {
                    return static::getNavigationType($get('type')) == NavigationTypeEnum::Content;
                })
                ->afterStateUpdated(fn(Forms\Components\Select $component) => $component
                    ->getContainer()
                    ->getComponent('dynamicExtrasFields')
                    ->getChildComponentContainer()
                    ->fill()
                ),

            Forms\Components\Fieldset::make('extras')
                ->label('选项')
                ->schema(function (Get $get) {
                    return NavigationType::make()->getTypeForms($get('options.type'), ['fields' => $get()]);
                })->visible(function (Get $get) {
                    $hasForms = NavigationType::make()->hasForms($get('options.type'), ['fields' => $get()]);

                    // 内容类型的导航，选了内容类型，并且内容类型有 form 表单
                    return (static::getNavigationType($get('type')) == NavigationTypeEnum::Content) && filled($get('options.type')) && $hasForms;
                })
                ->statePath('options._extras')
                ->key('dynamicExtrasFields'),

            Forms\Components\Radio::make('status')
                ->label('导航状态')
                ->options(Status::class)
                ->default(Status::Normal)
                ->required()
        ];
    }


    private static function getNavigationType($type)
    {
        return $type instanceof NavigationTypeEnum ? $type : NavigationTypeEnum::tryFrom($type);
    }
}
