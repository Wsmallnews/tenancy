<?php

namespace App\Filament\Resources\Shares\Pages;

use App\Filament\Resources\Shares\ShareResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateShare extends CreateRecord
{
    protected static string $resource = ShareResource::class;


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
