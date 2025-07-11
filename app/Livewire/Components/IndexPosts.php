<?php

namespace App\Livewire\Components;

use App\Models\Post;
use Illuminate\Support\Collection;
use Livewire\Component;

class IndexPosts extends Component
{
    public int $limit = 10;

    public function render()
    {
        $posts = Post::query()->normal()->limit($this->limit)->get();

        return view('livewire.components.index-posts', [
            'posts' => $posts,
        ]);
    }
}
