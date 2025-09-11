<?php

namespace App\Filament\Resources\PhenotypeIdentifies\Pages;

use App\Filament\Resources\PhenotypeIdentifies\PhenotypeIdentifyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPhenotypeIdentify extends EditRecord
{
    protected static string $resource = PhenotypeIdentifyResource::class;

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
