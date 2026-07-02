<?php

namespace App\Filament\Resources\Theses\Pages;

use App\Filament\Resources\Theses\ThesisResource;
use Filament\Resources\Pages\CreateRecord;

class CreateThesis extends CreateRecord
{
    protected static string $resource = ThesisResource::class;

    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
