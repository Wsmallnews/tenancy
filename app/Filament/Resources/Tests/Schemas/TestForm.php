<?php

namespace App\Filament\Resources\Tests\Schemas;

use App\Enums\Companies\Status;
use App\Filament\Forms\Fields\DistrictSelect;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;

class TestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Section::make('单位信息')->schema([
                            Forms\Components\TextInput::make('name')->label('单位名称')
                                ->placeholder('请输入单位名称')
                                ->required(),
                            Forms\Components\TextInput::make('code')->label('单位编号')
                                ->placeholder('请输入单位编号')
                                ->required(),
                            Forms\Components\TextInput::make('contact')->label('联系人')
                                ->placeholder('请输入联系人'),
                            Forms\Components\TextInput::make('contact_phone')->label('联系人手机号')
                                ->placeholder('请输入联系人手机号'),
                            Forms\Components\TextInput::make('email')->label('邮箱')
                                ->placeholder('请输入邮箱'),
                            DistrictSelect::make('district')
                                ->label('单位所在地区')
                                ->placeholder('选择单位所在地区'),
                            Forms\Components\TextInput::make('address')->label('单位地址')
                                ->placeholder('请输入单位地址'),
                        ])->columns(2),
                    ])->columns(1),
                    Schemas\Components\Section::make('状态')->schema([
                        Forms\Components\TextInput::make('order_column')->label('排序')->integer()
                            ->placeholder('正序排列')
                            ->rules(['integer', 'min:0']),
                        Forms\Components\Radio::make('status')
                            ->label('状态')
                            ->default(Status::Normal)
                            ->inline()
                            ->options(Status::class),
                    ])->grow(false),
                ])
                    ->columnSpanFull()
                    ->from('lg'),
            ]);
    }
}
