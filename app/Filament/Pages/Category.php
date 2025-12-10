<?php

namespace App\Filament\Pages;

use Closure;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\HtmlString;
use UnitEnum;
use Wsmallnews\Category\Enums\CategoryStatus;
use Wsmallnews\Category\Filament\Pages\Category\Base as BaseCategoryPage;
use Wsmallnews\Support\Concerns\Resource\HasCustomProperties;

class Category extends BaseCategoryPage
{
    protected static ?string $modelLabel = '种质分类';

    protected static ?string $title = '种质分类';

    protected static ?string $navigationLabel = '种质分类';

    protected static string | UnitEnum | null $navigationGroup = '属性选项';

    protected static ?string $slug = 'appraise-categories';

    protected static ?string $pluralModelLabel = '种质分类';

    protected static ?int $navigationSort = 1;

    protected static ?int $level = 2;


    public static function getScopeType(): string
    {
        return 'appraise';
    }

    public static function getScopeId(): int
    {
        return 0;
    }

    public function getLevel(): ?int
    {
        return 2;
    }

    public function getEmptyLabel(): ?string
    {
        return '种质分类数据为空';
    }

    protected function schema(array $arguments): array
    {
        return [
            Forms\Components\TextInput::make('name')->label('分类名称')
                ->placeholder('请输入分类名称')
                ->required(),
            Forms\Components\Textarea::make('description')->label('描述'),

            Schemas\Components\Group::make()
                ->schema([
                    Forms\Components\Radio::make('status')
                        ->label('状态')
                        ->default(CategoryStatus::Normal)
                        ->inline()
                        ->options(CategoryStatus::class)
                        ->columnSpan(1),
                ])->columns(2),
            Forms\Components\Repeater::make('options.fields')
                ->label('自定义字段')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('分组名称')
                        ->placeholder('请输入字段分组名称')
                        ->helperText(fn(string $operation): ?HtmlString => $operation == 'edit' ? new HtmlString('<span style="color: #F59E0B;font-weight: bold">编辑分组名称会导致 评价、编目 等该分组自定义字段值失效</span>') : null)
                        ->required()
                        ->live(onBlur: true)
                        ->rules([
                            fn(Get $get, string $state): Closure => static::repeaterGroupNameUniqueRule($get, $state),
                        ])
                        ->columnSpan(1),
                    Forms\Components\Builder::make('fields')
                        ->label('分组字段')
                        ->hint(fn(string $operation): ?HtmlString => $operation == 'edit' ? new HtmlString('<span style="color: #F59E0B;font-weight: bold">编辑字段名称会导致 评价、编目 等该自定义字段值失效</span>') : null)
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
                                            fn(Get $get, string $state): Closure => static::builderFieldNameUniqueRule($get, $state),
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
                                            fn(Get $get, string $state): Closure => static::builderFieldNameUniqueRule($get, $state),
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
                                            fn(Get $get, string $state): Closure => static::builderFieldNameUniqueRule($get, $state),
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
                                    Schemas\Components\Fieldset::make('Options')
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
                                        ->columnSpanFull()
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
                                            fn(Get $get, string $state): Closure => static::builderFieldNameUniqueRule($get, $state),
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
                            Forms\Components\Builder\Block::make('dateTimePicker')
                                ->label(function (?array $state): string {
                                    $name = $state['name'] ?? '';
                                    return '日期时间' . ($name ? ' - ' . $name : '');
                                })
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->hiddenLabel()
                                        ->placeholder('请输入字段名称')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->rules([
                                            fn(Get $get, string $state): Closure => static::builderFieldNameUniqueRule($get, $state),
                                        ])
                                        ->columnSpan(1),
                                    Forms\Components\Select::make('type')
                                        ->hiddenLabel()
                                        ->placeholder('请选择日期类型')
                                        ->options([
                                            'date' => '日期选择',
                                            'time' => '时间选择',
                                            'datetime' => '日期时间选择',
                                        ])
                                        ->required()
                                        ->live()
                                        ->columnSpan(1),
                                    Forms\Components\Toggle::make('has_second')
                                        ->label('是否需要秒')
                                        ->default(true)
                                        ->visible(fn(Get $get): bool => in_array($get('type'), ['datetime', 'time']))
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
                        ])
                        // ->deleteAction(
                        //     fn (Action $action) => $action->requiresConfirmation(),
                        // )
                        ->extraAttributes(['class' => 'category-custom-field'])
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
                ->extraAttributes(['class' => 'category-custom-field-group'])
                ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                ->addActionLabel('添加分组')
                ->collapsible()
                ->cloneable()
                ->addActionAlignment(Alignment::Start)
                ->columns(2)
        ];
    }


    private static function builderFieldNameUniqueRule($get, $state)
    {
        return function (string $attribute, $value, Closure $fail) use ($get, $state) {
            $duplicates = collect($get('../../'))
                ->filter(fn($block) => isset($block['data']['name']) && !empty($block['data']['name']))     // 过滤空值
                ->map(function ($block) {            // 取出 name
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
                ->map(function ($repeater) {            // 取出 name
                    return $repeater['name'];
                })
                ->duplicates();

            if ($duplicates->isNotEmpty() && $duplicates->contains($state)) {
                $fail('字段名称不能重复');
            }
        };
    }
}
