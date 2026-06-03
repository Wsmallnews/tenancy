<?php

namespace App\Filament\Resources\Companies\Exports;

use App\Models\Company;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CompanyExporter extends Exporter
{
    protected ?string $delimiter = ',';

    public static function getModel(): string
    {
        return Company::class;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('单位名称'),
            ExportColumn::make('code')
                ->label('单位编号'),
            ExportColumn::make('contact')
                ->label('联系人'),
            ExportColumn::make('contact_phone')
                ->label('联系人电话'),
            ExportColumn::make('email')
                ->label('邮箱'),
            ExportColumn::make('province_name')
                ->label('省'),
            ExportColumn::make('city_name')
                ->label('市'),
            ExportColumn::make('district_name')
                ->label('区'),
            ExportColumn::make('address')
                ->label('所在地址'),
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
        return 'company-' . date('YmdHis') . "-{$export->getKey()}";
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
