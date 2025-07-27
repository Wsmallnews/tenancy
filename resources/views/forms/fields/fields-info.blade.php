@php
    $count = $count ?? count($fields ?? []);

    $lgBlank = 2 - ($count % 2);
    $twoXlBlank = 3 - ($count % 3);
@endphp

<div>
    <div class="text-sm">{{ $title }}</div>
    <x-filament::grid
        class="border-t border-l border-gray-200 rounded-md overflow-hidden"
        :default="1"
        :lg="2"
        :twoXl="3"
    >
        @foreach($fields as $field)
            <x-filament::grid.column
                @class([
                    'w-full flex border-r border-b border-gray-200',
                    'row-span-2' => $field['type'] === 'image',
                ])
            >
                <div class="w-1/3 h-full min-h-14 flex items-center px-2 text-sm font-medium bg-gray-100">{{ $field['label'] }}</div>
                <div class="w-2/3 h-full min-h-14 flex items-center px-2 text-sm">
                    @if($field['type'] === 'image')
                        <x-filament::icon
                            icon="{{ $field['value'] }}"
                            class="max-w-full h-24 object-cover rounded-md"
                        />
                    @else
                        {{ $field['value'] }}
                    @endif
                </div>
            </x-filament::grid.column>
        @endforeach

        @for($i = 0; $i < $lgBlank; $i++)
            <x-filament::grid.column
                class="w-full border-r border-b border-gray-200 hidden lg:flex"
            />
        @endfor

        @for($i = 0; $i < $twoXlBlank; $i++)
            <x-filament::grid.column
                class="w-full border-r border-b border-gray-200 hidden 2xl:flex"
            />
        @endfor
    </x-filament::grid>
</div>