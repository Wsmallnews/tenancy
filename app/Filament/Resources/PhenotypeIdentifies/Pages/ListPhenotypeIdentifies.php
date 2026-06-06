<?php

namespace App\Filament\Resources\PhenotypeIdentifies\Pages;

use App\Filament\Resources\PhenotypeIdentifies\PhenotypeIdentifyResource;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Locked;
use Wsmallnews\Category\Livewire\Concerns\Categoryable;

class ListPhenotypeIdentifies extends Page
{
    use Categoryable;

    #[Locked]
    public string $scopeType = 'appraise';

    #[Locked]
    public int $scopeId = 0;

    protected static string $resource = PhenotypeIdentifyResource::class;

    protected string $view = 'filament.resources.phenotype-identifies.list';

    protected static ?string $title = '表型鉴定';

    protected static ?string $navigationLabel = '表型鉴定';

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getScopeable(): array
    {
        return ['scope_type' => $this->scopeType, 'scope_id' => $this->scopeId];
    }

    public function getCategories()
    {
        $categories = $this->getScopedQuery()->normal()
            ->defaultOrder()
            ->get()->toTree();

        return $categories;
    }
}
