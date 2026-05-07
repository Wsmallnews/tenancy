<?php

namespace App\Filament\Platform\Resources\ActivityLogs\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Wsmallnews\Support\Filament\Resources\ActivityLogs\Concerns\SubjectTimelineAction;
use Wsmallnews\Support\Filament\Resources\ActivityLogs\Tables\ActivityLogTable as WsmallnewsActivityLogTable;
use Wsmallnews\Support\Helpers\FilamentHelper;

class ActivityLogTable extends WsmallnewsActivityLogTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                static::IDColumn(),
                static::eventColumn(),
                static::subjectTypeColumn(),
                static::causerColumn(),
                static::ipAddressColumn(),
                static::userAgentColumn(),
                static::descriptionColumn(),
                static::createdAtColumn(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                static::eventFilter(),
                static::causerFilter(),
                static::subjectTypeFilter(),
                FilamentHelper::dateTimeRangeFilter('created_at', '发生'),
            ])
            ->headerActions([
                static::exportHeaderAction(),
            ])
            ->recordActions([
                ActionGroup::make([
                    SubjectTimelineAction::make()
                        ->modifyQueryUsing(fn($query) => $query->whereNull('team_id'))
                        ->color('info'),
                    ViewAction::make(),
                    static::revertAction(),
                    static::deleteAction(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    static::exportBulkAction(),
                    static::deleteBulkAction(),
                    static::revertBulkAction(),
                ]),
            ]);
    }
}
