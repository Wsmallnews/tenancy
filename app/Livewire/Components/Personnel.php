<?php

namespace App\Livewire\Components;

use App\Models\Personnel as PersonnelModel;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Personnel extends Component
{
    public int $id;

    public function render()
    {
        $personnel = PersonnelModel::query()->scopeTenant()->normal()->with(['media', 'content'])->findOrFail($this->id);

        Model::withoutTimestamps(fn() => $personnel->increment('views'));        // 增加浏览量,不更新 updated_at

        return view('livewire.components.personnel', [
            'personnel' => $personnel
        ]);
    }
}
