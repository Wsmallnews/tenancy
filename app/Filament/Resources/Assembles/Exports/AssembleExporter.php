<?php

namespace App\Filament\Resources\Assembles\Exports;

use App\Models\Assemble;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AssembleExporter extends Exporter
{
    protected ?string $delimiter = ',';

    public static function getModel(): string
    {
        return Assemble::class;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('收集人'),
            ExportColumn::make('assembleCompany.name')
                ->label('收集单位')
                ->formatStateUsing(fn ($state, $record) => $record->assembleCompany?->name ?? ''),
            ExportColumn::make('assemble_no')
                ->label('收集编号'),
            ExportColumn::make('subject_no')
                ->label('所属课题编号'),
            ExportColumn::make('sub_subject_no')
                ->label('所属子课题编号'),
            ExportColumn::make('appraise.resource_no')
                ->label('全国统一编号')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->resource_no ?? ''),
            ExportColumn::make('appraise.name')
                ->label('种质中文名')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->name ?? ''),
            ExportColumn::make('appraise.en_name')
                ->label('种质外文名')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->en_name ?? ''),
            ExportColumn::make('appraise.subject_name')
                ->label('科名')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->subject_name ?? ''),
            ExportColumn::make('appraise.genus_name')
                ->label('属名')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->genus_name ?? ''),
            ExportColumn::make('appraise.species_name')
                ->label('学名')
                ->formatStateUsing(fn ($state, $record) => $record->appraise?->species_name ?? ''),
            ExportColumn::make('country_name')
                ->label('收集国家'),
            ExportColumn::make('province_name')
                ->label('收集省'),
            ExportColumn::make('city_name')
                ->label('收集市'),
            ExportColumn::make('address')
                ->label('收集地址'),
            ExportColumn::make('longitude')
                ->label('经度'),
            ExportColumn::make('latitude')
                ->label('纬度'),
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
        return 'assemble-' . date('YmdHis') . "-{$export->getKey()}";
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
