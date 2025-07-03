@php
    $breadcrumbs = [];

    foreach ($parents as $parent) {
        $breadcrumbs[$parent->url_info['url']] = $parent->name;
    }
@endphp

@push('seo')
    {!! seo()->for($navigation) !!}
@endpush

<div class="w-full" x-data>
    <livewire:sn-components-navigation />

    <div class="container mx-auto flex flex-col gap-4">
        @if ($navigation->getFirstMediaUrl('banner'))
            <div class="w-full relative">
                <img src="{{ $navigation->getFirstMediaUrl('banner') }}" class="w-full">

                <div class="absolute top-0 left-0 w-full h-full">
                    <div class="max-w-[1200px] h-full mx-auto relative flex justify-center items-center">
                        <div
                            class="leading-[60px] text-white text-[60px] font-bold inline-block border-b-[4px] border-white pb-[30px]">
                            {{ $navigation->name }}
                        </div>

                        <div class="w-full absolute bottom-6 right-0 text-white text-base text-right">
                            {{ __('frontend.navigation.current_position') }}：
                            <x-filament::breadcrumbs :breadcrumbs="$breadcrumbs" />
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex flex-col md:flex-row items-start gap-4">
            @if ($brothers->isNotEmpty()) 
                <ul class="flex flex-col w-full md:w-72 shrink-0 bg-primary-500">
                    @foreach ($brothers as $brother)
                        <li class="flex">
                            <a class="flex flex-grow px-4 py-4 font-bold text-white focus:underline"
                                {{ \Filament\Support\generate_href_html($brother->url_info['url'], $brother->url_info['target'] ?? '_self') }} 
                                aria-current="page"
                            >
                                {{ $navigation->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="flex flex-col grow gap-4">
                @foreach ($components as $component_name => $params)
                    @livewire($component_name, $params, key($component_name . '-' . $loop->index))
                @endforeach
            </div>
        </div>
    </div>

    <livewire:sn-components-footer />
</div>

@assets
    <script>
        function index({

        }) {
            return {
                init() {
                    this.pageInit()
                },
                toJump(route) {
                    Livewire.navigate(route)
                },
                pageInit() {
                    // 初始化首页轮播
                    const swiper = new Swiper('.first-screen-swiper', {
                        loop: true,
                        slidesPerView: 1,
                        spaceBetween: 0,
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        effect: 'slide',
                        speed: 800,
                        grabCursor: true,
                        keyboard: {
                            enabled: true,
                        },
                        mousewheel: {
                            enabled: false,
                        },
                        allowTouchMove: true,
                        touchRatio: 1,
                        touchAngle: 45,
                        resistance: true,
                        resistanceRatio: 0.85,
                        observer: true,
                        observeParents: true,
                        autoplay: {
                            delay: 3000, // 设置自动切换的时间间隔，单位为毫秒
                            stopOnLastSlide: false, // 设置为true时，在最后一个Slide处停止自动轮播
                        },
                    });
                    // 监听窗口大小变化
                    window.addEventListener('resize', function() {
                        swiper.update();
                    });
                }
            }
        }
    </script>

@endassets
