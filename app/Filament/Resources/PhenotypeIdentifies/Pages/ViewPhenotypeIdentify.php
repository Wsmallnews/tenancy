<?php

namespace App\Filament\Resources\PhenotypeIdentifies\Pages;

use App\Filament\Resources\PhenotypeIdentifies\PhenotypeIdentifyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPhenotypeIdentify extends ViewRecord
{
    protected static string $resource = PhenotypeIdentifyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
