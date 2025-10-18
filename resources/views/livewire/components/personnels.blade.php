<x-dynamic-component :component="$wrapperView" class="w-full">
    <x-paginators.container :page-type="$pageType" class="@container" :page-info="$pageInfo" :paginator-link="$paginatorLink" :page-name="$pageName">
        <div class="w-full grid grid-cols-1 @3xl:grid-cols-2 @7xl:grid-cols-3 gap-4">
            @foreach ($personnels as $personnel)
                <x-dynamic-component :component="$itemWrapperView" tag="a" href="{{ sn_route('personnels.show', $personnel->id) }}" class="w-full flex flex-row gap-4 overflow-hidden group">
                    @if ($personnel->getFirstMediaUrl('avatar'))
                        <div class="w-36 aspect-5/7 flex-shrink-0 rounded-md overflow-hidden">
                            <img class="object-cover transition duration-300 rounded-md group-hover:scale-105" src="{{ $personnel->getFirstMediaUrl('avatar') }}" />
                        </div>
                    @endif

                    <div class="flex flex-col flex-grow py-4 pr-4 gap-4">
                        <div class="text-xl font-bold line-clamp-1 transition duration-300 group-hover:text-primary-500">
                            {{ $personnel->name }}
                        </div>

                        <div class="flex flex-col gap-2">
                            <div class="flex gap-2.5 items-center line-clamp-1 transition duration-300">
                                <span class="inline-block text-sm text-gray-700 min-w-16">学历</span><span class="font-bold">{{ $personnel->qualification }}</span>
                            </div>
                            <div class="flex gap-2.5 items-center line-clamp-1 transition duration-300">
                                <span class="inline-block text-sm text-gray-700 min-w-16">职称</span><span class="font-bold">{{ $personnel->professional_title }}</span>
                            </div>
                            <div class="flex gap-2.5 items-center line-clamp-1 transition duration-300">
                                <span class="inline-block text-sm text-gray-700 min-w-16">研究方向</span><span class="font-bold">{{ $personnel->research_focus }}</span>
                            </div>
                            <div class="flex gap-2.5 items-center line-clamp-1 transition duration-300">
                                <span class="inline-block text-sm text-gray-700 min-w-16">研究成果</span><span class="font-bold">{{ $personnel->research_result }}</span>
                            </div>
                        </div>
                    </div>
                </x-dynamic-component>
            @endforeach
        </div>
    </x-paginators.container>
</x-dynamic-component>