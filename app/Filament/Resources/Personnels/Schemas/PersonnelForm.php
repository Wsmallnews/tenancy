<?php

namespace App\Filament\Resources\Personnels\Schemas;

use App\Enums\Personnels\Status;
use App\Settings\OtherSettings;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Wsmallnews\Support\Filament\Forms\FormComponents;

class PersonnelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Section::make('人员信息')->schema([
                            FormComponents::mediaImageUpload('avatar', 'avatar')->label('头像')
                                ->helperText('支持上传图片')
                                ->required()
                                ->uploadingMessage('头像上传中...')
                                ->columns(1),
                            Forms\Components\TextInput::make('name')->label('姓名')
                                ->placeholder('请输入人员姓名')
                                ->required(),
                            Forms\Components\Select::make('qualification')->label('学历')
                                ->placeholder('请选择人员学历')
                                ->options(fn (OtherSettings $settings) => Arr::mapWithKeys($settings->qualification, function ($item) {
                                    return [$item => $item];
                                })),
                            Forms\Components\TextInput::make('professional_title')->label('职称')
                                ->placeholder('请输入人员职称'),
                            Forms\Components\TextInput::make('research_focus')->label('研究方向')
                                ->placeholder('请输入研究方向'),
                            Forms\Components\Textarea::make('research_result')->label('研究成果')
                                ->placeholder('请输入研究成果'),
                            Forms\Components\Textarea::make('intro')->label('个人简介')
                                ->placeholder('请输入个人简介'),
                            Schemas\Components\Group::make()
                                ->relationship('content')
                                ->schema([
                                    FormComponents::richEditor('content')
                                        ->fileAttachmentsDirectory('contents/'.date('Ymd'))
                                        ->label('履历'),
                                ])->columns(1)
                                ->columnSpanFull(),
                        ])->columns(2),
                    ])->columns(1),
                    Schemas\Components\Section::make('状态')->schema([
                        Forms\Components\TextInput::make('order_column')->label('排序')->integer()
                            ->placeholder('正序排列')
                            ->rules(['integer', 'min:0']),
                        Forms\Components\Toggle::make('is_display')
                            ->label('是否对外展示')
                            ->default(false),
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
