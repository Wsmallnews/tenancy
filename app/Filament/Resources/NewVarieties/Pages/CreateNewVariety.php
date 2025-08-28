<?php

namespace App\Filament\Resources\NewVarieties\Pages;

use App\Filament\Resources\NewVarieties\NewVarietyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateNewVariety extends CreateRecord
{
    protected static string $resource = NewVarietyResource::class;


    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
