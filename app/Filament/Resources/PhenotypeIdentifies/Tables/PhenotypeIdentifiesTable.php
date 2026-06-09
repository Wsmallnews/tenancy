<?php

namespace App\Filament\Resources\PhenotypeIdentifies\Tables;

use App\Filament\Resources\Concerns\HasCategoryFields;
use Filament\Actions;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;
use Wsmallnews\Support\Filament\Filters\FilterComponents;

class PhenotypeIdentifiesTable
{
    use HasCategoryFields;

    public static function configure(Table $table): Table
    {
        $categoryId = request()->route('categoryId');

        return $table
            ->modifyQueryUsing(function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('名称')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('描述')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\ColumnGroup::make('种质基础信息', [
                    Tables\Columns\TextColumn::make('category.name')
                        ->label('种质分类')
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
                    Tables\Columns\TextColumn::make('appraise.subject_name')
                        ->label('科名')
                        ->searchable()
                        ->toggleable(),
                    Tables\Columns\TextColumn::make('appraise.genus_name')
                        ->label('属名')
                        ->searchable()
                        ->toggleable(),
                    Tables\Columns\TextColumn::make('appraise.species_name')
                        ->label('学名')
                        ->searchable()
                        ->toggleable(),
                ]),
                Tables\Columns\ColumnGroup::make(
                    '种质信息',
                    static::getDynamicCategoryColumns($categoryId),
                ),
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
            ->searchPlaceholder('搜索 @sn todo 等...')
            ->filtersFormWidth(Width::Medium)
            ->filters([
                ...FilterComponents::createUpdateRangeFilter(),
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
