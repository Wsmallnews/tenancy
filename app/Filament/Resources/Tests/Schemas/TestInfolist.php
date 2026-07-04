<?php

namespace App\Filament\Resources\Tests\Schemas;

use Filament\Infolists;
use Filament\Schemas\Schema;

class TestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Infolists\Components\TextEntry::make('name')
                    ->label('单位名称'),
                Infolists\Components\TextEntry::make('code')
                    ->label('单位编号'),
                Infolists\Components\TextEntry::make('contact')
                    ->label('联系人'),
                Infolists\Components\TextEntry::make('contact_phone')
                    ->label('联系人电话'),
                Infolists\Components\TextEntry::make('email')
                    ->label('邮箱'),
                Infolists\Components\TextEntry::make('district')
                    ->label('单位所在地区')
                    ->formatStateUsing(function ($record) {
                        dd($record);
                        return $record ? $record->province_name . '/' . $record->city_name . '/' . $record->district_name : '';
                    }),
                Infolists\Components\TextEntry::make('address')
                    ->label('地址'),
                Infolists\Components\TextEntry::make('created_at')
                    ->label('创建时间')
                    ->dateTime(),
            ]);
    }
}
