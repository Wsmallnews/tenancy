@php
    use Filament\Support\Icons\Heroicon;
    use Wsmallnews\Cms\Support\Utils;
@endphp

<section class="w-full pt-6" aria-label="{{ __('首页焦点') }}">
    {{-- lg 起 flex 行 + 整行宽高比（aspect 在 flex 下生效）；两栏 calc(50%-12px) 确定性均分（grid 下行的 aspect 会失效） --}}
    <div class="w-full flex flex-col lg:flex-row lg:aspect-[1232/420] gap-4 lg:gap-6">
        {{-- 左：轮播图（置顶文章优先），每帧显示标题、描述、全部分类标签 --}}
        {{-- aspect 保证 <lg 堆叠时高度不塌陷（子元素全是 absolute）；lg 起由整行比例定高 --}}
        <div
            class="relative w-full aspect-[616/420] lg:w-[calc(50%-12px)] lg:aspect-auto lg:h-full overflow-hidden rounded-2xl shadow-md bg-gray-100 dark:bg-gray-800"
            x-data="{ active: 0, total: {{ count($slides) }}, timer: null }"
            x-init="total > 1 && (timer = setInterval(() => active = (active + 1) % total, 5000))"
            @mouseenter="clearInterval(timer)"
            @mouseleave="total > 1 && (timer = setInterval(() => active = (active + 1) % total, 5000))"
        >
            @foreach ($slides as $i => $slide)
                <a href="{{ $slide['url'] }}"
                    @if ($loop->index > 0) x-cloak @endif
                    x-show="active === {{ $loop->index }}"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-300 absolute inset-0"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="absolute inset-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 rounded-2xl"
                    aria-hidden="{{ $loop->index > 0 ? 'true' : 'false' }}"
                >
                    <img src="{{ $slide['image'] }}" alt="{{ $slide['label'] }}" loading="{{ $loop->index > 0 ? 'lazy' : 'eager' }}"
                        class="absolute inset-0 w-full h-full object-cover" />

                    {{-- 深色渐变遮罩 --}}
                    <span class="absolute inset-0 bg-gradient-to-t from-gray-950/80 via-gray-950/30 to-transparent" aria-hidden="true"></span>

                    {{-- 文案：分类标签 + 标题 + 描述（overflow-hidden + w-full 约束，防止长文案横向撑出容器） --}}
                    <span class="absolute inset-x-0 bottom-0 p-5 md:p-6 flex flex-col items-start gap-2 overflow-hidden">
                        @if ($slide['categories'])
                            <span class="max-w-full flex flex-wrap gap-2">
                                @foreach ($slide['categories'] as $categoryName)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-white bg-white/20 backdrop-blur border border-white/30">
                                        {{ $categoryName }}
                                    </span>
                                @endforeach
                            </span>
                        @endif

                        <span class="w-full text-xl md:text-2xl font-bold leading-8 text-white truncate drop-shadow-md">{{ $slide['label'] }}</span>

                        @if ($slide['description'])
                            <span class="w-full text-sm leading-5 text-white/80 line-clamp-2">{{ $slide['description'] }}</span>
                        @endif
                    </span>
                </a>
            @endforeach

            @if (count($slides) > 1)
                {{-- 左右切换 --}}
                <button type="button" @click="active = (active - 1 + total) % total" aria-label="{{ __('上一张') }}"
                    class="absolute left-3 top-1/2 -translate-y-1/2 z-10 inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur border border-white/30 text-white transition-colors duration-200 motion-reduce:transition-none focus:outline-none focus-visible:ring-2 focus-visible:ring-white">
                    <x-filament::icon icon="heroicon-o-chevron-left" class="w-5 h-5" aria-hidden="true" />
                </button>
                <button type="button" @click="active = (active + 1) % total" aria-label="{{ __('下一张') }}"
                    class="absolute right-3 top-1/2 -translate-y-1/2 z-10 inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur border border-white/30 text-white transition-colors duration-200 motion-reduce:transition-none focus:outline-none focus-visible:ring-2 focus-visible:ring-white">
                    <x-filament::icon icon="heroicon-o-chevron-right" class="w-5 h-5" aria-hidden="true" />
                </button>

                {{-- 指示点：激活为主题色长条 --}}
                <div class="absolute bottom-4 right-6 z-10 flex items-center gap-2" role="tablist" aria-label="{{ __('轮播指示') }}">
                    @foreach ($slides as $i => $slide)
                        <button type="button" @click="active = {{ $loop->index }}" role="tab"
                            :aria-selected="active === {{ $loop->index }}"
                            :class="active === {{ $loop->index }} ? 'w-6 h-2.5 bg-primary-500' : 'w-2.5 h-2.5 bg-white/50 hover:bg-white/80'"
                            class="rounded-full transition-all duration-300 motion-reduce:transition-none focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                            aria-label="{{ __('第 :n 张', ['n' => $loop->index + 1]) }}"></button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 右：分类 tab + 文章列表（与轮播图互不相关） --}}
        {{-- lg:h-full 贴合整行宽高比；列表固定 4 条，lg 起 grid-rows-4 行高自适应（高时行变高、矮时行变矮） --}}
        <div class="w-full lg:w-[calc(50%-12px)] lg:min-w-0 lg:h-full flex flex-col gap-5 bg-white dark:bg-gray-900 rounded-2xl shadow-md p-6">
            {{-- 分类 tab：横向可滚动，禁止竖向滚动条（下划线与分隔线相接，不用负 margin 压线避免被裁剪） --}}
            <div class="w-full shrink-0 flex items-center gap-6 border-b border-gray-200 dark:border-gray-800 overflow-x-auto overflow-y-hidden" role="tablist" aria-label="{{ __('文章分类') }}">
                @foreach ($categories as $category)
                    <button type="button" role="tab" wire:click="selectCategory({{ $category->id }})"
                        aria-selected="{{ $category->id === $this->activeCategoryId ? 'true' : 'false' }}"
                        @class([
                            'shrink-0 pb-3 text-sm font-semibold border-b-2 underline-offset-8 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 rounded-t-sm transition-colors duration-200 motion-reduce:transition-none',
                            'text-primary-600 dark:text-primary-400 border-primary-600 dark:border-primary-400' => $category->id === $this->activeCategoryId,
                            'text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 border-transparent' => $category->id !== $this->activeCategoryId,
                        ])
                    >
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <ul class="w-full grow min-h-0 flex flex-col gap-1.5 lg:grid lg:grid-rows-4 lg:gap-1" role="list">
                @forelse ($categoryPosts as $post)
                    <li class="min-h-0">
                        <x-sn-cms::container.block-link
                            href="{{ Utils::route('posts.show', $post) }}"
                            class="group flex items-center gap-4 h-full px-3 py-2 rounded-xl hover:bg-primary-50/70 dark:hover:bg-primary-900/20 transition-colors duration-200 motion-reduce:transition-none focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                        >
                            <div class="w-20 h-16 lg:h-full lg:w-auto lg:aspect-[5/4] rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 shrink-0">
                                @if ($post->getFirstMediaUrl('post_image'))
                                    <img
                                        src="{{ $post->getFirstMediaUrl('post_image') }}"
                                        alt="{{ $post->title }}"
                                        loading="lazy"
                                        class="w-full h-full object-cover sn-motion-scale"
                                    />
                                @else
                                    <div class="sn-image-placeholder">
                                        <x-filament::icon :icon="Heroicon::OutlinedPhoto" class="w-6 h-6" aria-hidden="true" />
                                    </div>
                                @endif
                            </div>

                            <div class="grow min-w-0 flex flex-col gap-1">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400">
                                    {{ $post->title }}
                                </h3>
                                <time class="text-xs text-gray-500 dark:text-gray-400 tabular-nums shrink-0" datetime="{{ ($post->published_at ?? $post->created_at)->toIso8601String() }}">
                                    {{ ($post->published_at ?? $post->created_at)->format('Y-m-d') }}
                                </time>
                            </div>
                        </x-sn-cms::container.block-link>
                    </li>
                @empty
                    <li class="py-10 lg:row-span-4 text-center text-sm text-gray-400 dark:text-gray-500 flex items-center justify-center">{{ __('该分类下暂无文章') }}</li>
                @endforelse
            </ul>
        </div>
    </div>
</section>
