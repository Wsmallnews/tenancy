<?php

namespace App\Filament\Resources\Theses\Pages;

use App\Filament\Resources\Theses\ThesisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditThesis extends EditRecord
{
    protected static string $resource = ThesisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
