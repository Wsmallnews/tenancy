@props([
    'scopeType',
    'scopeId',
])

@php
    use Wsmallnews\Cms\CmsPlugin;
    use Wsmallnews\Cms\Support\Utils;
@endphp

<div {{ $attributes->merge(['class' => 'sn-cms-container-page w-full flex flex-col min-h-dvh']) }}>
    {{-- ======================== 顶部工具条 ======================== --}}
    <div class="hidden md:block w-full bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="container mx-auto px-4 h-10 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
            <p class="truncate">{{ __('欢迎访问全国农作物种质资源信息平台！') }}</p>

            <div class="hidden lg:flex items-center gap-4 shrink-0">
                <span>{{ __('国家农业科学数据中心农业生物种质资源专题平台') }}</span>
                <span class="text-gray-200 dark:text-gray-700 select-none" aria-hidden="true">|</span>
                <span>{{ __('国家科技资源共享服务平台') }}</span>
            </div>
        </div>
    </div>

    {{-- ======================== 页头 ======================== --}}
    <header class="w-full bg-white dark:bg-gray-900 shadow-sm">
        {{-- 容器不带内边距，logo 贴边显示更大；内边距只加在右侧搜索/登录注册组上 --}}
        <div class="container mx-auto md:h-24 flex items-center justify-between">
            <a href="{{ Utils::route('index') }}" class="flex items-center py-2 md:py-0 pl-4 md:pl-0 shrink-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 rounded-sm">
                <img src="{{ asset('image/logo.png') }}" alt="logo" class="h-16 md:h-20 w-auto object-contain">
            </a>

            <div class="flex items-center gap-3 shrink-0 px-4">
                {{-- 种质检索：提交到种质资源列表页（支持 search 参数） --}}
                <form action="{{ Utils::route('appraises') }}" method="GET" role="search"
                    class="hidden lg:flex items-stretch h-11 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus-within:border-primary-500 dark:focus-within:border-primary-500 transition-colors duration-200 overflow-hidden">
                    <label for="site-search" class="sr-only">{{ __('种质检索') }}</label>
                    <input id="site-search" type="search" name="search" placeholder="{{ __('请输入关键词') }}"
                        class="w-44 xl:w-72 px-4 bg-transparent border-0 focus:ring-0 focus:outline-none text-sm text-gray-700 dark:text-gray-200 placeholder:text-gray-400 dark:placeholder:text-gray-500" />
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-5 bg-primary-600 hover:bg-primary-500 dark:bg-primary-600 dark:hover:bg-primary-500 text-white text-sm font-medium transition-colors duration-200 motion-reduce:transition-none focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-white">
                        <x-filament::icon icon="heroicon-o-magnifying-glass" class="w-4 h-4" aria-hidden="true" />
                        {{ __('搜索') }}
                    </button>
                </form>

                @auth
                    <livewire:sn-user::components.user.menu :module="app(CmsPlugin::class)->getId()" />
                @else
                    <a href="{{ Utils::route('login') }}"
                        class="hidden sm:inline-flex items-center justify-center h-11 px-5 rounded-lg border-2 border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-600 dark:text-gray-300 hover:border-primary-500 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 motion-reduce:transition-none focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                        {{ __('sn-cms::cms.frontend.login') }}
                    </a>
                    <a href="{{ Utils::route('register') }}"
                        class="inline-flex items-center justify-center h-11 px-5 rounded-lg bg-primary-600 hover:bg-primary-500 dark:bg-primary-600 dark:hover:bg-primary-500 text-white text-sm font-medium transition-colors duration-200 motion-reduce:transition-none focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                        {{ __('sn-cms::cms.frontend.register') }}
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ======================== 导航（沿用扩展包组件，未改动） ======================== --}}
    <livewire:sn-cms::components.navigation.navigation :scope-type="$scopeType" :scope-id="$scopeId" />

    {{-- ======================== 内容区域 ======================== --}}
    <div class="w-full flex flex-col grow">
        {{ $slot }}
    </div>

    {{-- ======================== 页脚（视图已在 cms-overrides 中覆盖） ======================== --}}
    <livewire:sn-cms::components.footer :scope-type="$scopeType" :scope-id="$scopeId" />
</div>
