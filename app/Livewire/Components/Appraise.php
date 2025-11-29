<?php

namespace App\Livewire\Components;

use App\Models\Appraise as AppraiseModel;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Appraise extends Component
{
    public int $id;

    public string $wrapperView = 'base.block';

    public function render()
    {
        $appraise = AppraiseModel::query()->scopeTenant()->normal()->with(['media', 'content'])->findOrFail($this->id);
        
        // Model::withoutTimestamps(fn() => $appraise->increment('views'));        // 增加浏览量,不更新 updated_at

        return view('livewire.components.appraise', [
            'appraise' => $appraise
        ]);
    }
}
