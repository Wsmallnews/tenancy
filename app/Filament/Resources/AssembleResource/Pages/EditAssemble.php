<?php

namespace App\Filament\Resources\AssembleResource\Pages;

use App\Filament\Resources\AssembleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssemble extends EditRecord
{
    protected static string $resource = AssembleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }


    /**
     * 保存前，重新组装 options 字段,填充对应的 省市区字段
     *
     * @param array $data
     * @return array
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // 修改省市信息
        $data = static::getResource()::operDistrictInfo($data);

        return $data;
    }
}
