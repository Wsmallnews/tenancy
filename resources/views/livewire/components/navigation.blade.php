@php
    $navigations = getNavigations();
@endphp

<x-base.block class="w-full mb-4" tag="nav" x-data="{ open: false }">
    <div class="container mx-auto px-4 sm:px-0">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('sn-shop.index') }}" wire:navigate>
                        <x-filament-panels::logo class="" />
                    </a>
                </div>


                @foreach ($navigations as $navigation)
                    @if ($navigation->children->count() > 0)
                        <x-filament::dropdown>
                            <x-slot name="trigger">
                                <x-filament::button>
                                    {{ $navigation->name }}
                                </x-filament::button>
                            </x-slot>
                            
                            <x-filament::dropdown.list>
                                <x-filament::dropdown.list.item wire:click="openViewModal">
                                    View
                                </x-filament::dropdown.list.item>
                                
                                <x-filament::dropdown.list.item wire:click="openEditModal">
                                    Edit
                                </x-filament::dropdown.list.item>
                                
                                <x-filament::dropdown.list.item wire:click="openDeleteModal">
                                    Delete
                                </x-filament::dropdown.list.item>
                            </x-filament::dropdown.list>
                        </x-filament::dropdown>
                    @else 
                        <a class="text-[20px] text-[#333] font-bold block"
                                {{ \Filament\Support\generate_href_html($navigation->url_info['url'], $navigation->url_info['target'] ?? false) }}
                            >
                            {{ $navigation->name }}
                        </a>

                        <x-base.operator class="flex items-center justify-center px-1" border="border-b-2" :href="route('sn-shop.index')" :shouldOpenUrlInSpaMode="true" :active="request()->routeIs('sn-shop.index')">
                            首页
                        </x-base.operator>
                    @endif


                    


                    <div class="relative group/parent">
                        <a class="text-[20px] text-[#333] font-bold block"
                            @if ($navigation->children->count() > 0) href="javascript:;"
                            @else
                                {{ \Filament\Support\generate_href_html($navigation->url_info['url'], $navigation->url_info['target'] ?? false) }} @endif>
                            {{ $navigation->name }}
                        </a>

                        @if ($navigation->children->count() > 0)
                            <div class="absolute left-1/2 top-0 z-20 w-[150px] -ml-[75px] hidden group-hover/parent:block">
                                <div class="flex justify-center mt-[24px]">
                                    <text class="iconfont icon-flower text-[34px] text-[#B79A2B]"></text>
                                </div>
                                <div
                                    class="mt-[40px] w-[150px] rounded-[10px] bg-[#fff4c7] box-border px-[8px] py-[10px]">
                                    @foreach ($navigation->children as $child)
                                        <div
                                            class="two-menu-item w-[134px] h-[34px] rounded-[6px] box-border px-[8px] flex justify-between items-center group cursor-pointer">
                                            <a class="flex w-full h-full text-[14px] text-[#333] items-center"
                                                {{ \Filament\Support\generate_href_html($child->url_info['url'], $child->url_info['target'] ?? false) }}>
                                                {{ $child->name }}
                                            </a>
                                            <text
                                                class="iconfont icon-right navbar-right  text-[#333] hidden group-hover:block"></text>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($loop->index == 3)
                        <div class="w-[200px] h-full">
                            <img class="absolute left-50% top-0 -translate-x-50% w-[200px] h-[200px] z-10"
                src="{{ asset('image/logo.png') }}" />
                        </div>
                    @endif
                @endforeach


                <!-- Navigation Links -->
                {{-- <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-base.operator class="flex items-center justify-center px-1" border="border-b-2" :href="route('sn-shop.index')" :shouldOpenUrlInSpaMode="true" :active="request()->routeIs('sn-shop.index')">
                        首页
                    </x-base.operator>
                </div> --}}




            </div>

            <!-- Settings Dropdown -->
            {{-- <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-filament::dropdown>
                    <x-slot name="trigger">
                        <x-sn-support::base.operator class="flex items-center justify-center px-1">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <x-heroicon-m-chevron-down class="w-5 h-5 font-bold" />
                            </div>
                        </x-sn-support::base.operator>
                    </x-slot>

                    <x-filament::dropdown.list>
                        <x-filament::dropdown.list.item :href="route('sn-shop.index')" tag="a">
                            {{ __('Profile') }}
                        </x-filament::dropdown.list.item>

                        <x-filament::dropdown.list.item wire:click="logout">
                            {{ __('Log Out') }}
                        </x-filament::dropdown.list.item>
                    </x-filament::dropdown.list>
                </x-filament::dropdown>
            </div> --}}

            <!-- Hamburger -->
            {{-- <div class="-me-2 flex items-center sm:hidden">
                <x-sn-support::base.operator class="flex items-center justify-center" tag="button" @click="open = ! open">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </x-sn-support::base.operator>
            </div> --}}
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-sn-support::base.operator class="block w-full ps-3 pe-4 py-2" :bg="true" border="border-l-4" :href="route('sn-shop.index')" :active="request()->routeIs('sn-shop.index')">
                首页
            </x-sn-support::base.operator>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-sn-support::base.operator class="block w-full ps-3 pe-4 py-2" border="border-l-4" :href="route('sn-shop.index')">
                    {{ __('Profile') }}
                </x-sn-support::base.operator>

                <!-- Authentication -->
                <x-sn-support::base.operator class="block w-full ps-3 pe-4 py-2" border="border-l-4" wire:click="logout">
                    {{ __('Log Out') }}
                </x-sn-support::base.operator>
            </div>
        </div>
    </div>
</x-base.block>


@assets
<script>
    function navigationPage({

    }) {
        return {
            init() {},
        }
    }
</script>
@endassets