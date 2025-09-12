<?php

namespace App\Filament\Resources\Appraises\Pages;

use App\Filament\Resources\Appraises\AppraiseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAppraise extends ViewRecord
{
    protected static string $resource = AppraiseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
