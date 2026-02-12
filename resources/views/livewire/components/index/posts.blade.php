@php
    use Filament\Support\Icons\Heroicon;
@endphp

<section class="py-10">
    <div class="flex flex-col items-center justify-center mb-12">
        <h1 class="sn-h1-text shrink-0 relative mb-4">
            动态资讯
            <span class="sn-primary-bg absolute bottom-0 left-0 w-full h-1 rounded-full transform translate-y-2"></span>
        </h1>
        <p class="sn-content-text max-w-3xl mx-auto">
            种质资源库启动，为农业发展注入核心“芯片”，守护生物多样性
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($posts as $post)
            <article class="sn-container sn-hover sn-link overflow-hidden group">
                <div class="relative h-48 overflow-hidden">
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
                    
                    @if($post->categories->count() > 0)
                        <div class="absolute top-4 left-4">
                            <span class="sn-primary-bg px-3 py-1 text-white text-xs font-bold rounded-full">
                                {{ $post->categories->first()->name }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="p-6">
                    <div class="sn-tip-text flex items-center gap-4 mb-3">
                        <div class="flex items-center gap-1">
                            <x-filament::icon :icon="Heroicon::OutlinedCalendarDays" class="w-4 h-4" />
                            {{ $post->published_at ? $post->published_at->format('Y-m-d') : $post->created_at->format('Y-m-d') }}
                        </div>
                        @if($post->categories->count() > 0)
                            <div class="flex items-center gap-1">
                                <x-filament::icon :icon="Heroicon::OutlinedTag" class="w-4 h-4" />
                                {{ $post->categories->first()->name }}
                            </div>
                        @endif
                    </div>

                    <h3 class="sn-h3-text sn-hover mb-3 line-clamp-2">
                        {{ $post->title }}
                    </h3>

                    <p class="sn-descript-text line-clamp-3 mb-4">
                        {{ $post->description ?? '暂无描述' }}
                    </p>
                    
                    <x-sn-cms::container.block-link href="{{ \Wsmallnews\Cms\Support\Utils::route('posts.show', $post->id) }}" class="sn-primary-text inline-flex items-center text-sm font-medium">
                        阅读全文 
                        <x-filament::icon :icon="Heroicon::OutlinedChevronRight" class="w-4 h-4 ml-1" />
                    </x-sn-cms::container.block-link>
                </div>
            </article>
        @endforeach
    </div>
    
    <div class="mt-8 text-center">
        <a href="{{ \Wsmallnews\Cms\Support\Utils::route('posts') }}" class="inline-flex items-center text-primary-500 font-medium">
            查看更多 &rarr;
        </a>
    </div>
</section>