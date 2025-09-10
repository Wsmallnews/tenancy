<?php

namespace App\Filament\Resources\ProjectManages\Pages;

use App\Filament\Resources\ProjectManages\ProjectManageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectManage extends EditRecord
{
    protected static string $resource = ProjectManageResource::class;

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
