<?php

namespace App\Filament\Resources\Patents\Exports;

use App\Models\Patent;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PatentExporter extends Exporter
{
    protected ?string $delimiter = ',';

    public static function getModel(): string
    {
        return Patent::class;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('专利名称'),
            ExportColumn::make('patentType.name')
                ->label('专利类型')
                ->formatStateUsing(fn ($state, $record) => $record->patentType?->name ?? ''),
            ExportColumn::make('patent_apply_no')
                ->label('专利申请号'),
            ExportColumn::make('patent_no')
                ->label('专利号'),
            ExportColumn::make('applied_at')
                ->label('申请日期'),
            ExportColumn::make('authd_at')
                ->label('授权日期'),
            ExportColumn::make('status')
                ->label('专利状态'),
            ExportColumn::make('author_name')
                ->label('发明人/作者'),
            ExportColumn::make('order_column')
                ->label('排序'),
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
        return 'patent-' . date('YmdHis') . "-{$export->getKey()}";
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
