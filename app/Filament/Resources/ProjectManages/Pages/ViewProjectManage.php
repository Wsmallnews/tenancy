<?php

namespace App\Filament\Resources\ProjectManages\Pages;

use App\Filament\Resources\ProjectManages\ProjectManageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectManage extends ViewRecord
{
    protected static string $resource = ProjectManageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
