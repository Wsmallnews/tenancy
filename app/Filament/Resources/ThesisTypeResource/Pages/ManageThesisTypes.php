<?php

namespace App\Filament\Resources\ThesisTypeResource\Pages;

use App\Filament\Resources\ThesisTypeResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManageThesisTypes extends ManageRecords
{
    protected static string $resource = ThesisTypeResource::class;

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
