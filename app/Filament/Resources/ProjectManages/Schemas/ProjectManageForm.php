<?php

namespace App\Filament\Resources\ProjectManages\Schemas;

use App\Enums\ProjectManages\Status;
use App\Settings\ProjectSettings;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;

class ProjectManageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Section::make('项目信息')->schema([
                            Forms\Components\TextInput::make('project_no')->label('项目编号')
                                ->placeholder('请输入项目编号')
                                ->required(),
                            Forms\Components\TextInput::make('name')->label('项目名称')
                                ->placeholder('请输入项目名称')
                                ->required(),
                            Forms\Components\Select::make('type')->label('项目类型')
                                ->placeholder('请选择项目类型')
                                ->required()
                                ->options(fn (ProjectSettings $settings) => Arr::mapWithKeys($settings->project_type, function ($item) {
                                    return [$item => $item];
                                })),
                            Forms\Components\Select::make('subject')->label('所属学科')
                                ->placeholder('请选择所属学科')
                                ->required()
                                ->options(fn (ProjectSettings $settings) => Arr::mapWithKeys($settings->project_subject, function ($item) {
                                    return [$item => $item];
                                })),
                            Forms\Components\TextInput::make('initiation_company')->label('立项单位')
                                ->placeholder('请输入立项单位')
                                ->required(),
                            Forms\Components\Select::make('level')->label('项目级别')
                                ->placeholder('请选择项目级别')
                                ->required()
                                ->options(fn (ProjectSettings $settings) => Arr::mapWithKeys($settings->project_level, function ($item) {
                                    return [$item => $item];
                                })),
                            Forms\Components\TextInput::make('manager_name')->label('负责人')
                                ->placeholder('请输入负责人')
                                ->required(),
                            Forms\Components\TextInput::make('attend_name')->label('参与人')
                                ->placeholder('请输入参与人')
                                ->required(),
                            Forms\Components\DatePicker::make('start_at')->label('开始时间')
                                ->placeholder('请选择开始时间')
                                ->native(false)
                                ->displayFormat('Y-m-d')
                                ->required(),
                            Forms\Components\DatePicker::make('end_at')->label('结束时间')
                                ->placeholder('请选择结束时间')
                                ->native(false)
                                ->displayFormat('Y-m-d')
                                ->required(),
                            Forms\Components\TextInput::make('budget')->label('总预算')
                                ->placeholder('请输入总预算')
                                ->suffix('元')
                                ->required(),
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
