@push('seo')
    {{-- {!! seo()->for($navigation) !!} --}}
@endpush

<div class="w-full flex flex-col gap-4">
    <livewire:sn-components-navigation />

    <div class="container mx-auto flex flex-col gap-4 p-4 rounded-md bg-white">
        <div class="flex flex-col md:flex-row items-start gap-4">
            {{-- @if ($brothers->isNotEmpty()) 
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
            @endif --}}

            <div class="flex flex-col grow gap-4">
                <livewire:sn-components-post :post="$post" />
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
