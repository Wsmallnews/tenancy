<x-dynamic-component :component="$wrapperView" class="w-full">
    <div class="w-full flex flex-col gap-4">
        <div class="text-xl font-bold">
            {{ $personnel->name }}
        </div>

        <div class="flex flex-col md:flex-row gap-4 bg-gray-100 p-4 rounded-md">
            @if ($personnel->getFirstMediaUrl('avatar'))
                <div class="w-full md:w-48 flex-shrink-0 rounded-md overflow-hidden">
                    <img class="w-full h-auto object-cover transition rounded-md duration-300 group-hover:scale-105" src="{{ $personnel->getFirstMediaUrl('avatar') }}" />
                </div>
            @endif
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
                <div class="flex gap-2.5 transition duration-300 leading-7">
                    <span class="inline-block text-sm text-gray-700 min-w-16">个人简介</span><span class="font-bold">{{ $personnel->intro }}</span>
                </div>
            </div>
        </div>
        <div>
            {!! $personnel->content?->content !!}
        </div>
    </div>
</x-dynamic-component>