@php
    use Wsmallnews\Cms\Support\Utils as CmsUtils;
    $scopeType = CmsUtils::getScopeType();
    $scopeId = CmsUtils::getScopeId();
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="container mx-auto flex flex-col grow gap-4 my-4">
        @if($breadcrumbs)
            <div class="w-full flex items-center gap-2 text-sm text-gray-500 text-left">
                当前位置 :
                <x-sn-support::breadcrumbs :breadcrumbs="$breadcrumbs" />
            </div>
        @endif

        <livewire:sn-components-appraise :scope-type="$scopeType" :scope-id="$scopeId" :id="$id" />
    </div>
</x-dynamic-component>