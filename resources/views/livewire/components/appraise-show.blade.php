@php
    $view = $this->getThemeView('components.category.categories');
    $recordView = $this->getBladeThemeView('components.category.category-record');
@endphp

<x-dynamic-component :component="$wrapperView" class="w-full flex flex-col lg:flex-row gap-4">
    <div class="w-full lg:w-72" >
        <livewire:sn-category-components-categories :category-id="$categoryId" scope-type="appraise" :view="$view" :record-view="$recordView" />
    </div>

    <livewire:sn-components-appraises class="w-full" :style="$style" :category-ids="$categoryId" key="{{ 'appraises-' . $categoryId }}" />
</x-dynamic-component>