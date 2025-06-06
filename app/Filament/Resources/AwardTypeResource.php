<?php

namespace App\Filament\Resources;

use App\Enums\AwardTypes\Status;
use App\Filament\Resources\AwardTypeResource\Pages;
use App\Filament\Resources\AwardTypeResource\RelationManagers;
use App\Models\AwardType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AwardTypeResource extends Resource
{
    protected static ?string $model = AwardType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '奖项类型';

    protected static ?string $navigationGroup = '研究成果';

    protected static ?string $navigationParentItem = '奖项';

    protected static ?string $slug = 'award-types';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '奖项类型';

    protected static ?string $pluralModelLabel = '奖项类型';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('类型名称')
                    ->placeholder('请输入类型名称')
                    ->required()
                    ->columnSpan(1),
                Forms\Components\TextInput::make('order_column')->label('排序')->integer()
                    ->placeholder('正序排列')
                    ->rules(['integer', 'min:0'])
                    ->columnSpan(1),
                Forms\Components\Radio::make('status')
                    ->label('状态')
                    ->default(Status::Normal)
                    ->options(Status::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('类型名称'),
                Tables\Columns\TextColumn::make('order_column')
                    ->label('排序')
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
            ->deferFilters()        // 延迟过滤,用户点击 apply 按钮后才会应用过滤器
            ->reorderable('order_column')
            ->defaultSort('order_column', 'asc')
            ->searchPlaceholder('搜索奖项类型')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAwardTypes::route('/'),
        ];
    }
}
