<?php

namespace App\Filament\Resources\AppraiseApplies\Pages;

use App\Filament\Resources\AppraiseApplies\AppraiseApplyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppraiseApply extends EditRecord
{
    protected static string $resource = AppraiseApplyResource::class;

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