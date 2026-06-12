<?php

namespace App\Filament\Resources\Appraises\Imports;

use App\Enums\Appraises\Status;
use App\Features\District;
use App\Models\Appraise;
use App\Models\Company;
use App\Settings\AppraiseSettings;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Wsmallnews\Category\Enums\CategoryStatus;
use Wsmallnews\Category\Support\Utils as CategoryUtils;
use Wsmallnews\Support\Exceptions\SupportException;

class AppraiseImporter extends Importer
{
    protected static ?string $model = Appraise::class;

    /**
     * 分类查找结果缓存（名称 → ID）
     *
     * @var array<string, int>
     */
    protected static array $categoryCache = [];

    /**
     * 公司名称查找缓存（名称 → ID）
     *
     * @var array<string, int|null>
     */
    protected static array $companyCache = [];

    /**
     * 省市区级联数据缓存
     */
    protected static ?array $districtCascader = null;

    /**
     * 已知的 CSV 列名集合（中文表头），这些列不会存入 options 字段
     */
    protected static array $knownCsvColumns = [
        '分类', '全国统一编号', '种质圃编号', '引种号', '采集号',
        '种质名称', '种质外文名', '科名', '属名', '学名',
        '原产国代码', '原产国', '原产省', '原产市', '原产地址', '海拔', '经度', '纬度',
        '来源国代码', '来源国', '来源省', '来源市', '来源地址',
        '保存单位', '系谱', '选育单位',
        '育成年份', '选育方法',
        '种质类型', '种质用途', '果实用途', '植株用途',
        '种植收集源', '收集材料类型', '观测地点',
        '状态',
    ];

    public static function getColumns(): array
    {
        return [
            // === 分类（必填，按名称查找） ===
            ImportColumn::make('category_id')
                ->label('分类')
                ->requiredMapping()
                ->fillRecordUsing(function ($state, $record) {
                    if (is_numeric($state) && (int) $state > 0) {
                        $record->category_id = (int) $state;
                    } else {
                        $record->category_id = static::resolveCategoryByName((string) $state);
                    }
                })
                ->exampleHeader('分类')
                ->example('苹果'),

            // === 基础信息 ===
            ImportColumn::make('resource_no')
                ->label('全国统一编号')
                ->requiredMapping()
                ->exampleHeader('全国统一编号')
                ->example('E210101'),

            ImportColumn::make('germplasm_no')
                ->label('种质圃编号')
                ->requiredMapping()
                ->exampleHeader('种质圃编号')
                ->example('GP2024001'),

            ImportColumn::make('original_no')
                ->label('引种号')
                ->exampleHeader('引种号')
                ->example('YZ-2024-001'),

            ImportColumn::make('gather_no')
                ->label('采集号')
                ->exampleHeader('采集号')
                ->example('CJ2024001'),

            ImportColumn::make('name')
                ->label('种质名称')
                ->requiredMapping()
                ->exampleHeader('种质名称')
                ->example('红富士'),

            ImportColumn::make('en_name')
                ->label('种质外文名')
                ->exampleHeader('种质外文名')
                ->example('Red Fuji'),

            ImportColumn::make('subject_name')
                ->label('科名')
                ->exampleHeader('科名')
                ->example('蔷薇科'),

            ImportColumn::make('genus_name')
                ->label('属名')
                ->exampleHeader('属名')
                ->example('苹果属'),

            ImportColumn::make('species_name')
                ->label('学名')
                ->exampleHeader('学名')
                ->example('Malus domestica'),

            // === 地理信息 ===
            ImportColumn::make('country_code')
                ->label('原产国代码')
                ->exampleHeader('原产国代码')
                ->example('CN'),

            ImportColumn::make('country_name')
                ->label('原产国')
                ->exampleHeader('原产国')
                ->example('中国'),

            ImportColumn::make('province_name')
                ->label('原产省')
                ->exampleHeader('原产省')
                ->example('山东'),

            ImportColumn::make('city_name')
                ->label('原产市')
                ->exampleHeader('原产市')
                ->example('烟台'),

            ImportColumn::make('address')
                ->label('原产地址')
                ->exampleHeader('原产地址')
                ->example('山东省烟台市栖霞市'),

            ImportColumn::make('altitude')
                ->label('海拔')
                ->integer()
                ->exampleHeader('海拔')
                ->example(500),

            ImportColumn::make('longitude')
                ->label('经度')
                ->exampleHeader('经度')
                ->example('121.39'),

            ImportColumn::make('latitude')
                ->label('纬度')
                ->exampleHeader('纬度')
                ->example('37.31'),

            // === 来源信息 ===
            ImportColumn::make('source_country_code')
                ->label('来源国代码')
                ->exampleHeader('来源国代码')
                ->example('CN'),

            ImportColumn::make('source_country_name')
                ->label('来源国')
                ->exampleHeader('来源国')
                ->example('中国'),

            ImportColumn::make('source_province_name')
                ->label('来源省')
                ->exampleHeader('来源省')
                ->example('山东'),

            ImportColumn::make('source_city_name')
                ->label('来源市')
                ->exampleHeader('来源市')
                ->example('烟台'),

            ImportColumn::make('source_address')
                ->label('来源地址')
                ->exampleHeader('来源地址')
                ->example('山东省烟台市'),

            // === 保存信息 ===
            ImportColumn::make('save_company_id')
                ->label('保存单位')
                ->fillRecordUsing(function ($state, $record) {
                    $companyId = static::findCompanyByName($state);
                    if ($companyId) {
                        $record->save_company_id = $companyId;
                    }
                })
                ->exampleHeader('保存单位')
                ->example('国家苹果种质资源圃'),

            ImportColumn::make('pedigree')
                ->label('系谱')
                ->exampleHeader('系谱')
                ->example('富士×元帅'),

            ImportColumn::make('breeding_company_id')
                ->label('选育单位')
                ->fillRecordUsing(function ($state, $record) {
                    $companyId = static::findCompanyByName($state);
                    if ($companyId) {
                        $record->breeding_company_id = $companyId;
                    }
                })
                ->exampleHeader('选育单位')
                ->example('山东省农科院'),

            ImportColumn::make('cultivationd_at')
                ->label('育成年份')
                ->exampleHeader('育成年份')
                ->example('1980-01-01'),

            ImportColumn::make('breeding_method')
                ->label('选育方法')
                ->exampleHeader('选育方法')
                ->example('杂交育种'),

            // === 种质特性 ===
            ImportColumn::make('germplasm_type')
                ->label('种质类型')
                ->exampleHeader('种质类型')
                ->example('地方品种'),

            ImportColumn::make('germplasm_use')
                ->label('种质用途')
                ->exampleHeader('种质用途')
                ->example('鲜食'),

            ImportColumn::make('fruit_use')
                ->label('果实用途')
                ->exampleHeader('果实用途')
                ->example('鲜食'),

            ImportColumn::make('plant_use')
                ->label('植株用途')
                ->exampleHeader('植株用途')
                ->example('砧木'),

            ImportColumn::make('assemble_resource')
                ->label('种植收集源')
                ->exampleHeader('种植收集源')
                ->example('野外采集'),

            ImportColumn::make('assemble_material_type')
                ->label('收集材料类型')
                ->exampleHeader('收集材料类型')
                ->example('枝条'),

            ImportColumn::make('observe_place')
                ->label('观测地点')
                ->exampleHeader('观测地点')
                ->example('国家种质圃'),
        ];
    }

    /**
     * 始终创建新记录（不按 ID 查找已有记录）
     */
    public function resolveRecord(): ?Model
    {
        return new Appraise;
    }

    /**
     * 数据转换：处理状态中文值，设置国家代码默认值
     */
    public function castData(): void
    {
        parent::castData();

        // 设置国家代码默认值
        if (blank($this->data['country_code'] ?? null) && ! blank($this->data['country_name'] ?? null)) {
            $this->data['country_code'] = static::guessCountryCode($this->data['country_name']);
        }

        if (blank($this->data['source_country_code'] ?? null) && ! blank($this->data['source_country_name'] ?? null)) {
            $this->data['source_country_code'] = static::guessCountryCode($this->data['source_country_name']);
        }
    }

    /**
     * 填充记录后，确保国家代码有默认值
     */
    public function fillRecord(): void
    {
        parent::fillRecord();

        $record = $this->record;

        if (blank($record->country_code)) {
            $record->country_code = 'CN';
        }

        if (blank($record->source_country_code)) {
            $record->source_country_code = 'CN';
        }
    }

    /**
     * 创建前处理：
     * 1. 确保分类已关联（未关联则自动解析为"其它"）
     * 2. 初始化 options 字段
     * 3. 收集未映射的 CSV 列，匹配分类自定义字段后存入 options
     * 4. 解析省市名称对应的 ID
     * 5. 同步 AppraiseSettings 中的枚举字段
     */
    protected function beforeCreate(): void
    {
        $record = $this->record;

        // 确保分类已关联：如果 category_id 未设置或为 0，解析为"其它"分类
        if (empty($record->category_id)) {
            $record->category_id = static::resolveCategoryByName('其它');
        }

        // 初始化 options
        if (! is_array($record->options)) {
            $record->options = [];
        }

        $record->status = Status::Normal->value;

        // 收集未映射的 CSV 列，与分类自定义字段匹配后存入 options
        $this->collectUnmappedOptions();

        // 解析省市名称对应的 ID
        if (! blank($record->province_name)) {
            $record->province_id = static::resolveProvinceId($record->province_name);

            if (! blank($record->city_name)) {
                $record->city_id = static::resolveCityId($record->province_name, $record->city_name);
            }
        }

        if (! blank($record->source_province_name)) {
            $record->source_province_id = static::resolveProvinceId($record->source_province_name);

            if (! blank($record->source_city_name)) {
                $record->source_city_id = static::resolveCityId($record->source_province_name, $record->source_city_name);
            }
        }

        // 同步设置：如果导入的枚举值不存在于 AppraiseSettings 中，自动添加
        $this->syncAppraiseSettings();
    }

    // ───────────────────── 分类匹配 ─────────────────────

    /**
     * 根据名称解析分类 ID
     *
     * 匹配优先级：精确匹配 → "其他"/"其它" 回退 → 自动创建"其它"分类
     */
    protected static function resolveCategoryByName(?string $name): int
    {
        $cacheKey = $name ?? '__fallback__';

        if (isset(static::$categoryCache[$cacheKey])) {
            return static::$categoryCache[$cacheKey];
        }

        $categoryModel = CategoryUtils::getCategoryModel();

        // 1. 精确匹配
        if (! blank($name)) {
            $category = $categoryModel::where('name', $name)
                ->whereNotNull('parent_id')
                ->snScope('appraise', 0)
                ->first();

            if ($category) {
                return static::$categoryCache[$cacheKey] = $category->id;
            }
        }

        // 2. 回退：查找 "其他" 或 "其它" 分类
        $fallbackCategory = $categoryModel::where(function ($q) {
            $q->where('name', '其他')
                ->orWhere('name', '其它');
        })
            ->whereNotNull('parent_id')
            ->snScope('appraise', 0)
            ->first();

        if ($fallbackCategory) {
            return static::$categoryCache[$cacheKey] = $fallbackCategory->id;
        }

        // 3. 自动创建 "其它" 分类
        $created = static::createFallbackCategory();

        return static::$categoryCache[$cacheKey] = $created->id;
    }

    /**
     * 创建 "其它" 兜底分类
     */
    protected static function createFallbackCategory(): Model
    {
        $categoryModel = CategoryUtils::getCategoryModel();
        $categoryTypeModel = CategoryUtils::getCategoryTypeModel();

        // 查找对应的 CategoryType（不存在则 type_id=0）
        $categoryType = $categoryTypeModel::snScope('appraise', 0)
            ->first();
        if (! $categoryType) {
            throw new SupportException('未找到对应的种质分类，请先创建种质分类');
        }

        $parentCategory = $categoryModel::where(function ($q) {
            $q->where('name', '其他')
                ->orWhere('name', '其它');
        })
            ->whereNull('parent_id')
            ->snScope('appraise', 0)
            ->first();

        $categoryAttributes = [
            'team_id' => current_tenant()?->id,
            'scope_type' => 'appraise',
            'scope_id' => 0,
            'type_id' => $categoryType->id,
            'name' => '其它',
            'status' => CategoryStatus::Normal,
        ];
        if (! $parentCategory) {
            $parentCategory = $categoryModel::create($categoryAttributes);
        }

        return $categoryModel::create($categoryAttributes, $parentCategory);
    }

    // ───────────────────── options 处理 ─────────────────────

    /**
     * 收集未映射的 CSV 列，与分类自定义字段匹配后存入 options
     *
     * - 所有额外列原始值存入 options.imports（备份）
     * - 匹配到分类自定义字段的值填入 options.fields 结构中
     */
    protected function collectUnmappedOptions(): void
    {
        $mappedCsvHeaders = array_filter(array_values($this->columnMap));
        $extraData = [];

        foreach ($this->originalData as $csvHeader => $value) {
            if (! in_array($csvHeader, $mappedCsvHeaders) && ! in_array($csvHeader, static::$knownCsvColumns)) {
                $extraData[$csvHeader] = $value;
            }
        }

        if (empty($extraData)) {
            return;
        }

        $options = $this->record->options ?? [];

        // 1. 所有额外列原始值存入 options.imports 作为备份
        $options['imports'] = $extraData;

        // 2. 读取分类的自定义字段结构，尝试匹配额外列
        $categoryId = $this->record->category_id;
        if ($categoryId) {
            $categoryModel = CategoryUtils::getCategoryModel();
            $category = $categoryModel::find($categoryId);
            $categoryFields = $category->options['fields'] ?? [];

            // 遍历分类的每个分组和子字段，匹配 CSV 额外列
            foreach ($categoryFields as $key => $group) {
                foreach ($group['fields'] ?? [] as $subKey => $subField) {
                    $fieldName = $subField['data']['name'] ?? null;

                    if ($fieldName && array_key_exists($fieldName, $extraData)) {
                        $categoryFields[$key]['fields'][$subKey]['data']['value'] = $extraData[$fieldName];
                    }
                }
            }

            $options['fields'] = $categoryFields;
        }

        $this->record->options = $options;
    }

    // ───────────────────── 辅助方法 ─────────────────────

    /**
     * 根据国家名称猜测 ISO 3166-1 alpha-2 代码
     */
    protected static function guessCountryCode(?string $name): string
    {
        if (blank($name)) {
            return 'CN';
        }

        return match ($name) {
            '中国' => 'CN',
            '日本' => 'JP',
            '韩国' => 'KR',
            '美国' => 'US',
            '英国' => 'GB',
            '法国' => 'FR',
            '德国' => 'DE',
            '澳大利亚' => 'AU',
            '加拿大' => 'CA',
            '俄罗斯' => 'RU',
            '印度' => 'IN',
            '巴西' => 'BR',
            default => 'CN',
        };
    }

    /**
     * 根据公司名称查找 Company ID
     *
     * 支持按 name 或 code 模糊匹配
     */
    protected static function findCompanyByName(?string $name): ?int
    {
        if (blank($name)) {
            return null;
        }

        if (! isset(static::$companyCache[$name])) {
            $company = Company::where('name', $name)
                ->orWhere('code', $name)
                ->first();

            static::$companyCache[$name] = $company?->id;
        }

        return static::$companyCache[$name];
    }

    // ───────────────────── 省市区匹配 ─────────────────────

    /**
     * 加载省市区级联数据（缓存）
     */
    protected static function loadDistrictData(): array
    {
        if (static::$districtCascader !== null) {
            return static::$districtCascader;
        }

        $district = new District;
        $cascader = $district->getCascader();

        if (is_string($cascader)) {
            $cascader = json_decode($cascader, true) ?: [];
        }

        return static::$districtCascader = $cascader ?: [];
    }

    /**
     * 根据省份名称查找省份 ID
     */
    protected static function resolveProvinceId(?string $provinceName): ?int
    {
        if (blank($provinceName)) {
            return null;
        }

        $cascader = static::loadDistrictData();

        foreach ($cascader as $province) {
            if ($province['name'] === $provinceName
                || $province['short_name'] === $provinceName
                || str_contains($province['name'], $provinceName)
                || str_contains($provinceName, $province['short_name'])) {
                return (int) $province['id'];
            }
        }

        return null;
    }

    /**
     * 根据省份名称和市级名称查找城市 ID
     *
     * 处理直辖市特殊情况（市辖区虚拟节点）
     */
    protected static function resolveCityId(?string $provinceName, ?string $cityName): ?int
    {
        if (blank($cityName)) {
            return null;
        }

        $cascader = static::loadDistrictData();

        // 先找到对应的省
        $province = null;

        foreach ($cascader as $p) {
            if ($p['name'] === $provinceName
                || $p['short_name'] === $provinceName
                || str_contains($p['name'], $provinceName)
                || str_contains($provinceName, $p['short_name'])) {
                $province = $p;
                break;
            }
        }

        if (! $province) {
            return null;
        }

        // 在省的 children 中查找市
        foreach ($province['children'] ?? [] as $city) {
            if ($city['name'] === $cityName
                || $city['short_name'] === $cityName
                || str_contains($city['name'], $cityName)
                || str_contains($cityName, $city['short_name'])) {
                return (int) $city['id'];
            }
        }

        // 直辖市特殊情况：省名和市名相同时，使用虚拟"市辖区"节点的 ID
        if ($province['short_name'] === $cityName || $province['name'] === $cityName) {
            foreach ($province['children'] ?? [] as $city) {
                if (($city['short_name'] ?? '') === '市辖区') {
                    return (int) $city['id'];
                }
            }
        }

        return null;
    }

    // ───────────────────── 设置同步 ─────────────────────

    /**
     * 同步 AppraiseSettings：如果导入值不在设置选项中，自动追加
     */
    protected function syncAppraiseSettings(): void
    {
        $settingsFieldMap = [
            'germplasm_type' => 'germplasm_type',
            'germplasm_use' => 'germplasm_use',
            'fruit_use' => 'fruit_use',
            'plant_use' => 'plant_use',
            'assemble_resource' => 'assemble_resource',
            'assemble_material_type' => 'assemble_material_type',
        ];

        $settings = app(AppraiseSettings::class);
        $needSave = false;

        foreach ($settingsFieldMap as $recordField => $settingsField) {
            $value = $this->record->{$recordField} ?? null;

            if (blank($value)) {
                continue;
            }

            $currentOptions = $settings->{$settingsField};

            if (! in_array($value, $currentOptions)) {
                $currentOptions[] = $value;
                $settings->{$settingsField} = $currentOptions;
                $needSave = true;
            }
        }

        if ($needSave) {
            $settings->save();
        }
    }

    // ───────────────────── Importer 配置 ─────────────────────

    public function getJobConnection(): ?string
    {
        return 'sync';
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = "成功导入 {$import->successful_rows} 条评价记录。";

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= " {$failedRowsCount} 条记录导入失败。";
        }

        return $body;
    }
}
