<?php

namespace App\Filament\Platform\Resources\ActivityLogs;

use App\Filament\Platform\Resources\ActivityLogs\Pages;
use App\Filament\Platform\Resources\ActivityLogs\Tables\ActivityLogTable;
use Filament\Facades\Filament;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Wsmallnews\Support\Filament\Resources\ActivityLogs\BaseResource;

class ActivityLogResource extends BaseResource
{

    public static function getNavigationGroup(): ?string
    {
        return __('filament-shield::filament-shield.nav.group');        // 和角色放到一个组
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }

    public static function table(Table $table): Table
    {
        return ActivityLogTable::configure($table);
    }


    public static function getEloquentQuery(): Builder
    {
        $panel = Filament::getCurrentPanel();
        $channel = 'panel-' . $panel->getId();

        // resource 只查询 默认 log_name 的日志
        return parent::getEloquentQuery()
            ->whereNull('team_id')
            ->where('properties->channel', $channel);           // 只查询当前面板的日志
    }
}