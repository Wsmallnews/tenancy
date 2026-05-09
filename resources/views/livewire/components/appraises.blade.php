@php
    use Filament\Support\Icons\Heroicon;
@endphp

<div class="w-full flex flex-col gap-4">
    <div class="w-full flex flex-row-reverse items-center gap-4">
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
