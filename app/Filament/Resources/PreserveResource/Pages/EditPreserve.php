<?php

namespace App\Filament\Resources\PreserveResource\Pages;

use App\Filament\Resources\PreserveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreserve extends EditRecord
{
    protected static string $resource = PreserveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
