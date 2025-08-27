@props([
    'isGrid' => true,
    'default' => 1,
    'direction' => 'row',
    'sm' => null,
    'md' => null,
    'lg' => null,
    'xl' => null,
    'twoXl' => null,
])

<div
    {{
        $attributes
            ->class([
                'grid grid-cols-1 lg:grid-cols-2 2xl:grid-cols-3',
            ])
    }}
>
    {{ $slot }}
</div>
