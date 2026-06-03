<?php

namespace App\Filament\Resources\Preserves\Exports;

use App\Models\Preserve;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PreserveExporter extends Exporter
{
    protected ?string $delimiter = ',';

    public static function getModel(): string
    {
        return Preserve::class;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('preserve_no')
                ->label('保存编号'),
            ExportColumn::make('preserve_position')
                ->label('保存位置'),
            ExportColumn::make('putin_at')
                ->label('入库日期'),
            ExportColumn::make('num')
                ->label('初始数量'),
            ExportColumn::make('weight')
                ->label('初始质量'),
            ExportColumn::make('appraise.resource_no')
                ->label('全国统一编号')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->resource_no ?? ''),
            ExportColumn::make('appraise.name')
                ->label('种质中文名')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->name ?? ''),
            ExportColumn::make('appraise.en_name')
                ->label('种质外文名')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->en_name ?? ''),
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
        return 'preserve-' . date('YmdHis') . "-{$export->getKey()}";
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
