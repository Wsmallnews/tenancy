<?php

namespace App\Filament\Resources\Preserves\Pages;

use App\Filament\Resources\Preserves\PreserveResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPreserve extends ViewRecord
{
    protected static string $resource = PreserveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
