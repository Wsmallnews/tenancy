@php
    use Filament\Support\Icons\Heroicon;
@endphp

<section class="py-12 md:py-16" aria-labelledby="posts-heading">
    <header class="flex flex-col items-center justify-center text-center mb-12">
        <h2 id="posts-heading" class="sn-section-title sn-h1-text mb-6">动态资讯</h2>
        <p class="sn-content-text max-w-3xl mx-auto">
            种质资源库启动，为农业发展注入核心"芯片"，守护生物多样性
        </p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        @foreach($posts as $post)
            <article class="sn-container sn-hover overflow-hidden group">
                <x-sn-cms::container.block-link
                    href="{{ \Wsmallnews\Cms\Support\Utils::route('posts.show', $post) }}"
                    class="sn-link block focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 rounded-md"
                >
                    <div class="relative h-48 overflow-hidden">
                        @if ($post->getFirstMediaUrl('post_image'))
                            <img
                                src="{{ $post->getFirstMediaUrl('post_image') }}"
                                alt="{{ $post->title }}"
                                loading="lazy"
                                class="w-full h-full object-cover sn-motion-scale"
                            />
                        @else
                            <div class="sn-image-placeholder">
                                <x-filament::icon :icon="Heroicon::OutlinedPhoto" class="w-12 h-12" aria-hidden="true" />
                            </div>
                        @endif

                        @if($post->categories->count() > 0)
                            <div class="absolute top-4 left-4">
                                <span class="bg-primary-500 dark:bg-primary-600 px-3 py-1 text-white text-xs font-bold rounded-full shadow-sm">
                                    {{ $post->categories->first()->name }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="sn-tip-text flex items-center gap-4 mb-3">
                            <div class="flex items-center gap-1">
                                <x-filament::icon :icon="Heroicon::OutlinedCalendarDays" class="w-4 h-4" aria-hidden="true" />
                                <time datetime="{{ ($post->published_at ?? $post->created_at)->toIso8601String() }}">
                                    {{ $post->published_at ? $post->published_at->format('Y-m-d') : $post->created_at->format('Y-m-d') }}
                                </time>
                            </div>
                            @if($post->categories->count() > 0)
                                <div class="flex items-center gap-1">
                                    <x-filament::icon :icon="Heroicon::OutlinedTag" class="w-4 h-4" aria-hidden="true" />
                                    {{ $post->categories->first()->name }}
                                </div>
                            @endif
                        </div>

                        <h3 class="sn-h3-text sn-hover mb-3 line-clamp-2">
                            {{ $post->title }}
                        </h3>

                        <p class="sn-descript-text line-clamp-3 mb-4">
                            {{ $post->description ?? __('暂无描述') }}
                        </p>

                        <span class="sn-link-more">
                            {{ __('阅读全文') }}
                            <x-filament::icon :icon="Heroicon::OutlinedChevronRight" class="w-4 h-4" aria-hidden="true" />
                        </span>
                    </div>
                </x-sn-cms::container.block-link>
            </article>
        @endforeach
    </div>

    <div class="mt-10 text-center">
        <x-sn-cms::container.block-link href="{{ \Wsmallnews\Cms\Support\Utils::route('posts') }}" class="sn-link-more">
            {{ __('查看更多') }}
            <x-filament::icon :icon="Heroicon::OutlinedArrowRight" class="w-4 h-4" aria-hidden="true" />
        </x-sn-cms::container.block-link>
    </div>
</section>
