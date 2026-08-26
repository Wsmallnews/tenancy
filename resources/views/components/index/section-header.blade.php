@props([
    'title',
    'description' => null,
    'href' => null,
    'headingId' => null,
])

<header class="w-full flex flex-wrap items-center justify-between gap-x-4 gap-y-3 mb-4">
    <div class="flex items-center gap-4">
        <span class="w-1 h-8 rounded-sm bg-gradient-to-b from-primary-700 to-primary-500 shrink-0" aria-hidden="true"></span>

        <div class="flex flex-col gap-1">
            <h2 @if ($headingId) id="{{ $headingId }}" @endif class="text-xl font-bold leading-7 text-gray-900 dark:text-gray-100">
                {{ $title }}
            </h2>

            @if ($description)
                <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">{{ $description }}</p>
            @endif
        </div>
    </div>

    @if ($href)
        <x-sn-cms::container.block-link
            href="{{ $href }}"
            class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors duration-200 motion-reduce:transition-none rounded-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
        >
            {{ __('查看更多') }}
            <x-filament::icon icon="heroicon-o-chevron-right" class="w-3 h-3" aria-hidden="true" />
        </x-sn-cms::container.block-link>
    @endif
</header>
