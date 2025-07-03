<?php

namespace App\Livewire\Components;

use App\Enums\Navigations\Type as NavigationTypeEnum;
use App\Models\Navigation as NavigationModel;
use Livewire\Component;

class Navigation extends Component
{

    public function getNavigations()
    {
        return NavigationModel::scoped(has_tenancy() ? ['team_id' => current_tenant()->id] : [])
            ->normal()->defaultOrder()->get()
            ->map(function (NavigationModel $navigation) {
                return $navigation->resolveNavigation($navigation);
            })->toTree();
    }


    public function render()
    {
        return view('livewire.components.navigation');
    }
}
