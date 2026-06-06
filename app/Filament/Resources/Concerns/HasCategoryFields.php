<?php

namespace App\Filament\Resources\Concerns;

use App\Features\Common;
use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component as Livewire;
use Wsmallnews\Category\Support\Utils;
use Wsmallnews\Support\Filament\Forms\FormComponents;

/**
 * 种质分类自定义字段公共 trait
 *
 * 提供表单渲染、字段水化、数据处理、Infolist 展示等公共方法
 * 供 AppraiseResource、PhenotypeIdentifyForm 等复用
 */
trait HasCategoryFields
{
    /**
     * 获取分类的自定义字段 Tabs
     *
     * @param  Get  $get
     * @param  string  $categoryFieldName  分类字段名（默认 category_id）
     * @param  string  $optionsPrefix  options 字段前缀（默认 options）
     */
    public static function getCategoryTabs(Get $get, string $categoryFieldName = 'category_id', string $optionsPrefix = 'options'): array
    {
        $tabs = [];

        $category_id = $get($categoryFieldName);
        if ($category_id) {
            $category = Utils::getCategoryModel()::findOrFail($category_id);

            $fields = $category->options['fields'] ?? [];
            foreach ($fields as $key => $field) {
                $tabs[] = Schemas\Components\Tabs\Tab::make($field['name'])
                    ->schema(function () use ($key, $field, $optionsPrefix) {
                        $schemas = [];
                        foreach ($field['fields'] as $subKey => $subField) {
                            $fieldKey = $optionsPrefix . '.fields.' . $key . '.fields.' . $subKey . '.data.value';
                            if ($formField = static::getFormFields($fieldKey, $subField)) {     // 根据参数获取对应的表单
                                $schemas[] = $formField;
                            }
                        }

                        return $schemas;
                    })
                    ->columns(2);
            }
        }

        return $tabs;
    }

    /**
     * 根据类型获取特定的表单字段
     *
     * @param  string  $fieldKey
     * @param  array  $subField
     */
    public static function getFormFields(string $fieldKey, array $subField): ?Forms\Components\Field
    {
        $type = $subField['type'] ?? null;
        $data = $subField['data'] ?? [];
        $field = null;

        if ($type == 'textInput' || $type == 'number') {
            $regex_message = $data['regex_message'] ?? null;
            $validationMessages = [];
            if ($regex_message) {
                $validationMessages['regex'] = $regex_message;
            }

            $field = Forms\Components\TextInput::make($fieldKey)
                ->label($data['name'] ?? null)
                ->placeholder($data['placeholder'] ?? null)
                ->suffix($data['unit'] ?? null)
                ->required($data['is_required'] ?? false)
                ->regex($data['regex'] ?? null)
                ->validationMessages($validationMessages);
        } elseif ($type == 'select') {
            $options = $data['options'] ?? [];
            $options = Arr::mapWithKeys($options, function ($item) {
                return [$item => $item];
            });

            $field = Forms\Components\Select::make($fieldKey)
                ->label($data['name'] ?? null)
                ->placeholder($data['placeholder'] ?? null)
                ->suffix($data['unit'] ?? null)
                ->required($data['is_required'] ?? false)
                ->options($options);
        } elseif ($type == 'upload_image') {
            $field = FormComponents::mediaImageUpload($fieldKey, $data['collection_name'] ?? null)
                ->label($data['name'] ?? null)
                ->helperText('支持上传图片')
                ->required($data['is_required'] ?? false)
                ->multiple($data['is_multiple'] ?? false)
                ->minFiles($data['min_files'] ?? 1)
                ->maxFiles((isset($data['max_files_num']) && $data['max_files_num'] > 0) ? $data['max_files_num'] : 20)
                ->uploadingMessage(($data['name'] ?? '图片') . '上传中...')
                ->columns(1);
        } elseif ($type == 'dateTimePicker') {
            $field_type = $data['type'];
            match ($field_type) {
                'date' => $field = Forms\Components\DatePicker::make($fieldKey),
                'time' => $field = Forms\Components\TimePicker::make($fieldKey)->seconds($data['has_second'] ?? true)->displayFormat('H:i' . (($data['has_second'] ?? true) ? ':s' : '')),
                'datetime' => $field = Forms\Components\DateTimePicker::make($fieldKey)->seconds($data['has_second'] ?? true)->displayFormat('Y-m-d H:i' . (($data['has_second'] ?? true) ? ':s' : '')),
                default => $field = Forms\Components\TextInput::make($fieldKey),
            };

            $field = $field
                ->native(false)
                ->label($data['name'] ?? null)
                ->placeholder($data['placeholder'] ?? null)
                ->suffix($data['unit'] ?? null)
                ->required($data['is_required'] ?? false);
        }

        return $field ?? null;
    }

    /**
     * tab 字段水化，保证分类中自定义字段改变顺序时，数据库中保存的值也能正确显示
     *
     * @return void
     */
    public static function hydratedFields(Schemas\Components\Tabs $component, ?array $state)
    {
        $record = $component->getRecord();
        if (! $record) {
            $component->state($state);

            return;
        }

        // 这里一定要使用 state 中的值 (不可使用 $record 数据库中的值，没有 media 数据),里面已经包括了关联查的数据,比如  laravel-medialibrary 关联的 media 资源标识
        $recordOptions = $state['options'] ?? [];
        $recordFields = $recordOptions['fields'] ?? [];

        $category_id = $state['category_id'];
        if (! $category_id) {
            $component->state($state);

            return;
        }

        $category = Utils::getCategoryModel()::findOrFail($category_id);
        $fields = $category->options['fields'] ?? [];

        foreach ($fields as $key => $field) {
            $name = $field['name'] ?? null;
            $currentRecordFields = Arr::where($recordFields, function (array $value, int $key) use ($name) {
                $valueName = $value['name'] ?? null;

                return $valueName == $name && ! is_null($valueName);
            });

            $recordField = Arr::first($currentRecordFields);
            if (empty($recordField)) {
                continue;
            }

            foreach ($field['fields'] as $subKey => $subField) {
                $currentRecordSubFields = Arr::where($recordField['fields'] ?? [], function (array $value, int $key) use ($subField) {
                    $valueName = $value['data']['name'] ?? null;
                    $subFieldName = $subField['data']['name'] ?? null;

                    return $valueName == $subFieldName && ! is_null($valueName);
                });

                $recordSubField = Arr::first($currentRecordSubFields);

                if (empty($recordSubField)) {
                    continue;
                }

                $fields[$key]['fields'][$subKey]['data']['value'] = $recordSubField['data']['value'] ?? null;
            }
        }

        $state['options']['fields'] = $fields;

        $component->state($state);
    }

    /**
     * 保存前处理数据，重组 options 字段
     *
     * @param  array  $data
     * @param  string  $categoryFieldName  分类字段名
     * @return array
     */
    public static function getFieldsInfo(array $data, string $categoryFieldName = 'category_id'): array
    {
        $currentOptions = $data['options'] ?? [];
        $currentFields = $currentOptions['fields'] ?? [];
        $category_id = $data[$categoryFieldName];

        if ($category_id) {
            $category = Utils::getCategoryModel()::findOrFail($category_id);
            $fields = $category->options['fields'] ?? [];

            foreach ($fields as $key => $field) {
                foreach ($field['fields'] as $subKey => $subField) {
                    $fields[$key]['fields'][$subKey]['data']['value'] = $currentFields[$key]['fields'][$subKey]['data']['value'] ?? null;
                }
            }
            $currentOptions['fields'] = $fields;
        }

        return $currentOptions;
    }

    /**
     * 根据类型获取 Infolist entry 字段（非 media 类型）
     *
     * @param  string  $fieldKey
     * @param  array  $subField
     * @return Infolists\Components\Entry|null
     */
    public static function getEntryFieldsWithoutMedia(string $fieldKey, array $subField): ?Infolists\Components\Entry
    {
        $type = $subField['type'] ?? null;
        $data = $subField['data'] ?? [];
        $entry = null;

        if ($type == 'textInput' || $type == 'number' || $type == 'select') {
            $entry = Infolists\Components\TextEntry::make($fieldKey)
                ->label($data['name'] ?? null)
                ->placeholder($data['placeholder'] ?? null)
                ->suffix($data['unit'] ?? null);
        } elseif ($type == 'dateTimePicker') {
            $field_type = $data['type'];
            match ($field_type) {
                'date' => $entry = Infolists\Components\TextEntry::make($fieldKey),
                'time' => $entry = Infolists\Components\TextEntry::make($fieldKey)->time('H:i' . (($data['has_second'] ?? true) ? ':s' : '')),
                'datetime' => $entry = Infolists\Components\TextEntry::make($fieldKey)->dateTime('Y-m-d H:i' . (($data['has_second'] ?? true) ? ':s' : '')),
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
     * 根据类型获取 Infolist entry 字段（仅 media 类型）
     *
     * @param  string  $fieldKey
     * @param  array  $subField
     * @return Schemas\Components\Group|null
     */
    public static function getEntryFieldsOnlyMedia(string $fieldKey, array $subField): ?Schemas\Components\Group
    {
        $type = $subField['type'] ?? null;
        $data = $subField['data'] ?? [];
        $schema = null;

        if ($type == 'upload_image') {
            $schema = Schemas\Components\Group::make()
                ->schema(function (Model $record) use ($data) {
                    return Common::mediasEntry($record, $data['collection_name'] ?? null, $data['name'] ?? null);
                })
                ->extraAttributes([
                    'class' => 'sn-attachment-group',
                ])
                ->columns(['default' => 1, 'xl' => 2])->columnSpanFull();
        }

        return $schema ?? null;
    }

    /**
     * 根据类型获取 Table 列（非 media 类型）
     *
     * @param  string  $fieldKey
     * @param  array  $subField
     * @param  string  $columnPrefix  列前缀（用于区分同名字段）
     * @return Tables\Columns\Column|null
     */
    public static function getTableColumnWithoutMedia(string $fieldKey, array $subField, string $columnPrefix = ''): ?Tables\Columns\Column
    {
        $type = $subField['type'] ?? null;
        $data = $subField['data'] ?? [];
        $label = ($columnPrefix ? $columnPrefix . ' - ' : '') . ($data['name'] ?? null);
        $column = null;

        if ($type == 'textInput' || $type == 'number' || $type == 'select') {
            $column = Tables\Columns\TextColumn::make($fieldKey)
                ->label($label)
                ->suffix($data['unit'] ?? null)
                ->toggleable();
        } elseif ($type == 'dateTimePicker') {
            $field_type = $data['type'] ?? 'date';
            $format = match ($field_type) {
                'date' => 'Y-m-d',
                'time' => 'H:i' . (($data['has_second'] ?? true) ? ':s' : ''),
                'datetime' => 'Y-m-d H:i' . (($data['has_second'] ?? true) ? ':s' : ''),
                default => 'Y-m-d',
            };

            $column = Tables\Columns\TextColumn::make($fieldKey)
                ->label($label)
                ->dateTime($format)
                ->toggleable();
        }

        return $column;
    }

    /**
     * 根据类型获取 Table 列（仅 media 类型）
     *
     * @param  string  $fieldKey
     * @param  array  $subField
     * @param  string  $columnPrefix
     * @return Tables\Columns\Column|null
     */
    public static function getTableColumnOnlyMedia(string $fieldKey, array $subField, string $columnPrefix = ''): ?Tables\Columns\Column
    {
        $type = $subField['type'] ?? null;
        $data = $subField['data'] ?? [];
        $label = ($columnPrefix ? $columnPrefix . ' - ' : '') . ($data['name'] ?? null);
        $column = null;

        if ($type == 'upload_image') {
            $column = Tables\Columns\SpatieMediaLibraryImageColumn::make($fieldKey)
                ->label($label)
                ->collection($data['collection_name'] ?? null)
                ->toggleable();
        }

        return $column ?? null;
    }
}
