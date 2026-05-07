<?php

namespace App\Filament\Resources\Personnels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PersonnelsTable
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
                    ->label('姓名')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('qualification')
                    ->label('学历')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('professional_title')
                    ->label('职称')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('research_focus')
                    ->label('研究方向')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('research_result')
                    ->label('研究成果')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('intro')
                    ->label('个人简介')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('views')
                    ->label('浏览量')
                    ->alignCenter()
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
            ->searchPlaceholder('搜索人员姓名、学历、职称等...')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
