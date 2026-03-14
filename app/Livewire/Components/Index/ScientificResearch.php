<?php

namespace App\Livewire\Components\Index;

use Wsmallnews\Cms\Livewire\Components\Base;
use Wsmallnews\Cms\Support\Utils;

class ScientificResearch extends Base
{
    public int $limit = 6;

    public function render()
    {
        $posts = Utils::getPostModel()::snScope(...$this->getScopeable())->published()
            ->with(['media', 'categories'])
            ->orderBy('order_column', 'desc')
            ->orderBy('id', 'desc')
            ->limit($this->limit)
            ->get();

        return view('livewire.components.index.scientific-research', [
            'posts' => $posts,
        ]);
    }
}
