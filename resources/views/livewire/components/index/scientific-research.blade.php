@php
    use Filament\Support\Icons\Heroicon;
@endphp

<section class="py-10">
    <div class="flex flex-col items-center justify-center mb-12">
        <h2 class="shrink-0 text-3xl md:text-4xl font-bold text-slate-900 relative mb-4">
            科学研究
            <span class="absolute bottom-0 left-0 w-full h-1 bg-primary-500 rounded-full transform translate-y-2"></span>
        </h2>
        <p class="text-lg text-slate-600 max-w-3xl mx-auto">
            聚焦果业前沿科技，破解产业发展瓶颈，推动农业现代化进程
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($posts as $post)
            <div class="sn-container flex flex-col md:flex-row overflow-hidden group">
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
                <div class="p-6 md:w-3/5 flex flex-col justify-center">
                    <h3 class="text-xl font-bold text-slate-900 line-clamp-1 transition-colors mb-3">
                        {{ $post->title }}
                    </h3>
                    <p class="text-slate-500 text-sm mb-4 leading-relaxed line-clamp-2">
                        {{ $post->description ?? '暂无描述' }}
                    </p>
                    <x-sn-cms::container.block-link href="{{ \Wsmallnews\Cms\Support\Utils::route('posts.show', $post->id) }}" class="text-sm font-semibold text-primary-400 hover:text-primary-500 uppercase tracking-wide">
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