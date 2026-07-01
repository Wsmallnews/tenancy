<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * 保存前，重新组装 options 字段,填充对应的 省市区字段
     *
     * @param array $data
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_type'] = 'admin';       // 租户管理员

        return $data;
    }
}
