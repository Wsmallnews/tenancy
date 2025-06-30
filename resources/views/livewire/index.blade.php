@php
    // 这里可以添加 PHP 代码
@endphp

@push('seo')
    {!! seo() !!}
@endpush

<div x-data="index({})">
    <div class="pc-home" id="fullpage">
        <livewire:sn-components-navigation />
        <div class="pc-page" id="slide1">
            <div class="swiper first-screen-swiper first-screen-swiper-css">
                <div class="swiper-wrapper">
                    {{-- @foreach ($indexBlocks as $block) 
                        @if ($block->getFirstMediaUrl('main'))
                            <div class="swiper-slide">
                                <div class="slide-content">
                                    <img src="{{ $block->getFirstMediaUrl('main') }}" class="w-full h-full banner-img" />
                                </div>
                            </div>
                        @endif
                    @endforeach --}}
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <livewire:sn-components-footer />
    </div>
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
