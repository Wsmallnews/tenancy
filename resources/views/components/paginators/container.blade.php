@props([
    'pageType',
    'pageInfo',
    'pageName',
    'paginatorLink',
])

<div class="w-full">
    {{ $slot }}
    <x-paginators :page-type="$pageType" :page-info="$pageInfo" :paginator-link="$paginatorLink" :page-name="$pageName" />
</div>
