<?php

namespace App\Models\Cms;

use Wsmallnews\Cms\Enums\PostStatus;
use Wsmallnews\Cms\Models\Post as CmsPost;

class Post extends CmsPost
{
    protected $casts = [
        'options' => 'array',
        'status' => PostStatus::class,
        'published_at' => 'datetime',
    ];
}
