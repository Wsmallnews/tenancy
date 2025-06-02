<?php

namespace App\Filament\Resources;

use App\Enums\ThesisTypes\Status;
use App\Filament\Resources\ThesisTypeResource\Pages;
use App\Filament\Resources\ThesisTypeResource\RelationManagers;
use App\Models\ThesisType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThesisTypeResource extends Resource
{
    protected static ?string $model = ThesisType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '论文类型';

    protected static ?string $navigationGroup = '研究成果';

    protected static ?string $navigationParentItem = '论文';

    protected static ?string $slug = 'thesis-types';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '论文类型';

    protected static ?string $pluralModelLabel = '论文类型';

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
                    ->label('排序'),
                Tables\Columns\TextColumn::make('status')
                    ->label('状态'),
            ])
            ->deferFilters()        // 延迟过滤,用户点击 apply 按钮后才会应用过滤器
            ->reorderable('order_column')
            ->defaultSort('order_column', 'asc')
            ->searchPlaceholder('搜索论文类型')
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
            'index' => Pages\ManageThesisTypes::route('/'),
        ];
    }
}
