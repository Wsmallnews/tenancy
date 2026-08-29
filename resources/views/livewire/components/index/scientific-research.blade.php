@php
    use Filament\Support\Icons\Heroicon;
    use Wsmallnews\Cms\Support\Utils;

    // 类型标签颜色（设计稿：研究论文=emerald、技术报告=主题蓝、其余 amber/rose），按分类 id 稳定取色
    $tagPalette = [
        'bg-primary-500/10 dark:bg-primary-400/10 text-primary-600 dark:text-primary-400',
        'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400',
        'bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400',
        'bg-rose-50 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400',
    ];
@endphp

<section class="w-full pt-6" aria-labelledby="research-heading">
    <x-index.section-header
        headingId="research-heading"
        title="{{ __('科研成果') }}"
        description="{{ __('聚焦前沿研究，推动农业科技创新发展') }}"
        :href="Utils::route('posts')"
    />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($posts as $post)
            @php
                $category = $post->categories->first();
                $tagColor = $category ? $tagPalette[$category->id % count($tagPalette)] : $tagPalette[0];
            @endphp

            {{-- 卡片高度写死；图片区宽度由 满高 + aspect-[290/176]（与服务案例同比例）推算，贴上下左三边、右侧无圆角，cover 居中裁剪 --}}
            <article class="group h-28 bg-white dark:bg-gray-900 rounded-xl shadow-sm overflow-hidden flex">
                <x-sn-cms::container.block-link
                    href="{{ Utils::route('posts.show', $post) }}"
                    class="sn-link flex w-full h-full focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 rounded-xl"
                >
                    <div class="h-full aspect-[290/176] shrink-0 overflow-hidden bg-gray-100 dark:bg-gray-800">
                        @if ($post->getFirstMediaUrl('post_image'))
                            <img
                                src="{{ $post->getFirstMediaUrl('post_image') }}"
                                alt="{{ $post->title }}"
                                loading="lazy"
                                class="w-full h-full object-cover sn-motion-scale"
                            />
                        @else
                            <div class="sn-image-placeholder h-full">
                                <x-filament::icon :icon="Heroicon::OutlinedPhoto" class="w-8 h-8" aria-hidden="true" />
                            </div>
                        @endif
                    </div>

                    <div class="py-3 pl-4 pr-5 flex flex-col gap-1.5 min-w-0 grow">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold leading-5 text-gray-900 dark:text-gray-100 truncate">
                                {{ $post->title }}
                            </h3>

                            @if ($category)
                                <span class="shrink-0 px-1.5 py-0.5 rounded text-[10px] font-medium leading-4 {{ $tagColor }}">
                                    {{ $category->name }}
                                </span>
                            @endif
                        </div>

                        <p class="text-xs leading-5 text-gray-500 dark:text-gray-400 line-clamp-1">
                            {{ $post->description ?? __('暂无描述') }}
                        </p>

                        <time class="mt-auto flex items-center gap-1.5 text-xs leading-4 text-gray-400 dark:text-gray-500 tabular-nums"
                            datetime="{{ ($post->published_at ?? $post->created_at)->toIso8601String() }}">
                            <x-filament::icon :icon="Heroicon::OutlinedCalendarDays" class="w-3.5 h-3.5 shrink-0" aria-hidden="true" />
                            {{ ($post->published_at ?? $post->created_at)->format('Y-m-d') }}
                        </time>
                    </div>
                </x-sn-cms::container.block-link>
            </article>
        @endforeach
    </div>
</section>
