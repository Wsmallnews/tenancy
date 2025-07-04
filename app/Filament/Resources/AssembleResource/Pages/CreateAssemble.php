<?php

namespace App\Filament\Resources\AssembleResource\Pages;

use App\Filament\Resources\AssembleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAssemble extends CreateRecord
{
    protected static string $resource = AssembleResource::class;


    /**
     * 保存前，重新组装 options 字段,填充对应的 省市区字段
     *
     * @param array $data
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 修改省市信息
        $data = static::getResource()::operDistrictInfo($data);
        return $data;
    }


    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
