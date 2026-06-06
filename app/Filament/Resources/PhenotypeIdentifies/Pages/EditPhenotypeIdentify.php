<?php

namespace App\Filament\Resources\PhenotypeIdentifies\Pages;

use App\Filament\Resources\Concerns\HasCategoryFields;
use App\Filament\Resources\PhenotypeIdentifies\PhenotypeIdentifyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPhenotypeIdentify extends EditRecord
{
    use HasCategoryFields;

    protected static string $resource = PhenotypeIdentifyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // 重组自定义字段数据
        $data['options'] = static::getFieldsInfo($data);

        return $data;
    }
}
