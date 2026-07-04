<?php

namespace App\Filament\Resources\PatentTypes\Pages;

use App\Filament\Resources\PatentTypes\PatentTypeResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManagePatentTypes extends ManageRecords
{
    protected static string $resource = PatentTypeResource::class;

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
