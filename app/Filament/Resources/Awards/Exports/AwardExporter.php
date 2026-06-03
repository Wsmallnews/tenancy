<?php

namespace App\Filament\Resources\Awards\Exports;

use App\Models\Award;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AwardExporter extends Exporter
{
    protected ?string $delimiter = ',';

    public static function getModel(): string
    {
        return Award::class;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('奖项名称'),
            ExportColumn::make('awardType.name')
                ->label('奖项类型')
                ->formatStateUsing(fn ($state, $record) => $record->awardType?->name ?? ''),
            ExportColumn::make('award_agency')
                ->label('授奖机构'),
            ExportColumn::make('award_at')
                ->label('获奖日期'),
            ExportColumn::make('level')
                ->label('级别'),
            ExportColumn::make('award_name')
                ->label('获奖人/团队'),
            ExportColumn::make('order_column')
                ->label('排序'),
            ExportColumn::make('status')
                ->label('状态'),
            ExportColumn::make('created_at')
                ->label('创建时间'),
            ExportColumn::make('updated_at')
                ->label('更新时间'),
        ];
    }

    public function getJobConnection(): ?string
    {
        return 'sync';
    }

    public function getFileName(Export $export): string
    {
        return 'award-' . date('YmdHis') . "-{$export->getKey()}";
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = "成功导出 {$export->successful_rows} 条记录。";
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= " {$failedRowsCount} 条记录导出失败。";
        }

        return $body;
    }
}
