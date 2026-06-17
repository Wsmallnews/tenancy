<?php

namespace App\Livewire\Components;

use App\Models\Personnel as PersonnelModel;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Wsmallnews\Support\Livewire\Concerns\CanPagination;

class Personnels extends Component
{
    use CanPagination;
    use WithoutUrlPagination;

    #[Url(except: '')]
    public string $search = '';

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
        $query = PersonnelModel::query()->scopeTenant()->normal()->display()->with(['media'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy('order_column', 'desc');

        // 分页
        $this->personnels = $this->withPagination($query, $this->getFingerprint());

        return view('livewire.components.personnels', [
            'paginatorLink' => $this->links,
        ]);
    }

    protected function getFingerprint(): string
    {
        return md5(serialize([
            'search' => $this->search,
        ]));
    }
}
