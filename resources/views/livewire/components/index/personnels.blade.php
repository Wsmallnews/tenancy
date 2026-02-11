@php
    use Filament\Support\Icons\Heroicon;
@endphp

<section class="py-10">
    <div class="flex flex-col items-center justify-center mb-12">
        <h2 class="shrink-0 text-3xl md:text-4xl font-bold text-slate-900 relative mb-4">
            人才储备
            <span class="absolute bottom-0 left-0 w-full h-1 bg-primary-500 rounded-full transform translate-y-2"></span>
        </h2>
        <p class="text-lg text-slate-600 max-w-3xl mx-auto">
            汇聚行业顶尖人才，打造高水平科研创新团队
        </p>
    </div>

    <div class="sn-container sn-hover overflow-hidden">
        @foreach ($personnels as $index => $personnel)
            <div class="sn-link flex flex-col md:flex-row items-center p-6 md:p-8 {{ $index !== $personnels->count() - 1 ? 'border-b border-slate-100' : '' }}">
                <!-- Avatar -->
                <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-8">
                    <div class="relative">
                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden ring-4 ring-slate-50">
                            @if ($personnel->getFirstMediaUrl('avatar'))
                                <img src="{{ $personnel->getFirstMediaUrl('avatar') }}" alt="{{ $personnel->name }}" class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">
                                    <x-filament::icon :icon="Heroicon::OutlinedUser" class="w-12 h-12" />
                                </div>
                            @endif
                        </div>
                        <div class="absolute bottom-1 right-1 bg-primary-500 text-white p-1.5 rounded-full shadow-md">
                            <x-filament::icon :icon="Heroicon::OutlinedCheckCircle" class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-grow text-center md:text-left space-y-3">
                    <div class="flex flex-col md:flex-row items-center gap-2 md:gap-4 mb-2">
                        <h3 class="text-2xl font-bold text-slate-900">{{ $personnel->name }}</h3>
                        @if ($personnel->professional_title)
                            <span class="px-3 py-1 bg-primary-100 text-primary-500 text-sm font-semibold rounded-full">
                                {{ $personnel->professional_title }}
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-slate-600">
                        @if ($personnel->qualification)
                            <div class="flex items-center justify-center md:justify-start gap-2">
                                <x-filament::icon :icon="Heroicon::OutlinedBookOpen" class="w-5 h-5 shrink-0 text-primary-500" />
                                <span class="text-sm font-medium">学历：{{ $personnel->qualification }}</span>
                            </div>
                        @endif
                        @if ($personnel->research_focus)
                            <div class="flex items-center justify-center md:justify-start gap-2">
                                <x-filament::icon :icon="Heroicon::OutlinedAcademicCap" class="w-5 h-5 shrink-0 text-primary-500" />
                                <span class="text-sm font-medium">研究方向：{{ $personnel->research_focus }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action -->
                <div class="mt-4 md:mt-0 md:ml-6 flex-shrink-0">
                    <x-sn-cms::container.block-link href="{{ \Wsmallnews\Cms\Support\Utils::route('personnels.show', $personnel->id) }}" class="inline-block px-6 py-2 border border-primary-500 text-primary-600 rounded-full hover:bg-primary-500 hover:text-white transition-all font-medium text-sm">
                        查看详情
                    </x-sn-cms::container.block-link>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 text-center">
        {{-- <p class="text-slate-500 mb-6">
            研究所现有高级职称专家45人，博士生导师12人。我们长期诚聘海内外优秀青年学者加入。
        </p> --}}
        <a href="{{ \Wsmallnews\Cms\Support\Utils::route('personnels') }}" class="inline-flex items-center text-primary-500 font-medium">
            查看更多人才信息 &rarr;
        </a>
    </div>
</section>