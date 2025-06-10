<?php

namespace App\Filament\Resources\AppraiseResource\Pages;

use App\Filament\Resources\AppraiseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAppraise extends CreateRecord
{
    protected static string $resource = AppraiseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 修改数据
        $data['options'] = AppraiseResource::getFieldsInfo($data);

        return $data;
    }

    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
