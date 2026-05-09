@php
    use Filament\Support\Icons\Heroicon;
@endphp

<section class="py-12 md:py-16" aria-labelledby="personnels-heading">
    <header class="flex flex-col items-center justify-center text-center mb-12">
        <h2 id="personnels-heading" class="sn-section-title sn-h1-text mb-6">人才储备</h2>
        <p class="sn-content-text max-w-3xl mx-auto">
            汇聚行业顶尖人才，打造高水平科研创新团队
        </p>
    </header>

    <ul class="sn-container sn-hover sn-divide-y overflow-hidden" role="list">
        @foreach ($personnels as $personnel)
            <li class="flex flex-col md:flex-row items-center p-6 md:p-8 gap-6 md:gap-8">
                <!-- Avatar -->
                <div class="shrink-0">
                    <div class="relative">
                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden ring-4 ring-gray-100 dark:ring-gray-800">
                            @if ($personnel->getFirstMediaUrl('avatar'))
                                <img
                                    src="{{ $personnel->getFirstMediaUrl('avatar') }}"
                                    alt="{{ $personnel->name }}"
                                    loading="lazy"
                                    class="w-full h-full object-cover"
                                />
                            @else
                                <div class="sn-image-placeholder">
                                    <x-filament::icon :icon="Heroicon::OutlinedUser" class="w-12 h-12" aria-hidden="true" />
                                </div>
                            @endif
                        </div>
                        <span class="sn-primary-bg absolute bottom-1 right-1 text-white p-1.5 rounded-full shadow-md ring-2 ring-white dark:ring-gray-900" aria-hidden="true">
                            <x-filament::icon :icon="Heroicon::OutlinedCheckCircle" class="w-4 h-4" />
                        </span>
                    </div>
                </div>

                <!-- Info -->
                <div class="grow text-center md:text-left space-y-3 min-w-0">
                    <div class="flex flex-col md:flex-row items-center gap-2 md:gap-4">
                        <h3 class="sn-h2-text">{{ $personnel->name }}</h3>
                        @if ($personnel->professional_title)
                            <span class="sn-primary-text px-3 py-1 bg-primary-100 dark:bg-primary-900/40 text-sm rounded-full">
                                {{ $personnel->professional_title }}
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @if ($personnel->qualification)
                            <div class="flex items-center justify-center md:justify-start gap-2">
                                <x-filament::icon :icon="Heroicon::OutlinedBookOpen" class="w-5 h-5 shrink-0 sn-primary-text" aria-hidden="true" />
                                <span class="sn-descript-text"><span class="sr-only">学历：</span>学历：{{ $personnel->qualification }}</span>
                            </div>
                        @endif
                        @if ($personnel->research_focus)
                            <div class="flex items-center justify-center md:justify-start gap-2">
                                <x-filament::icon :icon="Heroicon::OutlinedAcademicCap" class="w-5 h-5 shrink-0 sn-primary-text" aria-hidden="true" />
                                <span class="sn-descript-text">研究方向：{{ $personnel->research_focus }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action -->
                <div class="shrink-0">
                    <x-sn-cms::container.block-link
                        href="{{ \Wsmallnews\Cms\Support\Utils::route('personnels.show', $personnel->id) }}"
                        class="inline-flex items-center justify-center min-h-[44px] px-6 py-2 border border-primary-500 dark:border-primary-400 text-primary-600 dark:text-primary-400 rounded-full hover:bg-primary-500 hover:text-white dark:hover:bg-primary-500 dark:hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 transition-colors duration-200 motion-reduce:transition-none font-medium text-sm"
                    >
                        {{ __('查看详情') }}
                    </x-sn-cms::container.block-link>
                </div>
            </li>
        @endforeach
    </ul>

    <div class="mt-10 text-center">
        <x-sn-cms::container.block-link href="{{ \Wsmallnews\Cms\Support\Utils::route('personnels') }}" class="sn-link-more">
            {{ __('查看更多人才信息') }}
            <x-filament::icon :icon="Heroicon::OutlinedArrowRight" class="w-4 h-4" aria-hidden="true" />
        </x-sn-cms::container.block-link>
    </div>
</section>
