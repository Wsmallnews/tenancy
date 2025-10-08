@push('seo')
    {{-- {!! seo()->for($navigation) !!} --}}
@endpush

<div class="w-full flex flex-col grow gap-4">
    <livewire:sn-components-navigation />

    <div class="container mx-auto flex flex-col grow gap-4 p-4 rounded-md">
        <div class="flex flex-col md:flex-row items-start gap-4">
            <x-base.block class="flex flex-col grow gap-4">
                <livewire:sn-components-personnel :id="$id" />
            </x-base.block>
        </div>
    </div>

    <livewire:sn-components-footer />
</div>
