<?php

namespace App\Filament\Resources\AccurateIdentifies\Exports;

use App\Models\AccurateIdentify;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AccurateIdentifyExporter extends Exporter
{
    protected ?string $delimiter = ',';

    public static function getModel(): string
    {
        return AccurateIdentify::class;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('gene_identify_method')
                ->label('基因鉴定方法'),
            ExportColumn::make('method_params')
                ->label('方法参数'),
            ExportColumn::make('sequencing_platform')
                ->label('测序平台'),
            ExportColumn::make('sequencing_technology')
                ->label('测序技术'),
            ExportColumn::make('f_reads_length')
                ->label('F端reads读长'),
            ExportColumn::make('entity_data_one')
                ->label('实体数据1 MD5'),
            ExportColumn::make('r_reads_length')
                ->label('R端reads读长'),
            ExportColumn::make('entity_data_two')
                ->label('实体数据2 MD5'),
            ExportColumn::make('reference_sequence')
                ->label('参考序列 MD5'),
            ExportColumn::make('sample_no')
                ->label('样本编号'),
            ExportColumn::make('identify_name')
                ->label('鉴定人'),
            ExportColumn::make('identify_at')
                ->label('鉴定时间'),
            ExportColumn::make('identify_conclusion')
                ->label('鉴定结论'),
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
        return 'accurate-identify-' . date('YmdHis') . "-{$export->getKey()}";
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
