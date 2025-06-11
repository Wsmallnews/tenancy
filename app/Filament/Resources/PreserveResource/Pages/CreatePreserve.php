<?php

namespace App\Filament\Resources\PreserveResource\Pages;

use App\Filament\Resources\PreserveResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePreserve extends CreateRecord
{
    protected static string $resource = PreserveResource::class;

    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
