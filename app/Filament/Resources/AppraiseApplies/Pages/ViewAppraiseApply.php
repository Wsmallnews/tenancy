<?php

namespace App\Filament\Resources\AppraiseApplies\Pages;

use App\Filament\Resources\AppraiseApplies\AppraiseApplyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAppraiseApply extends ViewRecord
{
    protected static string $resource = AppraiseApplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}