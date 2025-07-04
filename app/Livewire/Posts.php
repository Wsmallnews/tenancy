<?php

namespace App\Livewire;

use Livewire\Attributes\Url;

class Posts extends Base
{
    #[Url]
    public int $category_id;

    public function mount()
    {
        $this->category_id = request()->get('category_id', 0);
    }


    public function render()
    {
        return view('livewire.posts', []);
    }
}