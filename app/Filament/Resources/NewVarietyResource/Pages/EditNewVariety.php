<?php

namespace App\Filament\Resources\NewVarietyResource\Pages;

use App\Filament\Resources\NewVarietyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewVariety extends EditRecord
{
    protected static string $resource = NewVarietyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
