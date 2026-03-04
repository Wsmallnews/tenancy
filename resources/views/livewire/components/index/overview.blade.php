<section class="py-10">
    <div class="flex flex-col items-center justify-center mb-12">
        <h1 class="sn-h1-text shrink-0 relative mb-4">
            概况
            <span class="sn-primary-bg absolute bottom-0 left-0 w-full h-1 rounded-full transform translate-y-2"></span>
        </h1>
        <p class="sn-content-text max-w-3xl mx-auto">
            丰富的种质资源，优秀的科研团队
        </p>
    </div>

    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($stats as $stat)
            <div class="sn-container sn-hover sn-link p-4 flex flex-col items-center justify-center gap-4 aspect-video group">
                <div class="sn-primary-text w-20 h-20 flex items-center justify-center rounded-full bg-primary-200 group-hover:scale-110 transition-transform">
                    @if (isset($stat['icon']))
                        <x-filament::icon :icon="$stat['icon']" class="w-12 h-12" />
                    @else
                        <x-filament::icon icon="heroicon-o-chart-bar" class="w-12 h-12" />
                    @endif
                </div>
                <span class="sn-h2-text sn-hover">{{ $stat['value'] }}</span>
                <span class="sn-content-text">{{ $stat['title'] }}</span>
            </div>
        @endforeach
    </div>
</section>