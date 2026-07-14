<?php

namespace App\Filament\Resources\PhenotypeIdentifies\Schemas;

use App\Enums\PhenotypeIdentifies\Status;
use App\Filament\Resources\Concerns\HasCategoryFields;
use App\Models\Appraise;
use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Wsmallnews\Category\Support\Utils as CategoryUtils;

class PhenotypeIdentifyForm
{
    use HasCategoryFields;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        // 选择评价
                        Schemas\Components\Section::make('种质信息')->schema([
                            Forms\Components\Select::make('appraise_id')->label('选择评价')
                                ->relationship(name: 'appraise', titleAttribute: 'name', modifyQueryUsing: function (Builder $query, Component $livewire) {
                                    $query->normal()->orderBy('order_column', 'asc');

                                    $categoryId = $livewire->categoryId ?? null;

                                    if ($categoryId) {
                                        $query->where('category_id', $categoryId);
                                    }

                                    return $query;
                                })
                                ->getOptionLabelFromRecordUsing(fn (Appraise $record) => "{$record->resource_no} - {$record->name}")
                                ->placeholder('请选择评价')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->disabled(fn (string $operation) => $operation == 'edit')
                                ->afterStateUpdated(function (Get $get, Set $set) {     // 字段更新后触发，只能前端更新才会触发，$set 更新该无效
                                    $appraise_id = $get('appraise_id') ?? 0;
                                    $appraise = $appraise_id ? Appraise::find($appraise_id) : null;
                                    $set('category_id', $appraise->category_id ?? 0);
                                })
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\Hidden::make('category_id'),
                            // 评价基本信息展示
                            Schemas\Components\Grid::make([
                                'default' => 1,
                                'lg' => 2,
                                'xl' => 3,
                            ])
                                ->extraAttributes([
                                    'class' => 'sn-grid-table',
                                ])
                                ->schema(function (Get $get) {
                                    if ($get('appraise_id') && $appraise = Appraise::find($get('appraise_id'))) {
                                        $coverMedia = $appraise->getFirstMedia('cover');

                                        return [
                                            Infolists\Components\ImageEntry::make('appraise_cover')
                                                ->label('种质封面图')
                                                ->state($coverMedia?->getFullUrl())
                                                ->extraAttributes([
                                                    'class' => 'sn-two-rows',
                                                ]),
                                            Infolists\Components\TextEntry::make('appraise_resource_no')
                                                ->label('全国统一编号')
                                                ->state($appraise->resource_no),
                                            Infolists\Components\TextEntry::make('appraise_name')
                                                ->label('种质名称')
                                                ->state($appraise->name),
                                            Infolists\Components\TextEntry::make('appraise_en_name')
                                                ->label('种质外文名')
                                                ->state($appraise->en_name),
                                            Infolists\Components\TextEntry::make('appraise_subject_name')
                                                ->label('科名')
                                                ->state($appraise->subject_name),
                                            Infolists\Components\TextEntry::make('appraise_genus_name')
                                                ->label('属名')
                                                ->state($appraise->genus_name),
                                            Infolists\Components\TextEntry::make('appraise_species_name')
                                                ->label('学名')
                                                ->state($appraise->species_name),
                                            Infolists\Components\TextEntry::make('appraise_category')
                                                ->label('所属分类')
                                                ->state($appraise->category?->name ?? '-'),
                                        ];
                                    }
                                })
                                ->visible(fn (Get $get): bool => boolval($get('appraise_id')))
                                ->columnSpanFull(),
                        ])->columns(2),

                        // 表型鉴定信息
                        Schemas\Components\Section::make('表型鉴定信息')->schema([
                            Forms\Components\TextInput::make('name')->label('鉴定名称')
                                ->placeholder('请输入鉴定名称')
                                ->required(),
                            Forms\Components\Textarea::make('description')->label('鉴定说明')
                                ->placeholder('请输入鉴定说明')
                                ->rows(3),
                        ])->columns(2),

                        // 自定义字段动态 Tabs
                        Schemas\Components\Tabs::make('Tabs')
                            ->tabs(function (Get $get) {
                                // 从选择的评价获取分类，再加载自定义字段
                                $appraise_id = $get('appraise_id');
                                if (! $appraise_id) {
                                    return [];
                                }

                                $appraise = Appraise::find($appraise_id);
                                if (! $appraise || ! $appraise->category_id) {
                                    return [];
                                }

                                $category = CategoryUtils::getCategoryModel()::find($appraise->category_id);
                                if (! $category) {
                                    return [];
                                }

                                $tabs = [];
                                $fields = $category->options['fields'] ?? [];
                                foreach ($fields as $key => $field) {
                                    $tabs[] = Schemas\Components\Tabs\Tab::make($field['name'])
                                        ->schema(function () use ($key, $field) {
                                            $schemas = [];
                                            foreach ($field['fields'] as $subKey => $subField) {
                                                $fieldKey = 'options.fields.'.$key.'.fields.'.$subKey.'.data.value';
                                                if ($formField = static::getFormFields($fieldKey, $subField)) {
                                                    $schemas[] = $formField;
                                                }
                                            }

                                            return $schemas;
                                        })
                                        ->columns(2);
                                }

                                return $tabs;
                            })
                            ->afterStateHydrated(function (Schemas\Components\Tabs $component, ?array $state) {
                                static::hydratedFields($component, $state);
                            })
                            ->key('dynamicTabs')
                            ->visible(fn (Get $get): bool => boolval($get('appraise_id')))
                            ->columns(1)->columnSpanFull(),
                    ])->columns(1),

                    // 状态侧边栏
                    Schemas\Components\Section::make('状态')->schema([
                        Forms\Components\TextInput::make('order_column')->label('排序')->integer()
                            ->placeholder('正序排列')
                            ->rules(['integer', 'min:0']),
                        Forms\Components\Radio::make('status')
                            ->label('鉴定状态')
                            ->default(Status::Normal)
                            ->inline()
                            ->options(Status::class),
                    ])->grow(false),
                ])
                    ->columnSpanFull()
                    ->from('lg'),
            ]);
    }
}
