<?php

namespace App\Filament\Resources\Teams\Pages;

use App\Filament\Resources\Teams\RelationManagers\UsersRelationManager;
use App\Filament\Resources\Teams\TeamResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTeam extends ViewRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }


    public function getAllRelationManagers(): array
    {
        return [
            'users' => UsersRelationManager::class,
        ];
    }
}
