@push('seo')
    {{-- {!! seo()->for($navigation) !!} --}}
@endpush

<div class="w-full flex flex-col gap-4">
    <livewire:sn-components-navigation />

    <div class="container mx-auto flex flex-col gap-4 p-4 rounded-md bg-white">
        <div class="flex flex-col md:flex-row items-start gap-4">
            {{-- @if ($brothers->isNotEmpty()) 
                <ul class="flex flex-col w-full md:w-72 shrink-0 bg-primary-500">
                    @foreach ($brothers as $brother)
                        <li class="flex">
                            <a class="flex flex-grow px-4 py-4 font-bold text-white focus:underline"
                                {{ \Filament\Support\generate_href_html($brother->url_info['url'], $brother->url_info['target'] ?? '_self') }} 
                                aria-current="page"
                            >
                                {{ $navigation->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif --}}

            <x-base.block class="flex flex-col grow gap-4">
                <livewire:sn-components-post :id="$id" />
            </x-base.block>
        </div>
    </div>

    <livewire:sn-components-footer />
</div>
