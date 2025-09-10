<?php

namespace App\Filament\Resources\Shares\Pages;

use App\Filament\Resources\Shares\ShareResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShare extends EditRecord
{
    protected static string $resource = ShareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
