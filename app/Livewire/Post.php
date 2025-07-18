<?php

namespace App\Livewire;

use App\Models\Post as PostModel;
use Livewire\Attributes\Url;

class Post extends Base
{

    public int $id;
    
    public function render()
    {
        return view('livewire.post');
    }
}