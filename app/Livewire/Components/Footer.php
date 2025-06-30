<?php

namespace App\Livewire\Components;

use App\Features\Tree;
use App\Models\Navigation as NavigationModel;
use App\Settings\GeneralSettings;
use Livewire\Component;

class Footer extends Component
{

    // public $navigations;

    public function mount() {}



    public function boot()
    {
        // $this->navigations = (new Tree(function () {
        //     return NavigationModel::query()->normal()->orderBy('order_column', 'asc')->orderBy('id', 'asc');
        // }))->getTree(items: $tops, resultCb: function ($navigations) {
        //     return $navigations->map(function ($navigation) {
        //         return $navigation->resolveNavigation($navigation);
        //     });
        // });
    }



    public function render()
    {
        return view('livewire.components.footer', [
            // 'general' => app(GeneralSettings::class)
        ]);
    }
}
