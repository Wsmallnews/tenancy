<?php

namespace App\Filament\Resources\Patents\Pages;

use App\Filament\Resources\Patents\PatentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPatents extends ListRecords
{
    protected static string $resource = PatentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
