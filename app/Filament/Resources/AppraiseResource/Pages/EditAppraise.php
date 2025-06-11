<?php

namespace App\Filament\Resources\AppraiseResource\Pages;

use App\Filament\Resources\AppraiseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppraise extends EditRecord
{
    protected static string $resource = AppraiseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        // 修改数据
        $data['options'] = AppraiseResource::getFieldsInfo($data);

        // 修改省市信息
        $data = AppraiseResource::operDistrictInfo($data);

        return $data;
    }
}
