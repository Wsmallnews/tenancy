<?php

namespace App\Livewire\Components;

use App\Models\Post as PostModel;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Post extends Component
{
    public int $id;

    public function render()
    {
        $post = PostModel::query()->scopeTenant()->normal()->with(['media', 'content'])->findOrFail($this->id);

        Model::withoutTimestamps(fn() => $post->increment('views'));        // 增加浏览量,不更新 updated_at

        return view('livewire.components.post', [
            'post' => $post
        ]);
    }
}
