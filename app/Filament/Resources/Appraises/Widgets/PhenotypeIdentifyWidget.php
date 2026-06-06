<?php

namespace App\Filament\Resources\Appraises\Widgets;

use App\Filament\Resources\Concerns\HasCategoryFields;
use App\Models\Appraise;
use App\Models\PhenotypeIdentify;
use Filament\Widgets\Widget;
use Wsmallnews\Category\Support\Utils as CategoryUtils;

class PhenotypeIdentifyWidget extends Widget
{
    use HasCategoryFields;

    protected string $view = 'filament.resources.appraises.widgets.phenotype-identify-widget';

    protected int|string|array $columnSpan = 'full';

    public ?Appraise $record = null;

    /**
     * 获取该评价的表型鉴定记录及对应的分类字段定义
     */
    public function getWidgetData(): array
    {
        if (! $this->record) {
            return ['records' => collect(), 'categoryFields' => []];
        }

        $records = PhenotypeIdentify::query()
            ->where('appraise_id', $this->record->id)
            ->normal()
            ->orderBy('order_column', 'asc')
            ->get();

        $categoryFields = [];
        if ($this->record->category_id) {
            $category = CategoryUtils::getCategoryModel()::find($this->record->category_id);
            if ($category) {
                $categoryFields = $category->options['fields'] ?? [];
            }
        }

        return [
            'records' => $records,
            'categoryFields' => $categoryFields,
        ];
    }
}
