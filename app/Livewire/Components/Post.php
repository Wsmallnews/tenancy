<?php

namespace App\Livewire\Components;

use App\Models\Post as PostModel;
use Livewire\Component;

class Post extends Component
{
    public PostModel $post;

    public function mount($id)
    {
        $this->post = PostModel::query()->scopeTenant()->normal()->with(['media', 'content'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.components.post');
    }
}
