<?php

namespace App\Filament\Resources\PhenotypeIdentifies\Widgets;

use App\Filament\Resources\Concerns\HasCategoryFields;
use App\Filament\Resources\PhenotypeIdentifies\PhenotypeIdentifyResource;
use App\Models\Appraise;
use App\Models\PhenotypeIdentify;
use Filament\Actions;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class PhenotypeIdentifyTableWidget extends TableWidget
{
    use HasCategoryFields;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = '表型鉴定';

    public ?Appraise $record = null;

    public function table(Table $table): Table
    {
        $table = $table
            ->query(function () {
                return PhenotypeIdentify::query()
                    ->scopeTenant()
                    ->where('appraise_id', $this->record?->id)
                    ->normal()
                    ->orderBy('order_column', 'asc');
            })
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),
                TextColumn::make('name')
                    ->label('名称')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('description')
                    ->label('描述')
                    ->searchable()
                    ->toggleable(),
                ColumnGroup::make(
                    '种质信息',
                    static::getDynamicCategoryColumns($this->record->category_id)
                ),
                TextColumn::make('order_column')
                    ->label('排序')
                    ->alignCenter()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('状态')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('创建时间')
                    ->toggleable()
                    ->sortable(),
            ])
            ->defaultSort('order_column', 'asc')
            ->paginated([10, 25, 50])
            ->recordActions([
                Actions\Action::make('view')
                    ->label('查看')
                    ->icon('heroicon-o-eye')
                    ->url(fn (PhenotypeIdentify $record): string => PhenotypeIdentifyResource::getUrl('view', ['record' => $record])),
            ]);

        return $table;
    }
}
