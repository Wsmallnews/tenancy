@php
    use Wsmallnews\Cms\Support\Utils as CmsUtils;
    $scopeType = CmsUtils::getScopeType();
    $scopeId = CmsUtils::getScopeId();
@endphp

<x-dynamic-component :component="$this->getPageContainer()" :scope-type="$scopeType" :scope-id="$scopeId">
    <div class="container mx-auto flex flex-col grow gap-4 my-4">
        @if($breadcrumbs)
            <div class="sn-descript-text w-full flex items-center gap-2 text-left">
                当前位置 :
                <x-sn-support::breadcrumbs :breadcrumbs="$breadcrumbs" />
            </div>
        @endif

        <div class="w-full flex flex-col md:flex-row items-start gap-4">
            <div class="w-full md:w-72" >
                <livewire:sn-user-components-user-sidebar-menu :module="app(\Wsmallnews\User\UserPlugin::class)->getId()" />
            </div>

            <div class="w-full">
                <livewire:sn-components-user-appraise-apply class="w-full" :id="$id" key="{{ 'appraises-' . $id }}" />
            </div>
        </div>
    </div>
</x-dynamic-component>
