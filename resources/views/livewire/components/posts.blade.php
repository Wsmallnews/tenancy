<x-paginators.container :page-type="$pageType" :page-info="$pageInfo" :paginator-link="$paginatorLink" :page-name="$pageName">
    <div class="w-full flex flex-col gap-4">
        @foreach ($posts as $post)
            <a class="flex flex-row gap-4 overflow-hidden group"
                {{ \Filament\Support\generate_href_html(sn_route('posts.show', $post->id)) }}
            >
                @if ($post->getFirstMediaUrl('main'))
                <div class="w-44 h-44 flex-shrink-0 rounded-md overflow-hidden">
                    <img class="w-full h-full object-cover transition duration-300 group-hover:scale-105" src="{{ $post->getFirstMediaUrl('main') }}" />
                </div>
                @endif

                <div class="flex flex-col flex-grow py-4 pr-4 gap-4">
                    <div class="text-xl font-bold line-clamp-1 transition duration-300 group-hover:text-primary-500">
                        {{ $post->title }}
                    </div>

                    <div class="flex-grow max-h-14 text-gray-500 leading-7 line-clamp-2">
                        {{ $post->description }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $post->updated_at->format('Y-m-d') }}
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</x-paginators.container>