@php
    $view = $this->getThemeView('components.category.categories');
    $recordView = $this->getBladeThemeView('components.category.category-record');
@endphp

<div class="w-full flex flex-col lg:flex-row gap-4">
    <div class="w-full lg:w-72" >
        <livewire:sn-category-components-categories scope-type="appraise" :use-url="true" :view="$view" :record-view="$recordView" />
    </div>

    <livewire:sn-components-appraises class="w-full" :style="$style" :category-ids="$categoryId" />
</div>