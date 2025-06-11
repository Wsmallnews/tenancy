@php
    use Filament\Forms\Components\Actions\Action;

    $id = $getId();
    $hasCity = $hasCity();
    $hasDistrict = $hasDistrict();
    $isDisabled = $isDisabled();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixLabel = $getSuffixLabel();
    $options = $getOptions();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="w-full relative"
        x-data="districtSelectManager({
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            options: @js($options),
            hasCity: @js($hasCity),
            hasDistrict: @js($hasDistrict)
        })"
        x-on:keydown.esc.window="isOpened = false, openedWithKeyboard = false"
    >
        <x-filament::input.wrapper
            :disabled="$isDisabled"
            :inline-prefix="$isPrefixInline"
            :inline-suffix="$isSuffixInline"
            :prefix="$prefixLabel"
            :prefix-actions="$prefixActions"
            :prefix-icon="$prefixIcon"
            :prefix-icon-color="$getPrefixIconColor()"
            :suffix="$suffixLabel"
            :suffix-actions="$suffixActions"
            :suffix-icon="$suffixIcon"
            :suffix-icon-color="$getSuffixIconColor()"
            :valid="! $errors->has($statePath)"
            :attributes="
                \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                    ->class(['sn-support-district-select'])
            "
        >
            <x-filament::input
                :attributes="
                    \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                        ->merge($getExtraAlpineAttributes(), escape: false)
                        ->merge([
                            'disabled' => $isDisabled,
                            'id' => $id,
                            'inlinePrefix' => $isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel)),
                            'inlineSuffix' => $isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel)),
                            'placeholder' => $getPlaceholder(),
                            'readonly' => true,
                            'required' => $isRequired() && (! $isConcealed),
                            'type' => 'text',
                            'x-bind:value' => 'showVal',
                            'x-on:click' => 'isOpened = ! isOpened',
                        ], escape: false)
                        ->class([])
                "
            />
        </x-filament::input.wrapper>

        <div class="absolute top-11 flex w-full z-10 flex-col overflow-hidden rounded-md bg-white dark:bg-gray-900 ring-2 ring-primary-600"
            x-cloak 
            x-show="isOpened || openedWithKeyboard"
            x-transition 
            x-trap="openedWithKeyboard"
            x-on:click.outside="isOpened = false, openedWithKeyboard = false" 
            x-on:keydown.down.prevent="$focus.wrap().next()" 
            x-on:keydown.up.prevent="$focus.wrap().previous()"
        >

            <x-filament::tabs label="Content tabs" :contained="true" class="bg-white px-4">
                <x-filament::tabs.item tag="a" @click="switchTab('province')" alpine-active="currentTab == 'province'">
                    省
                </x-filament::tabs.item>

                @if ($hasCity)
                <x-filament::tabs.item tag="a" @click="switchTab('city')" alpine-active="currentTab == 'city'">
                    市
                </x-filament::tabs.item>
                @endif

                @if($hasDistrict)
                <x-filament::tabs.item tag="a" @click="switchTab('district')" alpine-active="currentTab == 'district'">
                    区/县
                </x-filament::tabs.item>
                @endif
            </x-filament::tabs>

            <div class="w-full h-72">
                <template x-if="currentTab == 'province'">
                    <ul class="w-full h-full overflow-auto ">
                        <template x-for="(province, provinceIndex) in options">
                            <li @click="chooseProvince(province)" class="flex items-center justify-start py-2 px-4 text-sm hover:text-primary-600 hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer"
                                :class="choosedProvince?.id == province.id ? 'text-primary-600 bg-gray-50 dark:bg-white/5' : 'text-gray-950 dark:text-white dark:bg-white/3'">
                                <span x-text="province.name"></span>
                            </li>
                        </template>
                    </ul>
                </template>

                @if ($hasCity)
                    <template x-if="currentTab == 'city' && choosedProvince">
                        <ul class="w-full h-full overflow-auto">
                            <template x-for="(city, cityIndex) in choosedProvince.children">
                                <li @click="chooseCity(city)"  class="flex items-center justify-start py-2 px-4 text-sm hover:text-primary-600 hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer"
                                    :class="choosedCity?.id == city.id ? 'text-primary-600 bg-gray-50 dark:bg-white/5' : 'text-gray-950 dark:text-white dark:bg-white/3'">
                                    <span x-text="city.name"></span>
                                </li>
                            </template>
                        </ul>
                    </template>
                @endif

                @if($hasDistrict)
                    <template x-if="currentTab == 'district' && choosedCity">
                        <ul class="w-full h-full overflow-auto">
                            <template x-for="(district, districtIndex) in choosedCity.children">
                                <li @click="chooseDistrict(district)" class="flex items-center justify-start py-2 px-4 text-sm hover:text-primary-600 hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer"
                                    :class="choosedDistrict?.id == district.id ? 'text-primary-600 bg-gray-50 dark:bg-white/5' : 'text-gray-950 dark:text-white dark:bg-white/3'">
                                    <span x-text="district.name"></span>
                                </li>
                            </template>
                        </ul>
                    </template>
                @endif
            </div>
        </div>
    </div>
</x-dynamic-component>

@assets
<script>
    function districtSelectManager({
        state,
        options,
        hasCity,
        hasDistrict
    }) {
        return {
            state,
            options,
            hasCity,
            hasDistrict,
            isOpened: false,
            openedWithKeyboard: false,
            currentTab: 'province',
            choosedProvince: null,
            choosedCity: null,
            choosedDistrict: null,
            init () {
                let province_id = this.state?.province_id || null;
                let city_id = this.state?.city_id || null;
                let district_id = this.state?.district_id || null;

                if (province_id) {
                    this.choosedProvince = options.find(item => item.id == province_id) || null;
                    this.updateState();
                }

                if (this.hasCity && city_id && this.choosedProvince) {
                    this.choosedCity = this.choosedProvince.children?.find(item => item.id == city_id) || null;
                    this.updateState();
                }
                
                if (this.hasDistrict && district_id && this.choosedCity) {
                    this.choosedDistrict = this.choosedCity.children.find(item => item.id == district_id) || null;
                    this.updateState();
                }
            },
            get showVal() {
                let val = '';
                if (this.choosedProvince) {
                    val += this.choosedProvince.name;
                    val += this.hasCity ? ' / ' : '';
                }

                if (this.choosedProvince && this.choosedCity) {
                    val += this.choosedCity.name;
                    val += this.hasDistrict ? ' / ' : '';
                }

                if (this.choosedProvince && this.choosedCity && this.choosedDistrict) {
                    val += this.choosedDistrict.name;
                }

                return val;
            },
            switchTab(tab) {
                console.log(tab, 'tab');
                if (tab == 'district' && this.choosedCity) {
                    this.currentTab = tab;
                    return;
                }

                if (tab == 'city' && this.choosedProvince) {
                    console.log(tab, 'comein city');
                    this.currentTab = tab;
                    return;
                }

                if (tab == 'province') {
                    this.currentTab = tab;
                    return;
                }
            },
            chooseProvince(province) {
                this.choosedProvince = province;
                this.choosedCity = null;
                this.choosedDistrict = null;

                if (this.hasCity) {
                    this.currentTab = 'city';
                } else {
                    this.chooseOk();
                }
            },
            chooseCity(city) {
                this.choosedCity = city;
                this.choosedDistrict = null;

                if (this.hasDistrict) {
                    this.currentTab = 'district';
                } else {
                    this.chooseOk();
                }
            },
            chooseDistrict(district) {
                this.choosedDistrict = district;
                this.chooseOk();
            },
            chooseOk() {
                this.isOpened = false;
                this.openedWithKeyboard = false;

                this.updateState();
            },
            updateState () {
                let state = {}

                state['province_name'] = this.choosedProvince?.name;
                state['province_id'] = this.choosedProvince?.id;

                state['city_name'] = this.choosedCity?.name;
                state['city_id'] = this.choosedCity?.id;

                state['district_name'] = this.choosedDistrict?.name;
                state['district_id'] = this.choosedDistrict?.id;
                
                this.state = state
            },
        }
    }
</script>
@endassets