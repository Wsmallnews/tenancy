<?php

namespace App\Livewire\Components\Index;

use App\Models\Personnel as PersonnelModel;
use Livewire\Component;

class Personnels extends Component
{

    public function render()
    {
        // 查询人员
        $personnels = PersonnelModel::query()->scopeTenant()->normal()->with(['media'])->orderBy('order_column', 'desc')->limit(10)->get();

        return view('livewire.components.index.personnels', [
            'personnels' => $personnels,
        ]);
    }
}
