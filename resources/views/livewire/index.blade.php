@php
    $scopeType = $this->getScopeType();
    $scopeId = $this->getScopeId();
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="container mx-auto px-4 flex flex-col">
        {{-- 首屏：轮播图 + 分类文章列表 --}}
        <livewire:sn-components-index-featured :scope-type="$scopeType" :scope-id="$scopeId" />

        {{-- 数据概况 --}}
        <livewire:sn-components-index-overview :scope-type="$scopeType" :scope-id="$scopeId" />

        {{-- 人才储备 --}}
        <livewire:sn-components-index-personnels :scope-type="$scopeType" :scope-id="$scopeId" />

        {{-- 服务案例 --}}
        <livewire:sn-components-index-posts :scope-type="$scopeType" :scope-id="$scopeId" />

        {{-- 科研成果 --}}
        <livewire:sn-components-index-scientific-research :scope-type="$scopeType" :scope-id="$scopeId" />
    </div>
</x-dynamic-component>
