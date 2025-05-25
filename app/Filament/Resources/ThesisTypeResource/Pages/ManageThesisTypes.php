<?php

namespace App\Filament\Resources\ThesisTypeResource\Pages;

use App\Filament\Resources\ThesisTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageThesisTypes extends ManageRecords
{
    protected static string $resource = ThesisTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
