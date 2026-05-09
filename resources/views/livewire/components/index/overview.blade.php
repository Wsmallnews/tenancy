<section class="py-12 md:py-16" aria-labelledby="overview-heading">
    <header class="flex flex-col items-center justify-center text-center mb-12">
        <h2 id="overview-heading" class="sn-section-title sn-h1-text mb-6">概况</h2>
        <p class="sn-content-text max-w-3xl mx-auto">
            丰富的种质资源，优秀的科研团队
        </p>
    </header>

    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($stats as $stat)
            <div class="sn-container sn-hover p-6 flex flex-col items-center justify-center gap-4 aspect-video group">
                <div class="sn-primary-text w-20 h-20 flex items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/40 group-hover:bg-primary-200 dark:group-hover:bg-primary-900/60 transition-colors duration-300 motion-reduce:transition-none">
                    @if (isset($stat['icon']))
                        <x-filament::icon :icon="$stat['icon']" class="w-12 h-12" aria-hidden="true" />
                    @else
                        <x-filament::icon icon="heroicon-o-chart-bar" class="w-12 h-12" aria-hidden="true" />
                    @endif
                </div>
                <span class="sn-h2-text" aria-label="{{ $stat['title'] }}">{{ $stat['value'] }}</span>
                <span class="sn-content-text">{{ $stat['title'] }}</span>
            </div>
        @endforeach
    </div>
</section>
