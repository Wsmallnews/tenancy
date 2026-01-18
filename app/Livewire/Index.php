<?php

namespace App\Livewire;

use App\Models\Block;
use App\Models\Project;
use Livewire\Attributes\Title;
use Wsmallnews\Cms\Support\Utils as CmsUtils;

class Index extends Base
{

    #[Title('首页')]
    public function render()
    {
        // $indexBlocks = Block::query()->normal()->with(['media'])->orderBy('order_column', 'asc')->get();

        return view('livewire.index', [
            // 'projects' => $projects,
            // 'indexBlocks' => $indexBlocks
        ])->layout(CmsUtils::getLayout());
    }
}
