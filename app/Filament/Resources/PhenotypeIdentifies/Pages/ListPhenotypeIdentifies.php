<?php

namespace App\Filament\Resources\PhenotypeIdentifies\Pages;

use App\Filament\Resources\PhenotypeIdentifies\PhenotypeIdentifyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPhenotypeIdentifies extends ListRecords
{
    protected static string $resource = PhenotypeIdentifyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
