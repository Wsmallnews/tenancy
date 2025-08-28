<?php

namespace App\Filament\Resources\Preserves\Pages;

use App\Filament\Resources\Preserves\PreserveResource;
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
