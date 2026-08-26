@php
    use Filament\Support\Icons\Heroicon;
    use Wsmallnews\Cms\Support\Utils;

    // 分类 pill 颜色（设计稿四色：主题蓝 + emerald/amber/rose），按分类 id 稳定取色
    $pillPalette = [
        'bg-primary-600 dark:bg-primary-500',
        'bg-emerald-500 dark:bg-emerald-600',
        'bg-amber-500 dark:bg-amber-600',
        'bg-rose-500 dark:bg-rose-600',
    ];
@endphp

<section class="w-full pt-6" aria-labelledby="posts-heading">
    <x-index.section-header
        headingId="posts-heading"
        title="{{ __('服务案例') }}"
        description="{{ __('联合创新，共促农业科技成果转化应用') }}"
        :href="Utils::route('posts')"
    />

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        @foreach ($posts as $post)
            @php
                $category = $post->categories->first();
                $pillColor = $category ? $pillPalette[$category->id % count($pillPalette)] : $pillPalette[0];
            @endphp

            <article class="bg-white dark:bg-gray-900 rounded-xl shadow-md overflow-hidden group">
                <x-sn-cms::container.block-link
                    href="{{ Utils::route('posts.show', $post) }}"
                    class="sn-link flex flex-col h-full rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900"
                >
                    <div class="relative w-full aspect-[290/176] overflow-hidden shrink-0">
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

                        @if ($category)
                            <span class="absolute left-3 top-3 inline-flex items-center h-6 px-3 rounded-full text-xs font-medium text-white shadow-sm {{ $pillColor }}">
                                {{ $category->name }}
                            </span>
                        @endif
                    </div>

                    <div class="p-5 flex flex-col gap-2 grow">
                        <h3 class="text-sm font-bold leading-6 text-gray-900 dark:text-gray-100 truncate">
                            {{ $post->title }}
                        </h3>

                        <p class="text-xs leading-5 text-gray-500 dark:text-gray-400 line-clamp-2">
                            {{ $post->description ?? __('暂无描述') }}
                        </p>

                        <time class="mt-auto pt-2 text-xs text-gray-400 dark:text-gray-500 tabular-nums"
                            datetime="{{ ($post->published_at ?? $post->created_at)->toIso8601String() }}">
                            {{ ($post->published_at ?? $post->created_at)->format('Y-m-d') }}
                        </time>
                    </div>
                </x-sn-cms::container.block-link>
            </article>
        @endforeach
    </div>
</section>
