<?php

namespace App\Filament\Resources\Patents\Pages;

use App\Filament\Resources\Patents\PatentResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatent extends CreateRecord
{
    protected static string $resource = PatentResource::class;

    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
