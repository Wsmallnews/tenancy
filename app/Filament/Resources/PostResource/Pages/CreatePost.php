<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;


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
