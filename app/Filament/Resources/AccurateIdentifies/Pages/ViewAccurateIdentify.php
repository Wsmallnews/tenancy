<?php

namespace App\Filament\Resources\AccurateIdentifies\Pages;

use App\Filament\Resources\AccurateIdentifies\AccurateIdentifyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAccurateIdentify extends ViewRecord
{
    protected static string $resource = AccurateIdentifyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
