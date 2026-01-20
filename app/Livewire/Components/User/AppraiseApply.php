<?php

namespace App\Livewire\Components\User;

use App\Models\AppraiseApply as AppraiseApplyModel;
use App\Filament\Resources\AppraiseApplies\Schemas\AppraiseApplyInfolist;
use Filament\Schemas\Schema;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Http\Request;
use Wsmallnews\Cms\Support\Utils as CmsUtils;
use Wsmallnews\Cms\Livewire\Components\Base;

class AppraiseApply extends Base implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public int $id;

    public AppraiseApplyModel $appraiseApply;

    public function mount(Request $request)
    {
        $user = $request->user();
        $this->appraiseApply = AppraiseApplyModel::query()->scopeTenant()->with(['appraise', 'team'])->where('user_id', $user->id)->findOrFail($this->id);
    }

    public function appraiseApplyInfolist(Schema $schema): Schema
    {
        return AppraiseApplyInfolist::configure($schema)->record($this->appraiseApply);
    }

    public function render()
    {
        return view('livewire.components.user.appraise-apply')->layout(CmsUtils::getLayout());
    }
}