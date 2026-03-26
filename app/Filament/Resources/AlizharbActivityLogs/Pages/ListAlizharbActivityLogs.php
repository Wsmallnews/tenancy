<?php

namespace App\Filament\Resources\AlizharbActivityLogs\Pages;

use App\Filament\Resources\AlizharbActivityLogs\AlizharbActivityLogResource;
use Filament\Resources\Pages\ListRecords;

class ListAlizharbActivityLogs extends ListRecords
{
    protected static string $resource = AlizharbActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}