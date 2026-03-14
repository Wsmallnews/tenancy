@php
    use Filament\Support\Icons\Heroicon;
@endphp

<section class="py-10">
    <div class="flex flex-col items-center justify-center mb-12">
        <h1 class="sn-h1-text shrink-0 relative mb-4">
            科学研究
            <span class="sn-primary-bg absolute bottom-0 left-0 w-full h-1 rounded-full transform translate-y-2"></span>
        </h1>
        <p class="sn-content-text max-w-3xl mx-auto">
            聚焦果业前沿科技，破解产业发展瓶颈，推动农业现代化进程
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($posts as $post)
            <div class="sn-container sn-hover sn-link flex flex-col md:flex-row overflow-hidden group">
                <div class="md:w-2/5 h-48 md:h-42 overflow-hidden">
                    @if ($post->getFirstMediaUrl('post_image'))
                        <img 
                            src="{{ $post->getFirstMediaUrl('post_image') }}" 
                            alt="{{ $post->title }}" 
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        />
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <x-filament::icon :icon="Heroicon::OutlinedPhoto" class="w-12 h-12 text-gray-400" />
                        </div>
                    @endif
                </div>
                <div class="p-6 md:w-3/5 flex flex-col justify-center gap-3">
                    <h3 class="sn-h3-text sn-hover line-clamp-1">
                        {{ $post->title }}
                    </h3>
                    <p class="sn-descript-text line-clamp-2">
                        {{ $post->description ?? '暂无描述' }}
                    </p>
                    <x-sn-cms::container.block-link href="{{ \Wsmallnews\Cms\Support\Utils::route('posts.show', $post) }}" class="sn-primary-text text-sm uppercase tracking-wide">
                        了解详情 &rarr;
                    </x-sn-cms::container.block-link>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 text-center">
        <a href="{{ \Wsmallnews\Cms\Support\Utils::route('posts') }}" class="inline-flex items-center text-primary-500 font-medium">
            查看更多 &rarr;
        </a>
    </div>
</section>