<?php

namespace App\Filament\Resources\PhenotypeIdentifies\Pages;

use App\Filament\Resources\Concerns\HasCategoryFields;
use App\Filament\Resources\PhenotypeIdentifies\PhenotypeIdentifyResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePhenotypeIdentify extends CreateRecord
{
    use HasCategoryFields;

    protected static string $resource = PhenotypeIdentifyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 重组自定义字段数据
        $data['options'] = static::getFieldsInfo($data);

        return $data;
    }

    /**
     * 保存后，更新排序字段
     */
    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $record->update(['order_column' => $record->id]);
    }
}
