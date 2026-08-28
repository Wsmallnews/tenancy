@php
    use Filament\Support\Icons\Heroicon;
    use Wsmallnews\Cms\Facades\FlagRegistry;

    $flags = FlagRegistry::getTypes($scopeType);

    $view = $this->getThemeView('components.category.categories');
    $recordView = $this->getBladeThemeView('components.category.category-record');

    // 分类标签调色板（与首页一致，按分类 id 稳定取色）
    $tagPalette = [
        'bg-primary-500/10 dark:bg-primary-400/10 text-primary-600 dark:text-primary-400',
        'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400',
        'bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400',
        'bg-rose-50 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400',
    ];

    // 预加载分类关联，避免视图内逐条查询（$posts 可能为普通 Collection，转成 Eloquent Collection 后单条 SQL 预加载）
    $posts = $posts instanceof \Illuminate\Database\Eloquent\Collection
        ? $posts
        : new \Illuminate\Database\Eloquent\Collection($posts->all());
    $posts->loadMissing('categories');
@endphp

<div class="w-full flex flex-col lg:flex-row gap-4 relative">
    <x-sn-support::loading.overlay />

    @if ($categoryStyle == 'tree')
        <div class="w-full lg:w-72 shrink-0">
            {{-- 分类树区块：亮色白底 / 暗色深底 --}}
            <div class="sn-container p-2">
                <livewire:sn-category::components.categories
                    :scope-type="$scopeType"
                    :use-url="false"
                    :active-category-id="$categoryId"
                    :view="$view"
                    :record-view="$recordView"
                    :key="'sn-category::components.categories-' . $this->getFingerprint()" />
            </div>
        </div>
    @endif

    <div class="w-full flex flex-col gap-4 grow min-w-0">
        @if ($categoryStyle == 'select' && $categories->isNotEmpty())
            <div class="flex flex-wrap gap-4">
                @foreach ($categories as $category)
                    <x-filament::badge class="sn-primary-bg text-sm text-white">{{ $category->name_label }}</x-filament::badge>
                @endforeach
            </div>
        @endif

        {{-- 右侧整体一个区块：头部（标签左 + 搜索右）+ 分割线 + 文章列表 --}}
        <div class="sn-container rounded-md overflow-hidden">

            <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-3 px-4 py-3 border-b border-gray-200 dark:border-gray-800">
                {{-- 剥掉 fi-tabs 自带的容器外观（白底/边框/阴影/内边距），只保留 tab 悬停与激活态 --}}
                <x-filament::tabs label="flags" class="min-w-0 bg-transparent! shadow-none! ring-0! rounded-none! p-0!">
                    <x-filament::tabs.item
                        wire:click="$set('flag', '')"
                        :active="blank($flag)"
                    >
                        {{ __('sn-cms::cms.frontend.all') }}
                    </x-filament::tabs.item>
                    @foreach ($flags as $flagItem)
                        <x-filament::tabs.item
                            wire:click="$set('flag', '{{ $flagItem['type'] }}')"
                            :active="$flagItem['type'] == $flag"
                            :icon="$flagItem['icon']"
                        >
                            {{ $flagItem['label'] }}
                        </x-filament::tabs.item>
                    @endforeach
                </x-filament::tabs>

                <x-filament::input.wrapper
                    class="w-full sm:w-72"
                    inline-prefix
                    :prefix-icon="Heroicon::MagnifyingGlass"
                >
                    <label for="post-search" class="sr-only">{{ __('sn-cms::cms.frontend.search') }}</label>
                    <x-filament::input
                        id="post-search"
                        type="search"
                        placeholder="{{ __('sn-cms::cms.frontend.search_placeholder') }}"
                        wire:model.live.debounce.250ms="search"
                    />
                </x-filament::input.wrapper>
            </div>

            {{-- 文章列表：无独立卡片，行间分割线 --}}
            <x-sn-support::paginators.container :page-type="$pageType" :page-info="$pageInfo" :paginator-link="$paginatorLink" :page-name="$pageName">
                <div class="w-full flex flex-col divide-y divide-gray-100 dark:divide-gray-800/70">
                    @forelse ($posts as $post)
                        {{-- 行高写死；图片区满行高 + 服务案例比例（aspect-[290/176]），cover 居中裁剪 --}}
                        <x-sn-cms::container.block-link
                            class="group flex flex-row gap-4 h-36 p-4 overflow-hidden transition-colors duration-200 motion-reduce:transition-none hover:bg-primary-50/50 dark:hover:bg-primary-900/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-500"
                            href="{{ \Wsmallnews\Cms\Support\Utils::route('posts.show', $post) }}"
                        >
                            <div class="h-full aspect-[290/176] max-w-[45%] shrink-0 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-800">
                                @if ($post->getFirstMediaUrl('post_image'))
                                    <img class="sn-motion-scale w-full h-full object-cover" src="{{ $post->getFirstMediaUrl('post_image') }}" alt="{{ $post->title }}" loading="lazy" />
                                @else
                                    <div class="sn-image-placeholder sn-motion-scale">
                                        <x-filament::icon :icon="Heroicon::OutlinedPhoto" class="w-10 h-10" aria-hidden="true" />
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col grow min-w-0 gap-1.5">
                                {{-- 分类标签：全部显示（标题上方） --}}
                                @if ($post->categories->isNotEmpty())
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($post->categories as $category)
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-medium leading-4 {{ $tagPalette[$category->id % count($tagPalette)] }}">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="sn-h3-text sn-hover line-clamp-1 transition duration-300">
                                    {{ $post->title }}
                                </div>

                                <div class="sn-descript-text line-clamp-2">
                                    {{ $post->description }}
                                </div>

                                <div class="sn-tip-text mt-auto">
                                    {{ $post->updated_at->format('Y-m-d') }}
                                </div>
                            </div>
                        </x-sn-cms::container.block-link>
                    @empty
                        <div class="py-16 text-center sn-descript-text">{{ __('暂无文章') }}</div>
                    @endforelse
                </div>
            </x-sn-support::paginators.container>
        </div>
    </div>
</div>
