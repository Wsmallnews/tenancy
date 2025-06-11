<?php

namespace App\Filament\Resources\AppraiseResource\Pages;

use App\Filament\Resources\AppraiseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAppraise extends CreateRecord
{
    protected static string $resource = AppraiseResource::class;


    /**
     * 保存前，重新组装 options 字段,填充对应的 省市区字段
     *
     * @param array $data
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 修改数据
        $data['options'] = static::getResource()::getFieldsInfo($data);

        // 修改省市信息
        $data = static::getResource()::operDistrictInfo($data);

        return $data;
    }


    /**
     * 保存后，更新排序字段
     *
     * @param Model $record
     * @return void
     */
    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
