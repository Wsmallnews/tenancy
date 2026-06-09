<?php

namespace App\Filament\Resources\ProjectManages\Exports;

use App\Models\ProjectManage;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProjectManageExporter extends Exporter
{
    protected ?string $delimiter = ',';

    public static function getModel(): string
    {
        return ProjectManage::class;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('project_no')
                ->label('项目编号'),
            ExportColumn::make('name')
                ->label('项目名称'),
            ExportColumn::make('type')
                ->label('项目类型'),
            ExportColumn::make('subject')
                ->label('所属学科'),
            ExportColumn::make('initiation_company')
                ->label('立项单位'),
            ExportColumn::make('level')
                ->label('项目级别'),
            ExportColumn::make('manager_name')
                ->label('负责人'),
            ExportColumn::make('attend_name')
                ->label('参与人'),
            ExportColumn::make('start_at')
                ->label('开始时间'),
            ExportColumn::make('end_at')
                ->label('结束时间'),
            ExportColumn::make('budget')
                ->label('总预算(元)'),
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
        return 'project-manage-' . date('YmdHis') . "-{$export->getKey()}";
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
