<?php

namespace App\Filament\Resources\ProjectManages\Tables;

use Filament\Actions;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;
use Wsmallnews\Support\Helpers\FilamentHelper;

class ProjectManagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project_no')
                    ->label('项目编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('项目名称')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('项目类型')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('所属学科')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('initiation_company')
                    ->label('立项单位')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('项目级别')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('manager_name')
                    ->label('负责人')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('attend_name')
                    ->label('参与人')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->label('开始时间')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->label('结束时间')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('budget')
                    ->label('总预算')
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
            ->searchPlaceholder('搜索项目编号、项目名称等...')
            ->filtersFormWidth(Width::Medium)
            ->filters([
                ...FilamentHelper::createUpdateRangeFilter(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                    Actions\ForceDeleteBulkAction::make(),
                    Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
