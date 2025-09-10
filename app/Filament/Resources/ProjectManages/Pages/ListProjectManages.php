<?php

namespace App\Filament\Resources\ProjectManages\Pages;

use App\Filament\Resources\ProjectManages\ProjectManageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectManages extends ListRecords
{
    protected static string $resource = ProjectManageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
