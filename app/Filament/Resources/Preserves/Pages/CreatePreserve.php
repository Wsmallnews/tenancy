<?php

namespace App\Filament\Resources\Preserves\Pages;

use App\Filament\Resources\Preserves\PreserveResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePreserve extends CreateRecord
{
    protected static string $resource = PreserveResource::class;

    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
