<?php

namespace App\Filament\Resources\Companies\Tables;

use App\Filament\Resources\Companies\Exports\CompanyExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction as FilamentExportAction;
use Filament\Actions\ExportBulkAction as FilamentExportBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CompaniesTable
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
                Tables\Columns\TextColumn::make('name')
                    ->label('单位名称')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('单位编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('contact')
                    ->label('联系人')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('contact_phone')
                    ->label('联系人电话')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('邮箱')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('district_name')
                    ->label('所在地区')
                    ->searchable()
                    ->state(function (Model $record): string {
                        return $record->province_name.' / '.$record->city_name.' / '.$record->district_name;
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('所在地址')
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
            ->searchPlaceholder('搜索公司名称、编号等...')
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                FilamentExportAction::make()
                    ->exporter(CompanyExporter::class)
                    ->icon(Heroicon::ArrowDownTray)
                    ->color('gray'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    FilamentExportBulkAction::make()
                        ->exporter(CompanyExporter::class)
                        ->icon(Heroicon::ArrowDownTray)
                        ->color('gray'),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
