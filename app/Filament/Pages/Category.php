<?php

namespace App\Filament\Pages;

use App\Enums\Categories\Status;
use App\Models\Category as CategoryModel;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\HtmlString;
use Kalnoy\Nestedset\QueryBuilder;
use Studio15\FilamentTree\Components\TreePage;

class Category extends TreePage
{
    protected static ?string $title = '种质分类';

    protected static ?string $navigationLabel = '种质分类';

    protected static ?string $navigationGroup = '种质目录';

    protected static ?string $slug = 'categories';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '种质分类';

    protected static ?string $pluralModelLabel = '种质分类';

    protected static ?int $navigationSort = 1;

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
            Forms\Components\Repeater::make('options.fields')
                ->label('自定义字段')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('分组名称')
                        ->placeholder('请输入字段分组名称')
                        ->helperText(fn (string $operation): ?HtmlString => $operation == 'edit' ? new HtmlString('<span style="color: #F59E0B;font-weight: bold">编辑分组名称会导致 评价、编目 等该分组自定义字段值失效</span>') : null)
                        ->required()
                        ->live(onBlur: true)
                        ->rules([
                            fn (Get $get, string $state): Closure => static::repeaterGroupNameUniqueRule($get, $state),
                        ])
                        ->columnSpan(1),
                    Forms\Components\Builder::make('fields')
                        ->label('分组字段')
                        ->hint(fn (string $operation): ?HtmlString => $operation == 'edit' ? new HtmlString('<span style="color: #F59E0B;font-weight: bold">编辑字段名称会导致 评价、编目 等该自定义字段值失效</span>') : null)
                        ->blocks([
                            Forms\Components\Builder\Block::make('textInput')
                                ->label(function (?array $state): string {
                                    $name = $state['name'] ?? '';
                                    return '文本字段' . ($name ? ' - ' . $name : '');
                                })
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->hiddenLabel()
                                        ->placeholder('请输入字段名称')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->rules([
                                            fn (Get $get, string $state): Closure => static::builderFieldNameUniqueRule($get, $state),
                                        ])
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('unit')
                                        ->hiddenLabel()
                                        ->placeholder('请输入字段单位')
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('placeholder')
                                        ->hiddenLabel()
                                        ->placeholder('请输入字段输入提示')
                                        ->columnSpan(1),
                                    Forms\Components\Toggle::make('is_required')
                                        ->label('是否必填')
                                        ->columnSpan(1),
                                ])
                                ->columns(4),
                            Forms\Components\Builder\Block::make('number')
                                ->label(function (?array $state): string {
                                    $name = $state['name'] ?? '';
                                    return '数值字段' . ($name ? ' - ' . $name : '');
                                })
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->hiddenLabel()
                                        ->placeholder('请输入字段名称')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->rules([
                                            fn (Get $get, string $state): Closure => static::builderFieldNameUniqueRule($get, $state),
                                        ])
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('unit')
                                        ->hiddenLabel()
                                        ->placeholder('请输入字段单位')
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('placeholder')
                                        ->hiddenLabel()
                                        ->default('请输入数值或者范围')
                                        ->placeholder('请输入字段输入提示')
                                        ->columnSpan(1),
                                    Forms\Components\Toggle::make('is_required')
                                        ->label('是否必填')
                                        ->columnSpan(1),
                                    Forms\Components\Hidden::make('regex')
                                        ->default('/^(\d+)(?:-(\d+))?$/')
                                        ->columnSpan(1),
                                    Forms\Components\Hidden::make('regex_message')
                                        ->default('请输入正确的数值信息: 纯数字或形如 18-30 的范围值')
                                        ->columnSpan(1),
                                ])
                                ->columns(4),
                            Forms\Components\Builder\Block::make('select')
                                ->label(function (?array $state): string {
                                    $name = $state['name'] ?? '';
                                    return '下拉选择字段' . ($name ? ' - ' . $name : '');
                                })
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->hiddenLabel()
                                        ->placeholder('请输入字段名称')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->rules([
                                            fn (Get $get, string $state): Closure => static::builderFieldNameUniqueRule($get, $state),
                                        ])
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('unit')
                                        ->hiddenLabel()
                                        ->placeholder('请输入字段单位')
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('placeholder')
                                        ->hiddenLabel()
                                        ->placeholder('请输入字段输入提示')
                                        ->columnSpan(1),
                                    Forms\Components\Toggle::make('is_required')
                                        ->label('是否必选')
                                        ->columnSpan(1),
                                    // Forms\Components\TagsInput::make('options')
                                    //     ->hiddenLabel()
                                    //     ->placeholder('请输入下拉选项, 按回车确认')
                                    //     ->columnSpan(4),
                                    Forms\Components\Fieldset::make('Options')
                                        ->label('添加下拉选项')
                                        ->schema([
                                            Forms\Components\Repeater::make('options')
                                                ->hiddenLabel()
                                                ->simple(
                                                    Forms\Components\TextInput::make('value')
                                                        ->hiddenLabel()
                                                        ->placeholder('请输入选项名称')
                                                        ->required()
                                                        ->columnSpanFull()
                                                )
                                                ->required()
                                                ->minItems(1)
                                                ->addActionAlignment(Alignment::Start)
                                                ->addActionLabel('添加下拉选项')
                                                ->columnSpanFull()
                                                ->grid(3),
                                        ])
                                ])
                                ->columns(4),
                            Forms\Components\Builder\Block::make('upload_image')
                                ->label(function (?array $state): string {
                                    $name = $state['name'] ?? '';
                                    return '上传图片' . ($name ? ' - ' . $name : '');
                                })
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->hiddenLabel()
                                        ->placeholder('请输入字段名称')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->rules([
                                            fn (Get $get, string $state): Closure => static::builderFieldNameUniqueRule($get, $state),
                                        ])
                                        ->columnSpan(1),
                                    Forms\Components\Toggle::make('is_required')
                                        ->label('是否必填')
                                        ->columnSpan(1),
                                    Forms\Components\Toggle::make('is_multiple')
                                        ->label('是否多图')
                                        ->live()
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('max_files_num')
                                        ->hiddenLabel()
                                        ->placeholder('最大数量, 默认20张')
                                        ->integer()
                                        ->visible(fn(Get $get): bool => $get('is_multiple'))
                                        ->columnSpan(1),
                                    Forms\Components\Hidden::make('collection_name')
                                        ->dehydrateStateUsing(function (Get $get, $state) {
                                            $group_name = $get('../../../name');
                                            $field_name = $get('name');
                                            return pinyin_permalink($group_name . $field_name);     // 分组名 + 字段名 设置为 上传表单 的 collection 名
                                        }),
                                ])
                                ->extraAttributes(['style' => 'place-self: center'])
                                ->columns(4),
                        ])
                        // ->deleteAction(
                        //     fn (Action $action) => $action->requiresConfirmation(),
                        // )
                        ->addActionLabel('添加字段')
                        ->collapsible()
                        ->blockNumbers(false)
                        ->cloneable()
                        ->addActionAlignment(Alignment::Start)
                        ->columnSpanFull()
            ])
            // ->deleteAction(          // 需要研究下 modal 的层级，如何不关闭当前编辑的 modal
            //     fn(Action $action) => $action->requiresConfirmation(),
            // )
            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
            ->addActionLabel('添加分组')
            ->collapsible()
            ->cloneable()
            ->addActionAlignment(Alignment::Start)
            ->columns(2)


            // Forms\Components\Repeater::make('options.fields')
            //     ->label('自定义字段')
            //     ->schema([
            //         Forms\Components\TextInput::make('name')
            //             ->hiddenLabel()
            //             ->placeholder('请输入字段分组名称')
            //             ->required()
            //             ->live(onBlur: true)
            //             ->columnSpan(1),
            //         TableRepeater::make('fields')
            //             ->label('字段')
            //             ->hiddenLabel()
            //             ->headers([
            //                 Header::make('name')
            //                     ->label('字段名称')
            //                     ->markAsRequired()
            //                     ->width('150px'),
            //                 Header::make('unit')
            //                     ->label('字段单位')
            //                     ->width('150px'),
            //                 Header::make('placeholder')
            //                     ->label('字段输入提示')
            //                     ->width('150px'),
            //             ])
            //             ->schema([
            //                 Forms\Components\TextInput::make('name')
            //                     ->label('字段名称')
            //                     ->placeholder('请输入字段名称')
            //                     ->required()
            //                     ->columnSpan(1),
            //                 Forms\Components\TextInput::make('unit')
            //                     ->label('字段单位')
            //                     ->placeholder('请输入字段单位')
            //                     ->columnSpan(1),
            //                 Forms\Components\TextInput::make('placeholder')
            //                     ->label('字段输入提示')
            //                     ->placeholder('请输入字段输入提示')
            //                     ->columnSpan(1),
            //             ])
            //             ->cloneable()
            //             ->addActionAlignment(Alignment::Start)
            //             ->emptyLabel('请设置分组的字段信息')
            //             ->columnSpanFull()
            //     ])
            //     // ->deleteAction(          // 需要研究下 modal 的层级，如何不关闭当前编辑的 modal
            //     //     fn(Action $action) => $action->requiresConfirmation(),
            //     // )
            //     ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
            //     ->collapsible()
            //     ->cloneable()
            //     ->addActionAlignment(Alignment::Start)
            //     ->columns(2)
        ];
    }


    private static function builderFieldNameUniqueRule($get, $state)
    {
        return function (string $attribute, $value, Closure $fail) use ($get, $state) {
            $duplicates = collect($get('../../'))
                ->filter(fn($block) => isset($block['data']['name']) && !empty($block['data']['name']))     // 过滤空值
                ->map(function($block) {            // 取出 name
                    return $block['data']['name'];
                })
                ->duplicates();

            if ($duplicates->isNotEmpty() && $duplicates->contains($state)) {
                $fail('字段名称不能重复');
            }
        };
    }


    private static function repeaterGroupNameUniqueRule($get, $state)
    {
        return function (string $attribute, $value, Closure $fail) use ($get, $state) {
            $duplicates = collect($get('../'))
                ->filter(fn($repeater) => isset($repeater['name']) && !empty($repeater['name']))     // 过滤空值
                ->map(function($repeater) {            // 取出 name
                    return $repeater['name'];
                })
                ->duplicates();

            if ($duplicates->isNotEmpty() && $duplicates->contains($state)) {
                $fail('字段名称不能重复');
            }
        };
    }
}
