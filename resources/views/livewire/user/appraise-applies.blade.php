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

        <div class="w-full flex flex-col md:flex-row items-start gap-4">
            <div class="w-full md:w-72">
                <livewire:sn-cms-components-user-profile-menu />
            </div>

            <div class="sn-container w-full grow-0 overflow-hidden">
                {{ $this->table }}
            </div>
        </div>
    </div>
</x-dynamic-component>