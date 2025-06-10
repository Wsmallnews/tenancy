<?php

namespace App\Filament\Resources\PreserveResource\Pages;

use App\Filament\Resources\PreserveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreserves extends ListRecords
{
    protected static string $resource = PreserveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
