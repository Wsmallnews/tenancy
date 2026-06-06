<?php

namespace App\Filament\Resources\PhenotypeIdentifies\Schemas;

use App\Features\Common;
use App\Filament\Resources\Concerns\HasCategoryFields;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Wsmallnews\Category\Support\Utils as CategoryUtils;

class PhenotypeIdentifyInfolist
{
    use HasCategoryFields;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    // Sidebar
                    Schemas\Components\View::make('components.schemas.sidebar')
                        ->schema(function ($record) {
                            return array_merge([
                                Schemas\Components\Text::make(Common::sidebar('基本信息', 'piBase', Heroicon::OutlinedRectangleStack)),
                                Schemas\Components\Text::make(Common::sidebar('评价信息', 'piAppraise', Heroicon::OutlinedRectangleStack)),
                            ], self::getSidebarItems($record));
                        })->grow(false),

                    // Content
                    Schemas\Components\Group::make(function ($record) {
                        $sidebarContents = [
                            // 基本信息
                            Schemas\Components\Text::make(Common::title('基本信息', 'piBase')),
                            Schemas\Components\Grid::make([
                                'default' => 1,
                                'xl' => 2,
                                '2xl' => 3,
                            ])
                                ->extraAttributes([
                                    'class' => 'sn-grid-table',
                                ])
                                ->schema([
                                    Infolists\Components\TextEntry::make('name')
                                        ->label('鉴定名称'),
                                    Infolists\Components\TextEntry::make('description')
                                        ->label('鉴定说明'),
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
                                        ->label('鉴定状态'),
                                ])->columnSpanFull(),

                            // 评价信息
                            Schemas\Components\Text::make(Common::title('评价信息', 'piAppraise')),
                            Schemas\Components\Grid::make([
                                'default' => 1,
                                'xl' => 2,
                                '2xl' => 3,
                            ])
                                ->extraAttributes([
                                    'class' => 'sn-grid-table',
                                ])
                                ->schema([
                                    Infolists\Components\SpatieMediaLibraryImageEntry::make('appraise_cover')
                                        ->label('种质封面图')
                                        ->collection('cover')
                                        ->state(fn (Model $record) => $record->appraise?->getFirstMediaUrl('cover'))
                                        ->extraAttributes([
                                            'class' => 'sn-two-rows',
                                        ]),
                                    Infolists\Components\TextEntry::make('appraise.resource_no')
                                        ->label('全国统一编号'),
                                    Infolists\Components\TextEntry::make('appraise.name')
                                        ->label('种质名称'),
                                    Infolists\Components\TextEntry::make('appraise.en_name')
                                        ->label('种质外文名'),
                                    Infolists\Components\TextEntry::make('appraise.subject_name')
                                        ->label('科名'),
                                    Infolists\Components\TextEntry::make('appraise.genus_name')
                                        ->label('属名'),
                                    Infolists\Components\TextEntry::make('appraise.species_name')
                                        ->label('学名'),
                                    Infolists\Components\TextEntry::make('appraise.category.name')
                                        ->label('所属分类'),
                                ])->columnSpanFull(),
                        ];

                        // 动态自定义字段
                        $sidebarContents = array_merge($sidebarContents, self::getSidebarContents($record));

                        return $sidebarContents;
                    })->columnSpanFull(),
                ])
                    ->columnSpanFull()
                    ->from('md'),
            ]);
    }

    protected static function getSidebarItems($record): array
    {
        $schemas = [];

        $category_id = $record->category_id;
        if ($category_id) {
            $category = CategoryUtils::getCategoryModel()::find($category_id);
            if ($category) {
                $fields = $category->options['fields'] ?? [];
                foreach ($fields as $field) {
                    $schemas[] = Schemas\Components\Text::make(
                        Common::sidebar($field['name'], self::getSidebarId($field['name']), Heroicon::OutlinedRectangleStack)
                    );
                }
            }
        }

        return $schemas;
    }

    protected static function getSidebarContents($record): array
    {
        $sidebarContents = [];

        $category_id = $record->category_id;
        if ($category_id) {
            $category = CategoryUtils::getCategoryModel()::find($category_id);
            if ($category) {
                $fields = $category->options['fields'] ?? [];
                foreach ($fields as $key => $field) {
                    $sidebarContents[] = Schemas\Components\Text::make(
                        Common::title($field['name'], self::getSidebarId($field['name']))
                    );

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
                                if ($entryField = static::getEntryFieldsWithoutMedia($fieldKey, $subField)) {
                                    $schemas[] = $entryField;
                                }
                            }

                            return $schemas;
                        })->columnSpanFull();

                    // media 字段
                    foreach ($field['fields'] as $subKey => $subField) {
                        $fieldKey = 'options.fields.'.$key.'.fields.'.$subKey.'.data.value';
                        if ($entryField = static::getEntryFieldsOnlyMedia($fieldKey, $subField)) {
                            $sidebarContents[] = $entryField;
                        }
                    }
                }
            }
        }

        return $sidebarContents;
    }

    private static function getSidebarId(string $name): string
    {
        return 'pi'.Str::Studly(pinyin_permalink($name));
    }
}
