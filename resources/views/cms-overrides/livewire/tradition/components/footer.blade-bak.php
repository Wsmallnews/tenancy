@php
    use Wsmallnews\Cms\Support\Utils;
@endphp

<footer class="w-full mt-12 bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 py-10 md:py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-8">
            {{-- 品牌 --}}
            <div class="sm:col-span-2 lg:col-span-1 flex flex-col gap-4">
                <a href="{{ Utils::route('index') }}" class="flex items-center gap-3 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 rounded-sm">
                    <img src="{{ asset('image/logo.png') }}" alt="logo" class="h-12 w-auto object-contain">
                    <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ __('全国农作物种质资源信息平台') }}</span>
                </a>
            </div>

            {{-- 平台导航（顶级导航） --}}
            <nav class="flex flex-col gap-4" aria-label="{{ __('平台导航') }}">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('平台导航') }}</h2>

                <ul class="flex flex-col gap-2.5" role="list">
                    @foreach ($navigations as $navigation)
                        <li>
                            <a class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 rounded-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                                {{ \Filament\Support\generate_href_html($navigation->url_info['url'], $navigation->url_info['target'] ?? false) }}
                            >
                                {{ $navigation->name_label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            {{-- 服务支持 --}}
            <nav class="flex flex-col gap-4" aria-label="{{ __('服务支持') }}">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('服务支持') }}</h2>

                <ul class="flex flex-col gap-2.5" role="list">
                    @foreach ([
                        ['label' => __('用种申请'), 'url' => Utils::route('navigation.show', ['slug' => 'appraise-apply'])],
                        ['label' => __('常见问题'), 'url' => '#'],
                        ['label' => __('下载中心'), 'url' => '#'],
                    ] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 rounded-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            {{-- 联系我们 --}}
            <div class="flex flex-col gap-4">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('联系我们') }}</h2>

                <address class="not-italic flex flex-col gap-3">
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

            {{-- 公众号二维码 --}}
            <div class="flex flex-col items-start gap-2">
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('关注公众号') }}</h2>

                @if ($general->wechat_official_qrcode)
                    <img src="{{ files_url($general->wechat_official_qrcode) }}" alt="{{ __('sn-cms::cms.frontend.official_qrcode') }}" loading="lazy"
                        class="w-24 h-24 rounded-xl border border-gray-200 dark:border-gray-700 object-cover">
                @else
                    <span class="w-24 h-24 rounded-xl bg-[#F0F4F8] dark:bg-gray-800 flex items-center justify-center text-gray-400 dark:text-gray-600" aria-hidden="true">
                        <x-filament::icon icon="heroicon-o-qr-code" class="w-10 h-10" />
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- 底部条：主题色背景 --}}
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
