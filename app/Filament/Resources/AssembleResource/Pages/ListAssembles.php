<?php

namespace App\Filament\Resources\AssembleResource\Pages;

use App\Filament\Resources\AssembleResource;
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
