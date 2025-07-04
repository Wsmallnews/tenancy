<div class="w-full flex flex-col gap-4">
    <div class="">
        {{ $post->title }}
    </div>

    <div class="">
        {{ $post->description }}
    </div>
    <div class="">
        {{ $post->updated_at->format('Y-m-d') }}
    </div>
</div>