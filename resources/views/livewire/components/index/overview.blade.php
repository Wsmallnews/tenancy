<div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 py-10 gap-4">
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