<?php

namespace App\Livewire\Components;

use App\Filament\Resources\Appraises\Schemas\AppraiseInfolist;
use App\Models\Appraise as AppraiseModel;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Component;

class Appraise extends Component implements HasActions, HasSchemas
{
    use Concerns\ApplyAction;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public int $id;

    public AppraiseModel $appraise;

    public function mount()
    {
        $this->appraise = AppraiseModel::query()->scopeTenant()->normal()->with(['media'])->findOrFail($this->id);
    }

    public function appraiseInfolist(Schema $schema): Schema
    {
        return AppraiseInfolist::configure($schema, false)->record($this->appraise);
    }

    public function render()
    {
        return view('livewire.components.appraise');
    }
}
