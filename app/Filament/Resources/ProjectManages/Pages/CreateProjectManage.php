<?php

namespace App\Filament\Resources\ProjectManages\Pages;

use App\Filament\Resources\ProjectManages\ProjectManageResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProjectManage extends CreateRecord
{
    protected static string $resource = ProjectManageResource::class;

    /**
     * 保存后，更新排序字段
     *
     * @param  Model  $record
     * @return void
     */
    protected function afterCreate()
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
