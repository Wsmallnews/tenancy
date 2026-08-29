@php
    use Wsmallnews\Cms\Enums\NavigationType;
    use Wsmallnews\Cms\Support\Utils;

    // 平铺所有导航项（排除 Child 类型纯分组节点，不分层级）
    $flatNavigations = collect();
    $flatten = function ($items) use (&$flatten, &$flatNavigations) {
        foreach ($items as $item) {
            if ($item->type !== NavigationType::Child) {
                $flatNavigations->push($item);
            }

            $flatten($item->children);
        }
    };
    $flatten($navigations);
@endphp

<footer class="w-full mt-6 bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8 md:py-10">
        {{-- 三段式：左 logo（≥lg 显示）/ 中 导航平铺 / 右 联系我们；<md 竖排（左侧隐藏） --}}
        <div class="flex flex-col md:flex-row gap-8 md:gap-12 items-start">
            {{-- 左：logo，lg 以上显示 --}}
            <a href="{{ Utils::route('index') }}" class="hidden lg:block shrink-0 py-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 rounded-sm">
                <img src="{{ asset('image/logo.png') }}" alt="logo" class="h-16 w-auto object-contain">
            </a>

            {{-- 中：导航平铺，容器查询按容器宽度自适应每行列数 --}}
            <nav class="@container grow min-w-0 w-full" aria-label="{{ __('sn-cms::cms.frontend.footer_nav') }}">
                <ul class="grid grid-cols-2 @md:grid-cols-3 @2xl:grid-cols-4 @4xl:grid-cols-5 gap-x-8 gap-y-2.5" role="list">
                    @foreach ($flatNavigations as $item)
                        <li class="min-w-0">
                            <a class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 rounded-sm truncate focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                                {{ \Filament\Support\generate_href_html($item->url_info['url'], $item->url_info['target'] ?? false) }}
                            >
                                {{ $item->name_label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            {{-- 右：联系我们（复用 cms footer 右侧内容：二维码 + 联系方式） --}}
            <div class="shrink-0 w-full md:w-auto flex flex-col gap-4">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('sn-cms::cms.frontend.follow_us') }}</h2>

                @if ($general->wechat_qrcode || $general->wechat_official_qrcode)
                    <div class="flex gap-3">
                        @if ($general->wechat_qrcode)
                            <figure class="flex flex-col items-center gap-1.5">
                                <img src="{{ files_url($general->wechat_qrcode) }}" alt="{{ __('sn-cms::cms.frontend.wechat_qrcode') }}" loading="lazy"
                                    class="w-[72px] h-[72px] rounded-lg border border-gray-200 dark:border-gray-700 object-cover">
                                <figcaption class="text-xs text-gray-400 dark:text-gray-500">{{ __('sn-cms::cms.frontend.personal_wechat') }}</figcaption>
                            </figure>
                        @endif

                        @if ($general->wechat_official_qrcode)
                            <figure class="flex flex-col items-center gap-1.5">
                                <img src="{{ files_url($general->wechat_official_qrcode) }}" alt="{{ __('sn-cms::cms.frontend.official_qrcode') }}" loading="lazy"
                                    class="w-[72px] h-[72px] rounded-lg border border-gray-200 dark:border-gray-700 object-cover">
                                <figcaption class="text-xs text-gray-400 dark:text-gray-500">{{ __('sn-cms::cms.frontend.official_account') }}</figcaption>
                            </figure>
                        @endif
                    </div>
                @endif

                <address class="not-italic flex flex-col gap-2.5">
                    @if ($general->address)
                        <div class="flex items-start gap-2.5">
                            <x-filament::icon icon="heroicon-o-map-pin" class="w-4 h-4 mt-0.5 shrink-0 sn-primary-text" aria-hidden="true" />
                            <span class="text-sm leading-6 text-gray-500 dark:text-gray-400">{{ $general->address }}</span>
                        </div>
                    @endif

                    @if ($general->phone)
                        <div class="flex items-center gap-2.5">
                            <x-filament::icon icon="heroicon-o-phone" class="w-4 h-4 shrink-0 sn-primary-text" aria-hidden="true" />
                            <a href="tel:{{ $general->phone }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 tabular-nums">{{ $general->phone }}</a>
                        </div>
                    @endif

                    @if ($general->email)
                        <div class="flex items-center gap-2.5">
                            <x-filament::icon icon="heroicon-o-envelope" class="w-4 h-4 shrink-0 sn-primary-text" aria-hidden="true" />
                            <a href="mailto:{{ $general->email }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">{{ $general->email }}</a>
                        </div>
                    @endif
                </address>
            </div>
        </div>
    </div>

    {{-- 底部条：主题色背景，内容沿用 cms footer 版权信息 --}}
    <div class="w-full sn-primary-bg py-3">
        <div class="container mx-auto px-4 flex flex-wrap items-center justify-center gap-x-6 gap-y-1 text-xs text-white/90">
            <span>© {{ $general->copytime ?: now()->year }} {{ $general->copyright ?: __('全国农作物种质资源信息平台') }}</span>

            @if ($general->beian_no)
                <span class="text-white/40 select-none" aria-hidden="true">|</span>
                <a href="{{ $general->beian_url ?: '#' }}" target="_blank" rel="noopener"
                    class="hover:text-white hover:underline underline-offset-2 transition-colors">
                    {{ $general->beian_no }}
                </a>
            @endif
        </div>
    </div>
</footer>
