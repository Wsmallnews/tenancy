<?php

namespace App\Filament\Resources\AppraiseApplies\Pages;

use App\Filament\Resources\AppraiseApplies\AppraiseApplyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppraiseApplies extends ListRecords
{
    protected static string $resource = AppraiseApplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}