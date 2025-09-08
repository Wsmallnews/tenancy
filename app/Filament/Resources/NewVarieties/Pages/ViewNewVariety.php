<?php

namespace App\Filament\Resources\NewVarieties\Pages;

use App\Filament\Resources\NewVarieties\NewVarietyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNewVariety extends ViewRecord
{
    protected static string $resource = NewVarietyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
