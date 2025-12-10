<x-dynamic-component :component="$wrapperView" class="w-full flex flex-col lg:flex-row gap-4">
    <div class="w-full lg:w-72" >
        <livewire:sn-category-components-categories :category-id="$categoryId" scope-type="appraise" />
    </div>

    <livewire:sn-components-appraises class="w-full" :category-ids="$categoryId" />
</x-dynamic-component>