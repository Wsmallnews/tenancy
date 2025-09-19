<?php

namespace App\Filament\Platform\Resources\Teams\Pages;

use App\Enums\Teams\Status;
use App\Filament\Platform\Resources\Teams\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->label('全部'),
            ...(new Collection(Status::cases()))->mapWithKeys(fn(Status $status) => [
                $status->value => Tab::make()
                    ->label($status->getLabel())
                    ->icon($status->getIcon())
                    ->modifyQueryUsing(fn(Builder $query) => $query->where('status', $status->value)),
            ])->toArray(),
        ];
    }
}
