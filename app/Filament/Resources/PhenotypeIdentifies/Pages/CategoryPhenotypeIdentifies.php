<?php

namespace App\Filament\Resources\PhenotypeIdentifies\Pages;

use App\Filament\Resources\Concerns\HasCategoryFields;
use App\Filament\Resources\PhenotypeIdentifies\PhenotypeIdentifyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Wsmallnews\Category\Support\Utils as CategoryUtils;

class CategoryPhenotypeIdentifies extends ListRecords
{
    use HasCategoryFields;

    protected static string $resource = PhenotypeIdentifyResource::class;

    #[Url]
    public ?int $categoryId = null;

    public function getTitle(): string
    {
        $category = $this->getCategory();

        return $category ? "表型鉴定 - {$category->name}" : '表型鉴定';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            PhenotypeIdentifyResource::getUrl('index') => '表型鉴定',
            '#' => $this->getTitle(),
        ];
    }

    public function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('category_id', $this->categoryId);
    }

    public function table(Table $table): Table
    {
        $table = parent::table($table);

        // 根据分类的自定义字段动态添加列
        $category = CategoryUtils::getCategoryModel()::find($this->categoryId);
        if ($category) {
            $dynamicColumns = [];
            $fields = $category->options['fields'] ?? [];
            foreach ($fields as $key => $field) {
                $groupName = $field['name'] ?? '';
                foreach ($field['fields'] as $subKey => $subField) {
                    $fieldKey = 'options.fields.' . $key . '.fields.' . $subKey . '.data.value';
                    $subFieldType = $subField['type'] ?? null;

                    if ($subFieldType === 'upload_image') {
                        $column = static::getTableColumnOnlyMedia($fieldKey, $subField, $groupName);
                    } else {
                        $column = static::getTableColumnWithoutMedia($fieldKey, $subField, $groupName);
                    }

                    if ($column) {
                        $dynamicColumns[] = $column;
                    }
                }
            }

            if (! empty($dynamicColumns)) {
                $table->pushColumns($dynamicColumns);
            }
        }

        return $table;
    }

    protected function getCategory()
    {
        return CategoryUtils::getCategoryModel()::scopeable('appraise', 0)->where('team_id', current_tenant()?->id)->find($this->categoryId);
    }
}
