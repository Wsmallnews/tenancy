<?php

namespace App\Filament\Resources\NewVarietyResource\Pages;

use App\Filament\Resources\NewVarietyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewVariety extends EditRecord
{
    protected static string $resource = NewVarietyResource::class;

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
