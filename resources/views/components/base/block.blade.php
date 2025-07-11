@props([
    'tag' => 'div',
])

{{-- 支持暗黑的区块，默认是白色 --}}
<{{ $tag }}
    {{ $attributes->merge(['class' => 'p-4 rounded-md shadow-sm ring-1 bg-white ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10']) }}
>
    {{ $slot }}
</{{ $tag }}>