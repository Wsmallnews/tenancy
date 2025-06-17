<?php

namespace App\Filament\Resources\AssembleResource\Pages;

use App\Filament\Resources\AssembleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAssemble extends CreateRecord
{
    protected static string $resource = AssembleResource::class;

    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
