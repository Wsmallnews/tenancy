<?php

namespace App\Filament\Resources\Appraises\Pages;

use App\Filament\Resources\Appraises\AppraiseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppraises extends ListRecords
{
    protected static string $resource = AppraiseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
