<?php

namespace App\Filament\Resources\NewVarieties\Pages;

use App\Filament\Resources\NewVarieties\NewVarietyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewVarieties extends ListRecords
{
    protected static string $resource = NewVarietyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
