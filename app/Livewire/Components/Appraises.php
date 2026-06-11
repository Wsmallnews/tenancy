<?php

namespace App\Livewire\Components;

use App\Models\Appraise as AppraiseModel;
use App\Settings\AppraiseSettings;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Wsmallnews\Category\Livewire\Concerns\Categoryable;
use Wsmallnews\Support\Livewire\Concerns\CanPagination;

class Appraises extends Component implements HasActions, HasSchemas
{
    use CanPagination;
    use Categoryable;
    use Concerns\ApplyAction;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use WithoutUrlPagination;

    #[Url(except: '')]
    public string $search = '';

    public int|string|array $categoryIds = [];

    public Collection $appraises;

    public string $style = 'card';      // list=列表，card=卡片

    // 筛选条件
    #[Url(except: '')]
    public string $filter_germplasm_type = '';

    #[Url(except: '')]
    public string $filter_germplasm_use = '';

    #[Url(except: '')]
    public string $filter_fruit_use = '';

    #[Url(except: '')]
    public string $filter_plant_use = '';

    #[Url(except: '')]
    public string $filter_assemble_resource = '';

    #[Url(except: '')]
    public string $filter_assemble_material_type = '';

    #[Locked]
    public array $germplasmTypeOptions = [];

    #[Locked]
    public array $germplasmUseOptions = [];

    #[Locked]
    public array $fruitUseOptions = [];

    #[Locked]
    public array $plantUseOptions = [];

    #[Locked]
    public array $assembleResourceOptions = [];

    #[Locked]
    public array $assembleMaterialTypeOptions = [];

    public function mount()
    {
        $this->appraises = $this->appraises ?? collect([]);

        $settings = app(AppraiseSettings::class);

        $this->germplasmTypeOptions = $this->buildOptions($settings->germplasm_type);
        $this->germplasmUseOptions = $this->buildOptions($settings->germplasm_use);
        $this->fruitUseOptions = $this->buildOptions($settings->fruit_use);
        $this->plantUseOptions = $this->buildOptions($settings->plant_use);
        $this->assembleResourceOptions = $this->buildOptions($settings->assemble_resource);
        $this->assembleMaterialTypeOptions = $this->buildOptions($settings->assemble_material_type);
    }

    public function getScopeable(): array
    {
        return ['scope_type' => 'appraise', 'scope_id' => 0];
    }

    // 监听搜索变化
    public function updatedSearch()
    {
        $this->resetPage(); // 搜索时重置页码

        if (in_array($this->pageType, ['scroll', 'manual'])) {
            // 滚动分页或手动分页时，清空列表
            $this->appraises = collect([]);
        }
    }

    // 监听筛选变化
    public function updated($property)
    {
        if (str_starts_with($property, 'filter_')) {
            $this->resetPage();

            if (in_array($this->pageType, ['scroll', 'manual'])) {
                $this->appraises = collect([]);
            }
        }
    }

    // 获取生效的筛选条件（用于 UI 标签展示）
    public function getActiveFilters(): array
    {
        $filters = [];

        if ($this->search) {
            $filters[] = [
                'type' => 'search',
                'label' => '🔍 ' . $this->search,
                'key' => 'search',
            ];
        }

        $map = [
            'filter_germplasm_type' => '种质类型',
            'filter_germplasm_use' => '用途',
            'filter_fruit_use' => '果实用途',
            'filter_plant_use' => '植株用途',
            'filter_assemble_resource' => '种植收集源',
            'filter_assemble_material_type' => '收集材料类型',
        ];

        foreach ($map as $prop => $label) {
            if ($this->{$prop}) {
                $filters[] = [
                    'type' => 'filter',
                    'label' => "{$label}: {$this->{$prop}}",
                    'key' => $prop,
                ];
            }
        }

        return $filters;
    }

    // 重置筛选条件
    public function resetFilter(string $key): void
    {
        $this->{$key} = '';
    }

    // 重置全部筛选
    public function resetAllFilters(): void
    {
        $this->search = '';
        $this->filter_germplasm_type = '';
        $this->filter_germplasm_use = '';
        $this->filter_fruit_use = '';
        $this->filter_plant_use = '';
        $this->filter_assemble_resource = '';
        $this->filter_assemble_material_type = '';
        $this->resetPage();
    }

    protected function getCurrents()
    {
        return $this->appraises;
    }

    public function render()
    {
        $categoryIds = $this->categoryIds ? Arr::wrap($this->categoryIds) : [];

        $allCategories = collect([]);       // 要查询的分类，以及分类的所有子节点
        foreach ($categoryIds as $id) {
            // 查询分类以及分类的所有子节点
            $currentIds = $this->getScopedQuery()->normal()->descendantsAndSelf($id)
                ->pluck('id');

            $allCategories = $allCategories->merge($currentIds);
        }
        $allCategories = $allCategories->filter()->unique()->values();

        // 查询评价
        $query = AppraiseModel::query()->scopeTenant()->normal()->with(['saveCompany', 'media'])
            ->when($allCategories->isNotEmpty(), function ($query) use ($allCategories) {
                $query->whereIn('category_id', $allCategories);
            })
            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->when($this->filter_germplasm_type, function ($query) {
                $query->where('germplasm_type', $this->filter_germplasm_type);
            })
            ->when($this->filter_germplasm_use, function ($query) {
                $query->where('germplasm_use', $this->filter_germplasm_use);
            })
            ->when($this->filter_fruit_use, function ($query) {
                $query->where('fruit_use', $this->filter_fruit_use);
            })
            ->when($this->filter_plant_use, function ($query) {
                $query->where('plant_use', $this->filter_plant_use);
            })
            ->when($this->filter_assemble_resource, function ($query) {
                $query->where('assemble_resource', $this->filter_assemble_resource);
            })
            ->when($this->filter_assemble_material_type, function ($query) {
                $query->where('assemble_material_type', $this->filter_assemble_material_type);
            })
            ->orderBy('order_column', 'desc')->orderBy('id', 'desc');

        // 分页
        $this->appraises = $this->withPagination($query);

        return view('livewire.components.appraises', [
            'paginatorLink' => $this->links,
        ]);
    }

    private function buildOptions(array $items): array
    {
        return Arr::mapWithKeys($items, function ($item) {
            return [$item => $item];
        });
    }
}
