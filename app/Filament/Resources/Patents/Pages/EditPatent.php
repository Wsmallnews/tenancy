<?php

namespace App\Filament\Resources\Patents\Pages;

use App\Filament\Resources\Patents\PatentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPatent extends EditRecord
{
    protected static string $resource = PatentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
