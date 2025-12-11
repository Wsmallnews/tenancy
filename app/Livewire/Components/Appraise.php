<?php

namespace App\Livewire\Components;

use App\Filament\Resources\Appraises\Schemas\AppraiseInfolist;
use App\Models\Appraise as AppraiseModel;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Appraise extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public int $id;

    public AppraiseModel $appraise;

    public function mount()
    {
        $this->appraise = AppraiseModel::query()->scopeTenant()->normal()->with(['media'])->findOrFail($this->id);
    }


    public function appraiseInfolist(Schema $schema): Schema
    {
        return AppraiseInfolist::configure($schema)->record($this->appraise);
    }


    public function render()
    {
        return view('livewire.components.appraise');
    }
}
