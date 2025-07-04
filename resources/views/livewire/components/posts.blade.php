<x-paginators.container :page-type="$pageType" :page-info="$pageInfo" :paginator-link="$paginatorLink" :page-name="$pageName">
    <div class="w-full flex flex-col gap-4">
        @foreach ($posts as $post)
            <a class="flex flex-col md:flex-row gap-4"
                {{ \Filament\Support\generate_href_html(sn_route('posts.show', $post->id), '_self') }}
            >
                @if ($post->getFirstMediaUrl('main'))
                    <img class="" src="{{ $post->getFirstMediaUrl('main') }}" />
                @endif

                <div class="flex flex-col gap-4">
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
            </a>
        @endforeach
    </div>
</x-paginators.container>