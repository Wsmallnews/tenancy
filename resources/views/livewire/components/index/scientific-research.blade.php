@php
    use Filament\Support\Icons\Heroicon;
@endphp

<section class="py-12 md:py-16" aria-labelledby="research-heading">
    <header class="flex flex-col items-center justify-center text-center mb-12">
        <h2 id="research-heading" class="sn-section-title sn-h1-text mb-6">科学研究</h2>
        <p class="sn-content-text max-w-3xl mx-auto">
            聚焦果业前沿科技，破解产业发展瓶颈，推动农业现代化进程
        </p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
        @foreach($posts as $post)
            <article class="sn-container sn-hover overflow-hidden group">
                <x-sn-cms::container.block-link
                    href="{{ \Wsmallnews\Cms\Support\Utils::route('posts.show', $post) }}"
                    class="sn-link flex flex-row focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 rounded-md"
                >
                    <div class="w-1/3 md:w-2/5 h-42 md:h-44 overflow-hidden shrink-0">
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
                    </div>
                    <div class="p-6 md:w-3/5 flex flex-col justify-center gap-3">
                        <h3 class="sn-h3-text sn-hover line-clamp-1">
                            {{ $post->title }}
                        </h3>
                        <p class="sn-descript-text line-clamp-2">
                            {{ $post->description ?? __('暂无描述') }}
                        </p>
                        <span class="sn-link-more">
                            {{ __('了解详情') }}
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
