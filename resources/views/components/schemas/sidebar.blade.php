@props([])

<div class="max-w-60 min-w-44 sticky top-0 index-1000">
    {{ $getChildSchema() }}
    {{ $getChildSchema() }}
    {{ $getChildSchema() }}
    {{ $getChildSchema() }}
</div>

@assets
<script>
    // const targetUrl = new URL(window.location.href);
    // const hash = targetUrl.hash;
    
    // if (hash) {
    //     setTimeout(() => {
    //         const targetElement = document.querySelector('#item-' + hash.replace('#', ''));
    //         if (targetElement) {
    //             // 滚动到该元素位置
    //             targetElement.scrollIntoView({
    //                 behavior: 'smooth' // 可选：平滑滚动
    //             });
    //         }
    //     }, 300); // 300ms 的延迟通常足够
    // }
    setTimeout(() => {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.getElementById(targetId.slice(1));
                if (targetElement) {
                    const offsetTop = targetElement.offsetTop; // 获取元素距离顶部的距离
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }, 3000)
</script>

@endassets