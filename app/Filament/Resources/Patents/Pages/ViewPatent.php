<?php

namespace App\Filament\Resources\Patents\Pages;

use App\Filament\Resources\Patents\PatentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPatent extends ViewRecord
{
    protected static string $resource = PatentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
