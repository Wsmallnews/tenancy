<?php

namespace App\Livewire\Components;

use App\Models\Navigation as NavigationModel;
use App\Settings\GeneralSettings;
use Livewire\Component;

class Footer extends Component
{

    public function render()
    {
        $navigations = NavigationModel::scoped(has_tenancy() ? ['team_id' => current_tenant()->id] : [])
            ->normal()
            ->where(function ($query) {
                $query->where('options->footer_show', true)
                    ->orWhereNull('options->footer_show');
            })
            ->defaultOrder()->get()
            ->map(function (NavigationModel $navigation) {
                return $navigation->resolveNavigation($navigation);
            })->toTree();

        return view('livewire.components.footer', [
            'navigations' => $navigations,
            // 'general' => app(GeneralSettings::class)
        ]);
    }
}
