<?php

namespace App\Filament\Forms\Fields;

use Closure;
use Filament\Forms\Components\Concerns\CanBeDisabled;
use Filament\Forms\Components\Concerns\CanBeSearchable;
use Filament\Forms\Components\Concerns\HasActions;
use Filament\Forms\Components\Concerns\HasAffixes;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasOptions;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Contracts\HasAffixActions;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Illuminate\Database\Eloquent\Model;
use App\Features\District;

class DistrictSelect extends Field implements HasAffixActions
{
    use CanBeDisabled;
    use CanBeSearchable;
    use HasActions;
    use HasAffixes;
    use HasExtraAlpineAttributes;
    use HasExtraInputAttributes;
    use HasOptions;
    use HasPlaceholder;

    protected string $view = 'forms.fields.district-select';

    protected bool | Closure $hasCity = true;

    protected bool | Closure $hasDistrict = true;

    protected function setUp(): void
    {
        $this->options(function () {
            $districtData = (new District)->getCascader();

            return is_array($districtData) ? $districtData : json_decode($districtData, true);
        });

        // 从 model 中获取数据
        $this->afterStateHydrated(function (DistrictSelect $component, ?array $state) {
            $record = $component->getRecord();

            if (! $record) {
                $component->state($state);

                return;
            }

            $component->state([
                'province_name' => $record->province_name ?? null,
                'province_id' => $record->province_id ?? null,
                'city_name' => $record->city_name ?? null,
                'city_id' => $record->city_id ?? null,
                'district_name' => $record->district_name ?? null,
                'district_id' => $record->district_id ?? null,
            ]);
        });
    }


    public function city(bool | Closure $condition = true): static
    {
        $this->hasCity = $condition;

        return $this;
    }


    public function hasCity(): bool
    {
        return (bool) $this->evaluate($this->hasCity);
    }


    public function district(bool | Closure $condition = true): static
    {
        $this->hasDistrict = $condition;

        return $this;
    }


    public function hasDistrict(): bool
    {
        return (bool) ($this->evaluate($this->hasCity) && $this->evaluate($this->hasDistrict));
    }
}
