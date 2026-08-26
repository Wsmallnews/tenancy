<section class="w-full pt-6" aria-label="{{ __('数据概况') }}">
    <div class="w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        @foreach ($stats as $stat)
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-md p-6 flex items-center gap-5">
                <span class="w-14 h-14 rounded-xl bg-primary-500/10 dark:bg-primary-400/10 text-primary-600 dark:text-primary-400 flex items-center justify-center shrink-0">
                    @if (isset($stat['icon']))
                        <x-filament::icon :icon="$stat['icon']" class="w-7 h-7" aria-hidden="true" />
                    @else
                        <x-filament::icon icon="heroicon-o-chart-bar" class="w-7 h-7" aria-hidden="true" />
                    @endif
                </span>

                <div class="flex flex-col gap-1 min-w-0">
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $stat['title'] }}</span>
                    <span class="text-2xl font-bold leading-8 text-primary-600 dark:text-primary-400 tabular-nums" aria-label="{{ $stat['title'] }}">
                        {{ number_format($stat['value']) }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</section>
