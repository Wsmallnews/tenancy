<x-dynamic-component component="base.empty-block" class="w-full">
    @if ($style == 'list')
        <x-paginators.container :page-type="$pageType" :page-info="$pageInfo" :paginator-link="$paginatorLink" :page-name="$pageName">
            <div class="w-full flex flex-col gap-4">
                @foreach ($appraises as $appraise)
                    <x-dynamic-component component="base.empty-block" tag="a" href="{{ \Wsmallnews\Cms\Support\Utils::route('appraises.show', $appraise->id) }}" class="flex flex-row overflow-hidden group bg-white rounded-md">
                        @if ($appraise->getFirstMediaUrl('cover'))
                        <div class="w-40 aspect-16/9 flex-shrink-0 overflow-hidden">
                            <img class="w-full h-full object-cover transition duration-300 group-hover:scale-105" src="{{ $appraise->getFirstMediaUrl('cover') }}" />
                        </div>
                        @endif

                        <div class="flex flex-row w-full justify-between px-4 py-2">
                            <div class="flex flex-col flex-grow gap-2">
                                <div class="text-xl font-bold line-clamp-1 transition duration-300 group-hover:text-primary-500">
                                    {{ $appraise->name }}
                                </div>
    
                                <div class="flex-grow max-h-14 text-gray-500 leading-7 line-clamp-2">
                                    {{ $appraise->saveCompany?->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $appraise->updated_at->format('Y-m-d') }}
                                </div>
                            </div>

                            <div class="flex items-center">
                                <x-filament::button size="sm" class="h-8 text-white" >
                                    用种申请
                                </x-filament::button>
                            </div>
                        </div>
                    </x-dynamic-component>
                @endforeach
            </div>
        </x-paginators.container>
    @else
        <x-paginators.container :page-type="$pageType" class="@container" :page-info="$pageInfo" :paginator-link="$paginatorLink" :page-name="$pageName">
            <div class="w-full grid grid-cols-1 @3xl:grid-cols-2 @5xl:grid-cols-3 @7xl:grid-cols-4 gap-4">
                @foreach ($appraises as $appraise)
                    <x-dynamic-component component="base.empty-block" tag="a" href="{{ \Wsmallnews\Cms\Support\Utils::route('appraises.show', $appraise->id) }}" class="w-full flex flex-col overflow-hidden group bg-white rounded-md shadow-md">
                        @if ($appraise->getFirstMediaUrl('cover'))
                            <div class="aspect-16/9 flex-shrink-0 rounded-t-md overflow-hidden">
                                <img class="w-full h-full object-cover transition duration-300 rounded-t-md group-hover:scale-105" src="{{ $appraise->getFirstMediaUrl('cover') }}" />
                            </div>
                        @endif

                        <div class="text-xl font-bold line-clamp-1 p-4 transition duration-300 group-hover:text-primary-500">
                            {{ $appraise->name }}
                        </div>
                    </x-dynamic-component>
                @endforeach
            </div>
        </x-paginators.container>
    @endif
</x-dynamic-component>