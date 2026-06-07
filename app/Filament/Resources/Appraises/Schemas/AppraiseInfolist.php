<?php

namespace App\Filament\Resources\Appraises\Schemas;

use App\Features\Common;
use App\Features\QrCodeService;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Wsmallnews\Category\Support\Utils;

class AppraiseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\View::make('components.schemas.sidebar')
                        ->schema(function ($record) {
                            return array_merge([
                                Schemas\Components\Text::make(Common::sidebar('基础信息', 'appraiseBase', Heroicon::OutlinedRectangleStack)),
                                Schemas\Components\Text::make(Common::sidebar('地理信息', 'appraiseAddress', Heroicon::OutlinedRectangleStack)),
                                Schemas\Components\Text::make(Common::sidebar('保存信息', 'appraiseSave', Heroicon::OutlinedRectangleStack)),
                                Schemas\Components\Text::make(Common::sidebar('种质特性', 'appraiseFeature', Heroicon::OutlinedRectangleStack)),
                                Schemas\Components\Text::make(Common::sidebar('种质图集', 'appraiseMedia', Heroicon::OutlinedRectangleStack)),
                            ], self::getSidebarItems($record));
                        })->grow(false),
                    Schemas\Components\Group::make(function ($record) {
                        $sidebarContents = [
                            Schemas\Components\Text::make(Common::title('基础信息', 'appraiseBase')),
                            Schemas\Components\Grid::make([
                                'default' => 1,
                                'xl' => 2,
                                '2xl' => 3,
                            ])
                                ->extraAttributes([
                                    'class' => 'sn-grid-table',
                                ])
                                ->schema([
                                    Infolists\Components\SpatieMediaLibraryImageEntry::make('firstMedia')
                                        ->label('种质封面图')
                                        ->collection('cover')
                                        ->extraAttributes([
                                            'class' => 'sn-two-rows',
                                        ]),
                                    Schemas\Components\Text::make(fn (Model $record) => new HtmlString(
                                        '<div class="p-3 border border-gray-200 dark:border-gray-700 rounded-md text-center">'.
                                        '<div class="mb-2">'.QrCodeService::getAppraiseQrSvg($record).'</div>'.
                                        '<a href="'.route('admin.appraises.download-qrcode', $record).'" target="_blank" class="text-sm text-primary-600 hover:text-primary-500">下载二维码</a>'.
                                        '</div>'
                                    ))
                                        ->extraAttributes([
                                            'class' => 'sn-two-rows',
                                        ]),
                                    Infolists\Components\TextEntry::make('category.name')
                                        ->label('所属分类'),
                                    Infolists\Components\TextEntry::make('resource_no')
                                        ->label('全国统一编号'),
                                    Infolists\Components\TextEntry::make('germplasm_no')
                                        ->label('种质圃编号'),
                                    Infolists\Components\TextEntry::make('original_no')
                                        ->label('引种号'),
                                    Infolists\Components\TextEntry::make('gather_no')
                                        ->label('采集号'),
                                    Infolists\Components\TextEntry::make('name')
                                        ->label('种质名称'),
                                    Infolists\Components\TextEntry::make('en_name')
                                        ->label('种质外文名'),
                                    Infolists\Components\TextEntry::make('subject_name')
                                        ->label('科名'),
                                    Infolists\Components\TextEntry::make('genus_name')
                                        ->label('属名'),
                                    Infolists\Components\TextEntry::make('species_name')
                                        ->label('学名'),
                                    Infolists\Components\TextEntry::make('created_at')
                                        ->label('创建时间')
                                        ->dateTime(),
                                    Infolists\Components\TextEntry::make('updated_at')
                                        ->label('更新时间')
                                        ->dateTime(),
                                    Infolists\Components\TextEntry::make('order_column')
                                        ->label('排序')
                                        ->numeric(),
                                    Infolists\Components\TextEntry::make('status')
                                        ->label('评价状态'),
                                ])->columnSpanFull(),
                            Schemas\Components\Text::make(Common::title('地理信息', 'appraiseAddress')),
                            Schemas\Components\Grid::make([
                                'default' => 1,
                                'xl' => 2,
                                '2xl' => 3,
                            ])
                                ->extraAttributes([
                                    'class' => 'sn-grid-table',
                                ])
                                ->schema([
                                    Infolists\Components\TextEntry::make('country_name')
                                        ->label('原产国'),
                                    Infolists\Components\TextEntry::make('district_name')
                                        ->label('原产地区')
                                        ->state(fn (Model $record) => "{$record->province_name} / {$record->city_name}")
                                        ->visible(fn (Model $record) => $record->country_code == 'CN'),
                                    Infolists\Components\TextEntry::make('address')
                                        ->label('原产地'),
                                    Infolists\Components\TextEntry::make('altitude')
                                        ->label('海拔')
                                        ->suffix('米'),
                                    Infolists\Components\TextEntry::make('lng_lat')
                                        ->label('经纬度')
                                        ->state(fn (Model $record) => "{$record->longitude}, {$record->latitude}"),

                                    Infolists\Components\TextEntry::make('source_country_name')
                                        ->label('来源国'),
                                    Infolists\Components\TextEntry::make('source_district_name')
                                        ->label('来源地区')
                                        ->state(fn (Model $record) => "{$record->source_province_name} / {$record->source_city_name}")
                                        ->visible(fn (Model $record) => $record->source_country_code == 'CN'),
                                    Infolists\Components\TextEntry::make('source_address')
                                        ->label('来源地'),
                                ])->columnSpanFull(),
                            Schemas\Components\Text::make(Common::title('保存信息', 'appraiseSave')),
                            Schemas\Components\Grid::make([
                                'default' => 1,
                                'xl' => 2,
                                '2xl' => 3,
                            ])
                                ->extraAttributes([
                                    'class' => 'sn-grid-table',
                                ])
                                ->schema([
                                    Infolists\Components\TextEntry::make('saveCompany.name')
                                        ->label('保存单位')
                                        ->formatStateUsing(fn (Model $record, $state) => $record->saveCompany ? "{$record->saveCompany->name} (编号：{$record->saveCompany->code})" : '-'),
                                    Infolists\Components\TextEntry::make('pedigree')
                                        ->label('系谱'),
                                    Infolists\Components\TextEntry::make('breedingCompany.name')
                                        ->label('选育单位')
                                        ->formatStateUsing(fn (Model $record, $state) => $record->breedingCompany ? "{$record->breedingCompany->name} (编号：{$record->breedingCompany->code})" : '-'),
                                    Infolists\Components\TextEntry::make('cultivationd_at')
                                        ->label('育成年份')
                                        ->date('Y-m'),
                                    Infolists\Components\TextEntry::make('breeding_method')
                                        ->label('选育方法'),
                                ])->columnSpanFull(),
                            Schemas\Components\Text::make(Common::title('种质特性', 'appraiseFeature')),
                            Schemas\Components\Grid::make([
                                'default' => 1,
                                'xl' => 2,
                                '2xl' => 3,
                            ])
                                ->extraAttributes([
                                    'class' => 'sn-grid-table',
                                ])
                                ->schema([
                                    Infolists\Components\TextEntry::make('germplasm_type')
                                        ->label('种质类型'),
                                    Infolists\Components\TextEntry::make('germplasm_use')
                                        ->label('用途'),
                                    Infolists\Components\TextEntry::make('fruit_use')
                                        ->label('果实用途'),
                                    Infolists\Components\TextEntry::make('plant_use')
                                        ->label('植株用途'),
                                    Infolists\Components\TextEntry::make('assemble_resource')
                                        ->label('种植收集源'),
                                    Infolists\Components\TextEntry::make('assemble_material_type')
                                        ->label('收集材料类型'),
                                    Infolists\Components\TextEntry::make('observe_place')
                                        ->label('观测地点'),
                                ])->columnSpanFull(),
                            Schemas\Components\Text::make(Common::title('种质图集', 'appraiseMedia')),
                            Schemas\Components\Group::make()
                                ->schema(function (Model $record) {
                                    return Common::mediasEntry($record, 'galleries');
                                })
                                ->extraAttributes([
                                    'class' => 'sn-attachment-group',
                                ])
                                ->columns(['default' => 1,  'xl' => 2])->columnSpanFull(),
                        ];

                        $sidebarContents = array_merge($sidebarContents, self::getSidebarContents($record));

                        return $sidebarContents;
                    })->columnSpanFull(),
                ])
                    ->columnSpanFull()
                    ->from('md'),
            ]);
    }

    protected static function getSidebarItems($record)
    {
        $schemas = [];

        $category_id = $record->category_id;
        if ($category_id) {
            $category = Utils::getCategoryModel()::findOrFail($category_id);

            $fields = $category->options['fields'] ?? [];
            foreach ($fields as $key => $field) {
                $schemas[] = Schemas\Components\Text::make(Common::sidebar($field['name'], self::getSidebarId($field['name']), Heroicon::OutlinedRectangleStack));
            }
        }

        return $schemas;
    }

    protected static function getSidebarContents($record)
    {
        $sidebarContents = [];

        $category_id = $record->category_id;
        if ($category_id) {
            $category = Utils::getCategoryModel()::findOrFail($category_id);

            $fields = $category->options['fields'] ?? [];
            foreach ($fields as $key => $field) {
                $sidebarContents[] = Schemas\Components\Text::make(Common::title($field['name'], self::getSidebarId($field['name'])));

                // 非 media 字段
                $sidebarContents[] = Schemas\Components\Grid::make([
                    'default' => 1,
                    'xl' => 2,
                    '2xl' => 3,
                ])
                    ->extraAttributes([
                        'class' => 'sn-grid-table',
                    ])
                    ->schema(function () use ($key, $field) {
                        $schemas = [];
                        foreach ($field['fields'] as $subKey => $subField) {
                            $fieldKey = 'options.fields.'.$key.'.fields.'.$subKey.'.data.value';
                            if ($entryField = static::getEntryFieldsWithoutMedia($fieldKey, $subField)) {     // 根据参数获取对应的 entry
                                $schemas[] = $entryField;
                            }
                        }

                        return $schemas;
                    })->columnSpanFull();

                // media 字段
                foreach ($field['fields'] as $subKey => $subField) {
                    $fieldKey = 'options.fields.'.$key.'.fields.'.$subKey.'.data.value';
                    if ($entryField = static::getEntryFieldsOnlyMedia($fieldKey, $subField)) {     // 根据参数获取对应的表单
                        // $schemas = array_merge($schemas, $entryField);
                        $sidebarContents[] = $entryField;
                    }
                }
            }
        }

        return $sidebarContents;
    }

    /**
     * 根据类型获取特定的 entry 字段(除了 media)
     *
     * @param  string  $fieldKey
     * @param  array  $subField
     */
    protected static function getEntryFieldsWithoutMedia($fieldKey, $subField): ?Infolists\Components\Entry
    {
        $type = $subField['type'] ?? null;
        $data = $subField['data'] ?? [];

        if ($type == 'textInput' || $type == 'number' || $type == 'select') {
            $entry = Infolists\Components\TextEntry::make($fieldKey)
                ->label($data['name'] ?? null)
                ->placeholder($data['placeholder'] ?? null)
                ->suffix($data['unit'] ?? null);
        } elseif ($type == 'dateTimePicker') {
            $field_type = $data['type'];
            match ($field_type) {
                'date' => $entry = Infolists\Components\TextEntry::make($fieldKey),
                'time' => $entry = Infolists\Components\TextEntry::make($fieldKey)->time('H:i'.(($data['has_second'] ?? true) ? ':s' : '')),
                'datetime' => $entry = Infolists\Components\TextEntry::make($fieldKey)->dateTime('Y-m-d H:i'.(($data['has_second'] ?? true) ? ':s' : '')),
                default => $entry = Infolists\Components\TextEntry::make($fieldKey),
            };

            $entry = $entry
                ->label($data['name'] ?? null)
                ->placeholder($data['placeholder'] ?? null)
                ->suffix($data['unit'] ?? null);
        }

        return $entry ?? null;
    }

    /**
     * 根据类型获取特定的 entry 字段(仅 media)
     *
     * @param  string  $fieldKey
     * @param  array  $subField
     */
    protected static function getEntryFieldsOnlyMedia($fieldKey, $subField): ?Schemas\Components\Group
    {
        $type = $subField['type'] ?? null;
        $data = $subField['data'] ?? [];

        if ($type == 'upload_image') {
            $schema = Schemas\Components\Group::make()
                ->schema(function (Model $record) use ($data) {
                    return Common::mediasEntry($record, $data['collection_name'] ?? null, $data['name'] ?? null);
                })
                ->extraAttributes([
                    'class' => 'sn-attachment-group',
                ])
                ->columns(['default' => 1,  'xl' => 2])->columnSpanFull();
        }

        return $schema ?? null;
    }

    private static function getSidebarId($name)
    {
        return 'appraise'.Str::Studly(pinyin_permalink($name));
    }
}
