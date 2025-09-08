<?php

namespace App\Filament\Resources\Assembles\Pages;

use App\Filament\Resources\Assembles\AssembleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAssemble extends ViewRecord
{
    protected static string $resource = AssembleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
