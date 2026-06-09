// 表格操作列悬浮阴影：当表格出现水平滚动时，给操作列添加阴影以区分数据列
(function () {
    function init() {
        document.querySelectorAll('.fi-ta-table').forEach(function (table) {
            if (table._scrollableObserved) return;
            table._scrollableObserved = true;

            var container = table.closest('.fi-ta-content-ctn');
            if (!container) return;

            var check = function () {
                if (container.scrollWidth > container.clientWidth) {
                    table.classList.add('is-scrollable');
                } else {
                    table.classList.remove('is-scrollable');
                }
            };

            var obs = new ResizeObserver(check);
            obs.observe(table);
            obs.observe(container);
            check();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    document.addEventListener('livewire:navigated', init);
})();
