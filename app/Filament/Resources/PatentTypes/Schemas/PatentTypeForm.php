<?php

namespace App\Filament\Resources\PatentTypes\Schemas;

use App\Enums\PatentTypes\Status;
use Filament\Forms;
use Filament\Schemas\Schema;

class PatentTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')->label('类型名称')
                    ->placeholder('请输入类型名称')
                    ->required(),
                Forms\Components\TextInput::make('order_column')->label('排序')->integer()
                    ->placeholder('正序排列')
                    ->rules(['integer', 'min:0']),
                Forms\Components\Radio::make('status')
                    ->label('状态')
                    ->inline()
                    ->default(Status::Normal)
                    ->options(Status::class),
            ])
            ->columns(1);
    }
}
