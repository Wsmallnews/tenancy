@props([
    'width' => '100%',
])

<div
    {{ $attributes->merge([
        'class' => 'sn-container overflow-hidden',
        'style' => "width: {$width}",
    ]) }}
>
    <div class="divide-y divide-gray-200 dark:divide-white/10">
        {{ $slot }}
    </div>
</div>
