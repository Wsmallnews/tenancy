@php
    use Filament\Support\Icons\Heroicon;
@endphp

<x-sn-support::paginators.container :page-type="$pageType" class="w-full @container" :page-info="$pageInfo" :paginator-link="$paginatorLink" :page-name="$pageName">
    <ul class="w-full grid grid-cols-1 @3xl:grid-cols-2 @7xl:grid-cols-3 gap-4" role="list">
        @foreach ($personnels as $personnel)
            <li>
                <x-sn-cms::container.block-link
                    href="{{ \Wsmallnews\Cms\Support\Utils::route('personnels.show', $personnel->id) }}"
                    class="sn-container sn-link sn-hover w-full h-full flex flex-row gap-4 p-4 overflow-hidden group focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900"
                >
                    <div class="w-32 sm:w-36 aspect-5/7 shrink-0 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-800">
                        @if ($personnel->getFirstMediaUrl('avatar'))
                            <img
                                src="{{ $personnel->getFirstMediaUrl('avatar') }}"
                                alt="{{ $personnel->name }}"
                                loading="lazy"
                                class="w-full h-full object-cover sn-motion-scale"
                            />
                        @else
                            <div class="sn-image-placeholder">
                                <x-filament::icon :icon="Heroicon::OutlinedUser" class="w-12 h-12" aria-hidden="true" />
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col grow py-2 pr-2 gap-3 min-w-0">
                        <h3 class="sn-h3-text sn-hover line-clamp-1">
                            {{ $personnel->name }}
                        </h3>

                        <dl class="flex flex-col gap-2">
                            @php
                                $fields = [
                                    ['label' => '学历', 'value' => $personnel->qualification],
                                    ['label' => '职称', 'value' => $personnel->professional_title],
                                    ['label' => '研究方向', 'value' => $personnel->research_focus],
                                    ['label' => '研究成果', 'value' => $personnel->research_result],
                                ];
                            @endphp
                            @foreach ($fields as $field)
                                @if ($field['value'])
                                    <div class="flex gap-2.5 items-center line-clamp-1">
                                        <dt class="sn-tip-text shrink-0 min-w-16">{{ $field['label'] }}</dt>
                                        <dd class="sn-descript-text font-semibold text-gray-700 dark:text-gray-200 truncate">{{ $field['value'] }}</dd>
                                    </div>
                                @endif
                            @endforeach
                        </dl>
                    </div>
                </x-sn-cms::container.block-link>
            </li>
        @endforeach
    </ul>
</x-sn-support::paginators.container>
