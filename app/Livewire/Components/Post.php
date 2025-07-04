<?php

namespace App\Livewire\Components;

use App\Models\Post as PostModel;
use Livewire\Component;

class Post extends Component
{
    public PostModel $post;


    public function render()
    {
        return view('livewire.components.post');
    }
}
