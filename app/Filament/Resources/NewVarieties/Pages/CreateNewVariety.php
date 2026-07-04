<?php

namespace App\Filament\Resources\NewVarieties\Pages;

use App\Filament\Resources\NewVarieties\NewVarietyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNewVariety extends CreateRecord
{
    protected static string $resource = NewVarietyResource::class;

    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
