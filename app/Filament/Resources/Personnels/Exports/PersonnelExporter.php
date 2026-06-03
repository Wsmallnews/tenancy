<?php

namespace App\Filament\Resources\Personnels\Exports;

use App\Models\Personnel;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PersonnelExporter extends Exporter
{
    protected ?string $delimiter = ',';

    public static function getModel(): string
    {
        return Personnel::class;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('姓名'),
            ExportColumn::make('qualification')
                ->label('学历'),
            ExportColumn::make('professional_title')
                ->label('职称'),
            ExportColumn::make('research_focus')
                ->label('研究方向'),
            ExportColumn::make('research_result')
                ->label('研究成果'),
            ExportColumn::make('intro')
                ->label('个人简介'),
            ExportColumn::make('views')
                ->label('浏览量'),
            ExportColumn::make('order_column')
                ->label('排序'),
            ExportColumn::make('status')
                ->label('状态'),
            ExportColumn::make('is_display')
                ->label('对外展示')
                ->formatStateUsing(fn ($state) => $state ? '是' : '否'),
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
        return 'personnel-' . date('YmdHis') . "-{$export->getKey()}";
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
