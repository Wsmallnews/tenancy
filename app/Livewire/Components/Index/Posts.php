<?php

namespace App\Livewire\Components\Index;

use Wsmallnews\Cms\Livewire\Components\Base;
use Wsmallnews\Cms\Support\Utils;

class Posts extends Base
{
    public int $limit = 6;

    public function render()
    {
        $posts = Utils::getPostModel()::snScope(...$this->getScopeable())->normal()
            ->with(['media', 'categories'])
            ->orderBy('order_column', 'desc')
            ->orderBy('id', 'desc')
            ->limit($this->limit)
            ->get();

        return view('livewire.components.index.posts', [
            'posts' => $posts,
        ]);
    }
}
