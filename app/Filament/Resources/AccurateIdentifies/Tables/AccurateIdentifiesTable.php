<?php

namespace App\Filament\Resources\AccurateIdentifies\Tables;

use App\Filament\Resources\AccurateIdentifies\Exports\AccurateIdentifyExporter;
use Filament\Actions;
use Filament\Actions\ExportAction as FilamentExportAction;
use Filament\Actions\ExportBulkAction as FilamentExportBulkAction;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Wsmallnews\Support\Filament\Filters\FilterComponents;

class AccurateIdentifiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('gene_identify_method')
                    ->label('基因鉴定方法')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('method_params')
                    ->label('方法参数')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sequencing_platform')
                    ->label('测序平台')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sequencing_technology')
                    ->label('测序技术')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('f_reads_length')
                    ->label('F端reads读长')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('entity_data_one')
                    ->label('实体数据1 MD5')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('r_reads_length')
                    ->label('R端reads读长')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('entity_data_two')
                    ->label('实体数据2 MD5')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('reference_sequence')
                    ->label('参考序列 MD5')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sample_no')
                    ->label('样本编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('identify_name')
                    ->label('鉴定人')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('identify_at')
                    ->label('鉴定时间')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('identify_conclusion')
                    ->label('鉴定结论')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('appraise.cover')
                    ->label('种质封面图')
                    ->collection('cover')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.resource_no')
                    ->label('全国统一编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.name')
                    ->label('种质中文名')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.en_name')
                    ->label('种质外文名')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.country_name')
                    ->label('种质原产国')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.district_name')
                    ->label('种质原产地区')
                    ->searchable()
                    ->state(function (Model $record): string {
                        if ($record->appraise?->country_code == 'CN') {
                            return $record->appraise->province_name.' / '.$record->appraise->city_name;
                        }

                        return '/';
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.address')
                    ->label('种质原产地址')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('order_column')
                    ->label('排序')
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('状态')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->toggleable()
                    ->sortable(),
            ])
            ->reorderable('order_column')
            ->defaultSort('order_column', 'asc')
            ->searchPlaceholder('搜索鉴定方法、样本编号等...')
            ->filtersFormWidth(Width::Medium)
            ->filters([
                ...FilterComponents::createUpdateRangeFilter(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                FilamentExportAction::make()
                    ->exporter(AccurateIdentifyExporter::class)
                    ->icon(Heroicon::ArrowDownTray)
                    ->color('gray'),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    FilamentExportBulkAction::make()
                        ->exporter(AccurateIdentifyExporter::class)
                        ->icon(Heroicon::ArrowDownTray)
                        ->color('gray'),
                    Actions\DeleteBulkAction::make(),
                    Actions\ForceDeleteBulkAction::make(),
                    Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
