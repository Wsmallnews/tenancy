<?php

namespace App\Filament\Resources\AccurateIdentifies\Pages;

use App\Filament\Resources\AccurateIdentifies\AccurateIdentifyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccurateIdentify extends EditRecord
{
    protected static string $resource = AccurateIdentifyResource::class;

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
