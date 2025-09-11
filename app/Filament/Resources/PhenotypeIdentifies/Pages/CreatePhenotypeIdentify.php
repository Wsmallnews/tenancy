<?php

namespace App\Filament\Resources\PhenotypeIdentifies\Pages;

use App\Filament\Resources\PhenotypeIdentifies\PhenotypeIdentifyResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePhenotypeIdentify extends CreateRecord
{
    protected static string $resource = PhenotypeIdentifyResource::class;


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
