<?php

namespace App\Filament\Resources\AccurateIdentifies\Pages;

use App\Filament\Resources\AccurateIdentifies\AccurateIdentifyResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAccurateIdentify extends CreateRecord
{
    protected static string $resource = AccurateIdentifyResource::class;


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
