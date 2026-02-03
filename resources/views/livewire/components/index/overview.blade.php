<section class="py-10">
    <div class="flex flex-col items-center justify-center mb-12">
        <h2 class="shrink-0 text-3xl md:text-4xl font-bold text-slate-900 relative mb-4">
            概况
            <span class="absolute bottom-0 left-0 w-full h-1 bg-primary-500 rounded-full transform translate-y-2"></span>
        </h2>
        <p class="text-lg text-slate-600 max-w-3xl mx-auto">
            丰富的种质资源，优秀的科研团队
        </p>
    </div>

    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($stats as $stat)
            <div class="bg-white rounded-md shadow-sm border border-slate-100 p-4 flex flex-col items-center justify-center gap-4 aspect-16/9 group hover:shadow-lg transition-shadow">
                <div class="w-20 h-20 flex items-center justify-center rounded-full bg-primary-200 text-primary-500 group-hover:scale-110 transition-transform">
                    @if (isset($stat['icon']))
                        <x-filament::icon :icon="$stat['icon']" class="w-12 h-12" />
                    @else
                        <x-filament::icon icon="heroicon-o-chart-bar" class="w-12 h-12" />
                    @endif
                </div>
                <span class="text-2xl font-bold">{{ $stat['value'] }}</span>
                <span class="text-gray-500">{{ $stat['title'] }}</span>
            </div>
        @endforeach
    </div>
</section>