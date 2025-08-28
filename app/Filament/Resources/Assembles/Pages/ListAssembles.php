<?php

namespace App\Filament\Resources\Assembles\Pages;

use App\Filament\Resources\Assembles\AssembleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssembles extends ListRecords
{
    protected static string $resource = AssembleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
