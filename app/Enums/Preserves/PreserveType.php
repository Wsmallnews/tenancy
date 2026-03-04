<?php

namespace App\Enums\Preserves;

use Filament\Support\Contracts\HasLabel;
use App\Enums\Traits\EnumHelper;

enum PreserveType: string implements HasLabel
{

    use EnumHelper;

    case GermplasmNursery = 'germplasm_nursery';

    case TestTubeSeedling = 'test_tube_seedling';

    case UltraLowTemperature = 'ultra_low_temperature';

    case OriginalHabitat = 'original_habitat';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::GermplasmNursery => '种质圃保存',
            self::TestTubeSeedling => '试管苗保存',
            self::UltraLowTemperature => '超低温保存',
            self::OriginalHabitat => '原生境保存',
        };
    }
}
