<?php

namespace App\Filament\Resources\AccurateIdentifies\Pages;

use App\Filament\Resources\AccurateIdentifies\AccurateIdentifyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAccurateIdentifies extends ListRecords
{
    protected static string $resource = AccurateIdentifyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
