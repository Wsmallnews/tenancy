<?php

namespace App\Filament\Resources\Theses\Exports;

use App\Models\Thesis;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ThesisExporter extends Exporter
{
    protected ?string $delimiter = ',';

    public static function getModel(): string
    {
        return Thesis::class;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('title')
                ->label('论文标题'),
            ExportColumn::make('thesisType.name')
                ->label('论文类型')
                ->formatStateUsing(fn ($state, $record) => $record->thesisType?->name ?? ''),
            ExportColumn::make('author_name')
                ->label('作者'),
            ExportColumn::make('company.name')
                ->label('所属单位')
                ->formatStateUsing(fn ($state, $record) => $record->company?->name ?? ''),
            ExportColumn::make('journal')
                ->label('发布期刊'),
            ExportColumn::make('issue_number')
                ->label('卷期号'),
            ExportColumn::make('published_at')
                ->label('出版日期'),
            ExportColumn::make('keywords')
                ->label('关键字')
                ->formatStateUsing(fn ($state, $record) => $record->tagsWithType('keywords')->pluck('name')->implode(', ')),
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
        return 'thesis-' . date('YmdHis') . "-{$export->getKey()}";
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
