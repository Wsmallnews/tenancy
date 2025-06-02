<?php

namespace App\Filament\Resources\AwardTypeResource\Pages;

use App\Filament\Resources\AwardTypeResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManageAwardTypes extends ManageRecords
{
    protected static string $resource = AwardTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function (Action $action): Model {
                    $record = $action->getRecord();
                    $record->update(['order_column' => $record->id]);
                    return $record;
                }),
        ];
    }
}
