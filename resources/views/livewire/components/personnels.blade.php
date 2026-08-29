@php
    use Filament\Support\Icons\Heroicon;
    use Wsmallnews\Cms\Support\Utils;
@endphp

<div class="w-full flex flex-col gap-4 relative">
    <x-sn-support::loading.overlay />

    <div class="w-full flex flex-row-reverse gap-4">
        <x-filament::input.wrapper class="w-full md:w-80" inline-prefix :prefix-icon="\Filament\Support\Icons\Heroicon::MagnifyingGlass">
            <label for="personnel-search" class="sr-only">{{ __('搜索人员姓名') }}</label>
            <x-filament::input id="personnel-search" type="search" placeholder="搜索人员姓名" wire:model.live.debounce.250ms="search" />
        </x-filament::input.wrapper>
    </div>

    <x-sn-support::paginators.container :page-type="$pageType" class="w-full @container" :page-info="$pageInfo" :paginator-link="$paginatorLink" :page-name="$pageName">
        <ul class="w-full grid grid-cols-1 @3xl:grid-cols-2 @7xl:grid-cols-3 gap-4" role="list">
            @foreach ($personnels as $personnel)
                <li>
                    <x-sn-cms::container.block-link
                        href="{{ Utils::route('personnels.show', $personnel->id) }}"
                        class="sn-container sn-link sn-hover w-full h-full flex flex-col gap-4 p-5 rounded-xl overflow-hidden group focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900"
                    >
                        {{-- 内容区：圆形头像 + 右侧字段竖排（与首页人才储备卡片一致） --}}
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-800 shrink-0">
                                @if ($personnel->getFirstMediaUrl('avatar'))
                                    <img
                                        src="{{ $personnel->getFirstMediaUrl('avatar') }}"
                                        alt="{{ $personnel->name }}"
                                        loading="lazy"
                                        class="w-full h-full object-cover sn-motion-scale"
                                    />
                                @else
                                    <div class="sn-image-placeholder">
                                        <x-filament::icon :icon="Heroicon::OutlinedUser" class="w-8 h-8" aria-hidden="true" />
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col gap-1.5 min-w-0">
                                {{-- 名称 + 职称标签同行，宽度不够时名称省略（与首页卡片一致） --}}
                                <div class="flex items-center gap-2 min-w-0">
                                    <h3 class="text-lg font-bold leading-7 text-gray-900 dark:text-gray-100 truncate sn-hover">
                                        {{ $personnel->name }}
                                    </h3>

                                    @if ($personnel->professional_title)
                                        <span class="shrink-0 px-1.5 py-0.5 rounded text-[10px] font-medium leading-4 text-primary-600 dark:text-primary-400 bg-primary-500/10 dark:bg-primary-400/10">
                                            {{ $personnel->professional_title }}
                                        </span>
                                    @endif
                                </div>

                                <dl class="flex flex-col gap-1 mt-0.5">
                                    @foreach ([
                                        ['label' => '学历', 'value' => $personnel->qualification],
                                        ['label' => '研究方向', 'value' => $personnel->research_focus],
                                        ['label' => '研究成果', 'value' => $personnel->research_result],
                                    ] as $field)
                                        @if ($field['value'])
                                            <div class="flex gap-2 items-center line-clamp-1">
                                                <dt class="text-xs text-gray-400 dark:text-gray-500 shrink-0">{{ $field['label'] }}</dt>
                                                <dd class="text-xs font-medium text-gray-600 dark:text-gray-300 truncate">{{ $field['value'] }}</dd>
                                            </div>
                                        @endif
                                    @endforeach
                                </dl>
                            </div>
                        </div>

                        {{-- 查看详情按钮（与首页一致：描边 2px 主题色） --}}
                        <span class="mt-auto inline-flex items-center justify-center h-11 rounded-lg border-2 border-primary-600 dark:border-primary-500 text-sm font-medium text-primary-600 dark:text-primary-400 group-hover:bg-primary-600 group-hover:text-white dark:group-hover:bg-primary-500 dark:group-hover:text-white transition-colors duration-200 motion-reduce:transition-none">
                            {{ __('查看详情') }}
                        </span>
                    </x-sn-cms::container.block-link>
                </li>
            @endforeach
        </ul>
    </x-sn-support::paginators.container>
</div>
