<?php

namespace App\Filament\Resources\Appraises\Exports;

use App\Models\Appraise;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AppraiseExporter extends Exporter
{
    protected ?string $delimiter = ',';

    public static function getModel(): string
    {
        return Appraise::class;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('category.name')
                ->label('分类')
                ->formatStateUsing(fn ($state, $record) => $record->category?->name ?? ''),
            ExportColumn::make('resource_no')
                ->label('全国统一编号'),
            ExportColumn::make('germplasm_no')
                ->label('种质圃编号'),
            ExportColumn::make('original_no')
                ->label('引种号'),
            ExportColumn::make('gather_no')
                ->label('采集号'),
            ExportColumn::make('name')
                ->label('种质名称'),
            ExportColumn::make('en_name')
                ->label('种质外文名'),
            ExportColumn::make('subject_name')
                ->label('科名'),
            ExportColumn::make('genus_name')
                ->label('属名'),
            ExportColumn::make('species_name')
                ->label('学名'),
            ExportColumn::make('country_name')
                ->label('原产国'),
            ExportColumn::make('province_name')
                ->label('原产省'),
            ExportColumn::make('city_name')
                ->label('原产市'),
            ExportColumn::make('address')
                ->label('原产地址'),
            ExportColumn::make('altitude')
                ->label('海拔(米)'),
            ExportColumn::make('longitude')
                ->label('经度'),
            ExportColumn::make('latitude')
                ->label('纬度'),
            ExportColumn::make('source_country_name')
                ->label('来源国'),
            ExportColumn::make('source_province_name')
                ->label('来源省'),
            ExportColumn::make('source_city_name')
                ->label('来源市'),
            ExportColumn::make('source_address')
                ->label('来源地址'),
            ExportColumn::make('saveCompany.name')
                ->label('保存单位')
                ->formatStateUsing(fn ($state, $record) => $record->saveCompany?->name ?? ''),
            ExportColumn::make('pedigree')
                ->label('系谱'),
            ExportColumn::make('breedingCompany.name')
                ->label('选育单位')
                ->formatStateUsing(fn ($state, $record) => $record->breedingCompany?->name ?? ''),
            ExportColumn::make('cultivationd_at')
                ->label('育成年份'),
            ExportColumn::make('breeding_method')
                ->label('选育方法'),
            ExportColumn::make('germplasm_type')
                ->label('种质类型'),
            ExportColumn::make('germplasm_use')
                ->label('种质用途'),
            ExportColumn::make('fruit_use')
                ->label('果实用途'),
            ExportColumn::make('plant_use')
                ->label('植株用途'),
            ExportColumn::make('assemble_resource')
                ->label('种植收集源'),
            ExportColumn::make('assemble_material_type')
                ->label('收集材料类型'),
            ExportColumn::make('observe_place')
                ->label('观测地点'),
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
        return 'appraise-' . date('YmdHis') . "-{$export->getKey()}";
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
