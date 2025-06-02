<?php

namespace App\Filament\Resources\PatentResource\Pages;

use App\Filament\Resources\PatentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePatent extends CreateRecord
{
    protected static string $resource = PatentResource::class;

    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
