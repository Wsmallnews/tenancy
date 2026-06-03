<?php

namespace App\Filament\Resources\AppraiseApplies\Exports;

use App\Models\AppraiseApply;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AppraiseApplyExporter extends Exporter
{
    protected ?string $delimiter = ',';

    public static function getModel(): string
    {
        return AppraiseApply::class;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user.name')
                ->label('申请用户')
                ->formatStateUsing(fn ($state, $record) => $record->user?->name ?? ''),
            ExportColumn::make('name')
                ->label('申请人'),
            ExportColumn::make('phone')
                ->label('联系方式'),
            ExportColumn::make('company_name')
                ->label('用种单位'),
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
        return 'appraise-apply-' . date('YmdHis') . "-{$export->getKey()}";
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
