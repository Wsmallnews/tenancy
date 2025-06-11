<?php

namespace App\Filament\Resources\PreserveResource\Pages;

use App\Filament\Resources\PreserveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreserve extends EditRecord
{
    protected static string $resource = PreserveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }


    /**
     * 填充数据前，填充 关联 字段
     *
     * @param array $data
     * @return array
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = static::getResource()::fillAppraiseInfo($data);

        return $data;
    }
}
