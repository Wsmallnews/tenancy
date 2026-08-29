@php
    use Filament\Support\Icons\Heroicon;
    use Wsmallnews\Cms\Support\Utils;
@endphp

<section class="w-full pt-6" aria-labelledby="personnels-heading">
    <x-index.section-header
        headingId="personnels-heading"
        title="{{ __('人才储备') }}"
        description="{{ __('汇聚行业顶尖人才，打造高水平科研创新团队') }}"
        :href="Utils::route('personnels')"
    />

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        @foreach ($personnels as $personnel)
            <article class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-5 flex flex-col gap-4 group">
                {{-- 上部内容区：圆形头像 + 右侧字段竖排 --}}
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
                        {{-- 名称 + 职称标签同行，宽度不够时名称省略 --}}
                        <div class="flex items-center gap-2 min-w-0">
                            <h3 class="text-lg font-bold leading-7 text-gray-900 dark:text-gray-100 truncate">
                                {{ $personnel->name }}
                            </h3>

                            @if ($personnel->professional_title)
                                <span class="shrink-0 px-1.5 py-0.5 rounded text-[10px] font-medium leading-4 text-primary-600 dark:text-primary-400 bg-primary-500/10 dark:bg-primary-400/10">
                                    {{ $personnel->professional_title }}
                                </span>
                            @endif
                        </div>

                        @if ($personnel->research_focus)
                            <p class="text-xs leading-4 text-gray-500 dark:text-gray-400 truncate">
                                <span class="sr-only">研究方向：</span>{{ __('研究方向：') . $personnel->research_focus }}
                            </p>
                        @endif

                        @if ($personnel->research_result)
                            <p class="text-xs leading-4 text-gray-500 dark:text-gray-400 truncate">
                                <span class="sr-only">成果：</span>{{ __('成果：') . $personnel->research_result }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- 下部：查看详情按钮（按设计稿：描边 2px 主题色） --}}
                <x-sn-cms::container.block-link
                    href="{{ Utils::route('personnels.show', $personnel->id) }}"
                    class="mt-auto inline-flex items-center justify-center h-11 rounded-lg border-2 border-primary-600 dark:border-primary-500 text-sm font-medium text-primary-600 dark:text-primary-400 hover:bg-primary-600 hover:border-primary-600 hover:text-white dark:hover:bg-primary-500 dark:hover:text-white transition-colors duration-200 motion-reduce:transition-none focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900"
                >
                    {{ __('查看详情') }}
                </x-sn-cms::container.block-link>
            </article>
        @endforeach
    </div>
</section>
