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

    // flag 标识：标签 / 颜色 / 图标映射（颜色为 Filament 色名，转成与分类标签调色板同风格的 Tailwind 类）
    $flagLabels = FlagRegistry::getTypesOptions($scopeType);
    $flagColors = FlagRegistry::getTypesColors($scopeType);
    $flagIcons = FlagRegistry::getTypesIcons($scopeType);

    $flagColorClasses = [
        'primary' => 'bg-primary-500/10 dark:bg-primary-400/10 text-primary-600 dark:text-primary-400',
        'danger' => 'bg-rose-50 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400',
        'warning' => 'bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400',
        'success' => 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400',
        'info' => 'bg-sky-50 dark:bg-sky-900/40 text-sky-600 dark:text-sky-400',
        'gray' => 'bg-gray-100 dark:bg-gray-900/40 text-gray-500 dark:text-gray-400',
    ];
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
                        {{-- 行高写死；图片区满行高 + 服务案例比例（aspect-[290/176]），cover 居中裁剪；sm 起行高加大，容纳 flag 徽章行与查看详情按钮行 --}}
                        <x-sn-cms::container.block-link
                            class="group flex flex-row gap-4 h-40 sm:h-44 p-4 overflow-hidden transition-colors duration-200 motion-reduce:transition-none hover:bg-primary-50/50 dark:hover:bg-primary-900/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-500"
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

                            <div class="flex flex-col grow min-w-0 gap-1 sm:gap-1.5">
                                {{-- 顶行：分类标签（左）+ flag 标识（右）--}}
                                @if ($post->categories->isNotEmpty() || filled($post->flags))
                                    <div class="flex items-start justify-between gap-2">
                                        @if ($post->categories->isNotEmpty())
                                            <div class="flex flex-wrap gap-1.5 min-w-0">
                                                @foreach ($post->categories as $category)
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-medium leading-4 {{ $tagPalette[$category->id % count($tagPalette)] }}">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (filled($post->flags))
                                            {{-- 最多展示 2 个 flag，避免窄屏换行撑高行 --}}
                                            <div class="flex flex-wrap justify-end gap-1.5 shrink-0 ml-auto">
                                                @foreach (array_slice($post->flags ?? [], 0, 2) as $flagType)
                                                    @continue(blank($flagLabels[$flagType] ?? null))
                                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-medium leading-4 {{ $flagColorClasses[$flagColors[$flagType] ?? 'gray'] }}">
                                                        @if (filled($flagIcons[$flagType] ?? null))
                                                            <x-filament::icon :icon="$flagIcons[$flagType]" class="w-3 h-3" aria-hidden="true" />
                                                        @endif
                                                        {{ $flagLabels[$flagType] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="sn-h3-text sn-hover line-clamp-1 transition duration-300">
                                    {{ $post->title }}
                                </div>

                                <div class="sn-descript-text line-clamp-2">
                                    {{ $post->description }}
                                </div>

                                {{-- 底行：日期（左）+ 查看详情（右）--}}
                                <div class="flex items-center justify-between gap-2 mt-auto">
                                    <div class="sn-tip-text">
                                        {{ $post->updated_at->format('Y-m-d') }}
                                    </div>

                                    {{-- 整行已是链接，按钮用 span 仅作视觉标识，避免嵌套可交互元素 --}}
                                    <span class="shrink-0 inline-flex items-center gap-1 h-7 px-2.5 rounded-md border border-primary-600/60 dark:border-primary-400/50 text-xs font-medium text-primary-600 dark:text-primary-400 group-hover:bg-primary-600 group-hover:border-primary-600 group-hover:text-white dark:group-hover:bg-primary-500 dark:group-hover:border-primary-500 dark:group-hover:text-white transition-colors duration-200 motion-reduce:transition-none">
                                        {{ __('查看详情') }}
                                        <x-filament::icon :icon="Heroicon::ChevronRight" class="w-3 h-3" aria-hidden="true" />
                                    </span>
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
