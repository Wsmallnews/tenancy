<?php

namespace App\Filament\Resources\NewVarieties\Exports;

use App\Models\NewVariety;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class NewVarietyExporter extends Exporter
{
    protected ?string $delimiter = ',';

    public static function getModel(): string
    {
        return NewVariety::class;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('variety_no')
                ->label('品种权号'),
            ExportColumn::make('name')
                ->label('品种权人'),
            ExportColumn::make('variety_at')
                ->label('年份'),
            ExportColumn::make('cultivate_name')
                ->label('培育人'),
            ExportColumn::make('appraise.resource_no')
                ->label('全国统一编号')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->resource_no ?? ''),
            ExportColumn::make('appraise.name')
                ->label('种质中文名')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->name ?? ''),
            ExportColumn::make('appraise.en_name')
                ->label('种质外文名')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->en_name ?? ''),
            ExportColumn::make('appraise.country_name')
                ->label('种质原产国')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->country_name ?? ''),
            ExportColumn::make('appraise.province_name')
                ->label('种质原产省')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->province_name ?? ''),
            ExportColumn::make('appraise.city_name')
                ->label('种质原产市')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->city_name ?? ''),
            ExportColumn::make('appraise.address')
                ->label('种质原产地址')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->address ?? ''),
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
        return 'new-variety-' . date('YmdHis') . "-{$export->getKey()}";
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
