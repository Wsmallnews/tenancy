@php
    use Filament\Support\Icons\Heroicon;

    $view = $this->getThemeView('components.category.categories');
    $recordView = $this->getBladeThemeView('components.category.category-record');
@endphp

<div class="w-full flex flex-col lg:flex-row gap-4 relative">
    <x-sn-support::loading.overlay />

    @if ($categoryStyle == 'tree')
        <div class="w-full lg:w-72">
            <livewire:sn-category::components.categories
                scope-type="appraise"
                :use-url="false"
                :active-category-id="$categoryId"
                :view="$view"
                :record-view="$recordView"
                :key="'sn-category-components-categories-' . $this->getFingerprint()" />
        </div>
    @endif

    <div class="w-full flex flex-col gap-4 grow">
        @if ($categoryStyle == 'select' && isset($categories) && $categories->isNotEmpty())
            <div class="flex flex-wrap gap-4">
                @foreach ($categories as $category)
                    <x-filament::badge class="sn-primary-bg text-sm text-white">{{ $category->name_label }}</x-filament::badge>
                @endforeach
            </div>
        @endif

        <div class="sn-container p-4 flex flex-col gap-4">
            @php
                $activeFilters = $this->getActiveFilters();
            @endphp
            @if (count($activeFilters))
                <div class="flex flex-wrap flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                    <span class="text-xs font-medium text-gray-500 sm:w-24 sm:shrink-0 sm:text-right mt-1.5">已启用筛选条件</span>

                    <div class="flex flex-wrap items-center gap-2">
                        @foreach ($activeFilters as $filter)
                            <span class="flex items-center gap-1 sn-btn sn-btn-sm sn-btn-outline sn-btn-outline-primary">
                                {{ $filter['label'] }}
                                <span
                                    wire:click="resetFilter('{{ $filter['key'] }}')"
                                    @class([
                                        'cursor-pointer',
                                    ])
                                    title="清除此筛选"
                                >&times;</span>
                            </span>
                        @endforeach
                        <button
                            type="button"
                            wire:click="resetAllFilters"
                            class="sn-btn sn-btn-sm sn-btn-danger"
                        >
                            <x-filament::icon :icon="Heroicon::ArrowPath" class="size-4" aria-hidden="true" />
                            重置全部
                        </button>
                    </div>
                </div>
            @endif

            {{-- 种质类型 --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                <span class="text-xs font-medium text-gray-500 sm:w-24 sm:shrink-0 sm:text-right mt-1.5">种质类型</span>
                <div class="flex flex-wrap items-center gap-2 flex-1">
                    <button
                        type="button"
                        wire:click="$set('filter_germplasm_type', '')"
                        @class([
                            'sn-btn sn-btn-sm rounded-full',
                            'sn-btn-outline' => $filter_germplasm_type !== '',
                            'sn-btn-primary' => $filter_germplasm_type === '',
                        ])
                    >全部</button>
                    @foreach ($germplasmTypeOptions as $value => $label)
                        <button
                            type="button"
                            wire:click="$set('filter_germplasm_type', '{{ $value }}')"
                            @class([
                                'sn-btn sn-btn-sm rounded-full',
                                'sn-btn-outline' => $filter_germplasm_type !== $value,
                                'sn-btn-primary' => $filter_germplasm_type === $value,
                            ])
                        >{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            {{-- 用途 --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                <span class="text-xs font-medium text-gray-500 sm:w-24 sm:shrink-0 sm:text-right mt-1.5">用途</span>
                <div class="flex flex-wrap items-center gap-2 flex-1">
                    <button
                        type="button"
                        wire:click="$set('filter_germplasm_use', '')"
                        @class([
                            'sn-btn sn-btn-sm rounded-full',
                            'sn-btn-outline' => $filter_germplasm_use !== '',
                            'sn-btn-primary' => $filter_germplasm_use === '',
                        ])
                    >全部</button>
                    @foreach ($germplasmUseOptions as $value => $label)
                        <button
                            type="button"
                            wire:click="$set('filter_germplasm_use', '{{ $value }}')"
                            @class([
                                'sn-btn sn-btn-sm rounded-full',
                                'sn-btn-outline' => $filter_germplasm_use !== $value,
                                'sn-btn-primary' => $filter_germplasm_use === $value,
                            ])
                        >{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            {{-- 果实用途 --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                <span class="text-xs font-medium text-gray-500 sm:w-24 sm:shrink-0 sm:text-right mt-1.5">果实用途</span>
                <div class="flex flex-wrap items-center gap-2 flex-1">
                    <button
                        type="button"
                        wire:click="$set('filter_fruit_use', '')"
                        @class([
                            'sn-btn sn-btn-sm rounded-full',
                            'sn-btn-outline' => $filter_fruit_use !== '',
                            'sn-btn-primary' => $filter_fruit_use === '',
                        ])
                    >全部</button>
                    @foreach ($fruitUseOptions as $value => $label)
                        <button
                            type="button"
                            wire:click="$set('filter_fruit_use', '{{ $value }}')"
                            @class([
                                'sn-btn sn-btn-sm rounded-full',
                                'sn-btn-outline' => $filter_fruit_use !== $value,
                                'sn-btn-primary' => $filter_fruit_use === $value,
                            ])
                        >{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            {{-- 植株用途 --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                <span class="text-xs font-medium text-gray-500 sm:w-24 sm:shrink-0 sm:text-right mt-1.5">植株用途</span>
                <div class="flex flex-wrap items-center gap-2 flex-1">
                    <button
                        type="button"
                        wire:click="$set('filter_plant_use', '')"
                        @class([
                            'sn-btn sn-btn-sm rounded-full',
                            'sn-btn-outline' => $filter_plant_use !== '',
                            'sn-btn-primary' => $filter_plant_use === '',
                        ])
                    >全部</button>
                    @foreach ($plantUseOptions as $value => $label)
                        <button
                            type="button"
                            wire:click="$set('filter_plant_use', '{{ $value }}')"
                            @class([
                                'sn-btn sn-btn-sm rounded-full',
                                'sn-btn-outline' => $filter_plant_use !== $value,
                                'sn-btn-primary' => $filter_plant_use === $value,
                            ])
                        >{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            {{-- 种植收集源 --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                <span class="text-xs font-medium text-gray-500 sm:w-24 sm:shrink-0 sm:text-right mt-1.5">种植收集源</span>
                <div class="flex flex-wrap items-center gap-2 flex-1">
                    <button
                        type="button"
                        wire:click="$set('filter_assemble_resource', '')"
                        @class([
                            'sn-btn sn-btn-sm rounded-full',
                            'sn-btn-outline' => $filter_assemble_resource !== '',
                            'sn-btn-primary' => $filter_assemble_resource === '',
                        ])
                    >全部</button>
                    @foreach ($assembleResourceOptions as $value => $label)
                        <button
                            type="button"
                            wire:click="$set('filter_assemble_resource', '{{ $value }}')"
                            @class([
                                'sn-btn sn-btn-sm rounded-full',
                                'sn-btn-outline' => $filter_assemble_resource !== $value,
                                'sn-btn-primary' => $filter_assemble_resource === $value,
                            ])
                        >{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            {{-- 收集材料类型 --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                <span class="text-xs font-medium text-gray-500 sm:w-24 sm:shrink-0 sm:text-right mt-1.5">收集材料类型</span>
                <div class="flex flex-wrap items-center gap-2 flex-1">
                    <button
                        type="button"
                        wire:click="$set('filter_assemble_material_type', '')"
                        @class([
                            'sn-btn sn-btn-sm rounded-full',
                            'sn-btn-outline' => $filter_assemble_material_type !== '',
                            'sn-btn-primary' => $filter_assemble_material_type === '',
                        ])
                    >全部</button>
                    @foreach ($assembleMaterialTypeOptions as $value => $label)
                        <button
                            type="button"
                            wire:click="$set('filter_assemble_material_type', '{{ $value }}')"
                            @class([
                                'sn-btn sn-btn-sm rounded-full',
                                'sn-btn-outline' => $filter_assemble_material_type !== $value,
                                'sn-btn-primary' => $filter_assemble_material_type === $value,
                            ])
                        >{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            <x-filament::input.wrapper
                class="w-full md:w-80"
                inline-prefix
                :prefix-icon="\Filament\Support\Icons\Heroicon::MagnifyingGlass"
            >
                <label for="appraise-search" class="sr-only">{{ __('搜索种质名称、种质编号') }}</label>
                <x-filament::input
                    id="appraise-search"
                    type="search"
                    placeholder="搜索种质名称、种质编号"
                    wire:model.live.debounce.250ms="search"
                />
            </x-filament::input.wrapper>
        </div>

        {{-- ===== 种质列表 ===== --}}
        @if ($style == 'list')
            <x-sn-support::paginators.container :page-type="$pageType" :page-info="$pageInfo" :paginator-link="$paginatorLink" :page-name="$pageName">
                <ul class="w-full flex flex-col gap-4" role="list">
                    @foreach ($appraises as $appraise)
                        <li class="sn-container sn-link sn-hover flex flex-col sm:flex-row overflow-hidden group">
                            <x-sn-cms::container.block-link
                                href="{{ \Wsmallnews\Cms\Support\Utils::route('appraises.show', $appraise->id) }}"
                                class="flex flex-col sm:flex-row w-full focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 rounded-md"
                            >
                                <div class="w-full sm:w-40 aspect-video shrink-0 overflow-hidden bg-gray-100 dark:bg-gray-800">
                                    @if ($appraise->getFirstMediaUrl('cover'))
                                        <img
                                            src="{{ $appraise->getFirstMediaUrl('cover') }}"
                                            alt="{{ $appraise->name }}"
                                            loading="lazy"
                                            class="w-full h-full object-cover sn-motion-scale"
                                        />
                                    @else
                                        <div class="sn-image-placeholder">
                                            <x-filament::icon :icon="Heroicon::OutlinedPhoto" class="w-10 h-10" aria-hidden="true" />
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-col sm:flex-row w-full justify-between gap-4 px-4 py-3">
                                    <div class="flex flex-col grow gap-2 min-w-0">
                                        <h3 class="sn-h3-text sn-hover line-clamp-1">
                                            {{ $appraise->name }}
                                        </h3>
                                        <p class="sn-descript-text line-clamp-2 grow">
                                            {{ $appraise->saveCompany?->name }}
                                        </p>
                                        <div class="sn-tip-text">
                                            <time datetime="{{ $appraise->updated_at?->toIso8601String() }}">
                                                {{ $appraise->updated_at->format('Y-m-d') }}
                                            </time>
                                        </div>
                                    </div>
                                </div>
                            </x-sn-cms::container.block-link>

                            <div class="flex items-center justify-end gap-3 px-4 pb-4 sm:py-3 shrink-0">
                                <x-filament::button tag="a" color="info" href="{{ \Wsmallnews\Cms\Support\Utils::route('appraises.show', $appraise->id) }}">
                                    {{ __('详情') }}
                                </x-filament::button>

                                @if (($this->applyAction)(['appraise_id' => $appraise->id])->isVisible())
                                    {{ ($this->applyAction)(['appraise_id' => $appraise->id]) }}
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </x-sn-support::paginators.container>
        @else
            <x-sn-support::paginators.container :page-type="$pageType" class="@container" :page-info="$pageInfo" :paginator-link="$paginatorLink" :page-name="$pageName">
                <ul class="w-full grid grid-cols-1 @3xl:grid-cols-2 @5xl:grid-cols-3 @7xl:grid-cols-4 gap-4" role="list">
                    @foreach ($appraises as $appraise)
                        <li>
                            <x-sn-cms::container.block-link
                                href="{{ \Wsmallnews\Cms\Support\Utils::route('appraises.show', $appraise->id) }}"
                                class="sn-container sn-link sn-hover w-full h-full flex flex-col overflow-hidden group focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900"
                            >
                                <div class="aspect-video shrink-0 rounded-t-md overflow-hidden bg-gray-100 dark:bg-gray-800">
                                    @if ($appraise->getFirstMediaUrl('cover'))
                                        <img
                                            src="{{ $appraise->getFirstMediaUrl('cover') }}"
                                            alt="{{ $appraise->name }}"
                                            loading="lazy"
                                            class="w-full h-full object-cover sn-motion-scale"
                                        />
                                    @else
                                        <div class="sn-image-placeholder">
                                            <x-filament::icon :icon="Heroicon::OutlinedPhoto" class="w-10 h-10" aria-hidden="true" />
                                        </div>
                                    @endif
                                </div>

                                <h3 class="sn-h3-text sn-hover line-clamp-1 p-4">
                                    {{ $appraise->name }}
                                </h3>
                            </x-sn-cms::container.block-link>
                        </li>
                    @endforeach
                </ul>
            </x-sn-support::paginators.container>
        @endif

        <x-filament-actions::modals />
    </div>
</div>
