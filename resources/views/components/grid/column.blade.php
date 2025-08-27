@props([
    'default' => 1,
    'sm' => null,
    'md' => null,
    'lg' => null,
    'xl' => null,
    'twoXl' => null,
    'defaultStart' => null,
    'smStart' => null,
    'mdStart' => null,
    'lgStart' => null,
    'xlStart' => null,
    'twoXlStart' => null,
    'hidden' => false,
])

@php
    $getSpanValue = function ($span): string {
        if ($span === 'full') {
            return '1 / -1';
        }

        return "span {$span} / span {$span}";
    };
@endphp

<div
    {{
        $attributes
            ->class([
                'col-span-1',
            ])
    }}
>
    {{ $slot }}
</div>
