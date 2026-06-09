@php
    use Wsmallnews\User\Facades\UserConfig;
@endphp

<div class="w-full" >
    {{ $this->content }}

    <div class="flex flex-col mt-6 gap-4">
        @if (UserConfig::getConfig($module, 'urls.register'))
            <div class="flex text-sm items-center justify-center">
                {{ __('sn-user::user.links.no_account') }}
                <x-filament::link href="{{ UserConfig::getConfig($module, 'urls.register') }}">
                    {{ __('sn-user::user.links.go_register') }}
                </x-filament::link>
            </div>
        @endif

        @php
            $tenantSlug = request()->route('tenant') ? (is_object(request()->route('tenant')) ? request()->route('tenant')->slug : request()->route('tenant')) : null;
        @endphp

        @if ($tenantSlug)
            <div class="relative flex items-center justify-center my-2">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                </div>
                <div class="relative px-4 text-sm text-gray-500 bg-white dark:bg-gray-900">或</div>
            </div>

            <a href="{{ route('sso.redirect', ['from' => 'cms', 'tenant' => $tenantSlug]) }}"
                class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                使用园艺库账号登录
            </a>
        @endif
    </div>
</div>