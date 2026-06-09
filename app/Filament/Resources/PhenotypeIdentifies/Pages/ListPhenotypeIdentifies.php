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

class ListPhenotypeIdentifies extends ListRecords
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


    protected function getCategory()
    {
        return CategoryUtils::getCategoryModel()::scopeable('appraise', 0)->where('team_id', current_tenant()?->id)->find($this->categoryId);
    }
}
