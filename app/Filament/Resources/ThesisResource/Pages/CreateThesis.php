<?php

namespace App\Filament\Resources\ThesisResource\Pages;

use App\Filament\Resources\ThesisResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateThesis extends CreateRecord
{
    protected static string $resource = ThesisResource::class;

    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
