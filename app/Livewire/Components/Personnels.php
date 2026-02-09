<?php

namespace App\Livewire\Components;

use App\Models\Personnel as PersonnelModel;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Wsmallnews\Support\Livewire\Concerns\CanPagination;

class Personnels extends Component
{
    use CanPagination;
    use WithoutUrlPagination;

    public Collection $personnels;

    public function mount()
    {
        $this->personnels = $this->personnels ?? collect([]);
    }

    protected function getCurrents()
    {
        return $this->personnels;
    }


    public function render()
    {
        // 查询人员
        $query = PersonnelModel::query()->scopeTenant()->normal()->with(['media'])->orderBy('order_column', 'desc');

        // 分页
        $this->personnels = $this->withPagination($query);

        return view('livewire.components.personnels', [
            'paginatorLink' => $this->links
        ]);
    }
}
