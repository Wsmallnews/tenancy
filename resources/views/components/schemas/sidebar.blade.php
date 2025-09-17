<div class="sn-page-sidebar w-full md:max-w-60 min-w-44 rounded-md md:rounded-none bg-white md:bg-transparent shadow-sm md:shadow-none ring-1 md:ring-0 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" x-data="sidebarManager({})">
    {{ $getChildSchema() }}
</div>

@assets
<script>
    function sidebarManager({}) {
        return {
            currentTab: null,
            init () {
                // 监听滚动条
                window.addEventListener('scroll', () => {
                    setTimeout(() => {
                        const tabContents = document.querySelectorAll('.sn-page-sidebar-content-item');
                        tabContents.forEach(tabContent => {
                            const id = tabContent.getAttribute('id');
                            const rect = tabContent.getBoundingClientRect();
    
                            if (rect.top <= 100 && rect.bottom >= 100) {
                                console.log(id, 'scroll to')
                                this.currentTab = id;
                            }
                        });
                    }, 100)
                });
            },
            swithSidebar (id) {
                // this.currentTab = id;
            }
        }
    }
</script>
@endassets