<?php

namespace App\Filament\Resources\ProjectManages\Schemas;

use App\Features\Common;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;

class ProjectManageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Text::make(Common::title('项目信息')),
                Schemas\Components\Grid::make([
                    'default' => 1,
                    'lg' => 2,
                    'xl' => 3,
                ])
                    ->extraAttributes([
                        'class' => 'sn-grid-table',
                    ])
                    ->schema([
                        Infolists\Components\TextEntry::make('project_no')
                            ->label('项目编号'),
                        Infolists\Components\TextEntry::make('name')
                            ->label('项目名称'),
                        Infolists\Components\TextEntry::make('type')
                            ->label('项目类型'),
                        Infolists\Components\TextEntry::make('subject')
                            ->label('所属学科'),
                        Infolists\Components\TextEntry::make('initiation_company')
                            ->label('立项单位'),
                        Infolists\Components\TextEntry::make('level')
                            ->label('项目级别'),
                        Infolists\Components\TextEntry::make('manager_name')
                            ->label('负责人'),
                        Infolists\Components\TextEntry::make('attend_name')
                            ->label('参与人'),
                        Infolists\Components\TextEntry::make('start_at')
                            ->label('开始时间')
                            ->date('Y-m-d'),
                        Infolists\Components\TextEntry::make('end_at')
                            ->label('结束时间')
                            ->date('Y-m-d'),
                        Infolists\Components\TextEntry::make('budget')
                            ->label('总预算(元)')
                            ->suffix('元'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('创建时间')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('更新时间')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('order_column')
                            ->label('排序')
                            ->numeric(),
                        Infolists\Components\TextEntry::make('status')
                            ->label('状态'),
                    ])->columnSpanFull(),
            ]);
    }
}
