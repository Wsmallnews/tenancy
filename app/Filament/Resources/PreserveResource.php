<?php

namespace App\Filament\Resources;

use App\Enums\Preserves\Status;
use App\Filament\Resources\PreserveResource\Pages;
use App\Models\Appraise;
use App\Models\Preserve;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PreserveResource extends Resource
{
    protected static ?string $model = Preserve::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '保存';

    protected static ?string $navigationGroup = '种质资源库';

    protected static ?string $slug = 'preserves';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '保存';

    protected static ?string $pluralModelLabel = '保存';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('基础信息')->schema([
                        Forms\Components\Select::make('appraise_id')->label('选择种质')
                            ->relationship(name: 'appraise', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                return $query->normal()->orderBy('order_column', 'asc');
                            })
                            ->placeholder('请选择种质')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set, Forms\Components\Select $component, $state) {
                                self::afterUpdateAppraiseInfo($component, $state, $set);
                            })
                            ->required(),
                        Forms\Components\TextInput::make('resource_no')->label('种质资源编号')
                            ->placeholder('请输入种质资源编号')
                            ->disabled()
                            ->required(),
                        Forms\Components\TextInput::make('germplasm_name')->label('种质中文名')
                            ->placeholder('请输入种质中文名')
                            ->disabled()
                            ->required(),
                        Forms\Components\TextInput::make('germplasm_az_name')->label('种质拉丁学名')
                            ->placeholder('请输入种质拉丁学名')
                            ->disabled()
                            ->required(),
                        Forms\Components\ViewField::make('cover')
                            ->label('封面图')
                            ->disabled()
                            ->view('forms.fields.show-image'),
                        Forms\Components\TextInput::make('preserve_no')->label('保存编号')
                            ->placeholder('请输入保存编号')
                            ->required(),
                        Forms\Components\TextInput::make('preserve_position')->label('保存位置')
                            ->placeholder('请输入保存位置')
                            ->required(),
                    ]),
                ])->columns(2)->columnSpan(2),
                Forms\Components\Section::make('状态')->schema([
                    Forms\Components\TextInput::make('order_column')->label('排序')->integer()
                        ->placeholder('正序排列')
                        ->rules(['integer', 'min:0']),
                    Forms\Components\Radio::make('status')
                        ->label('状态')
                        ->default(Status::Normal)
                        ->inline()
                        ->options(Status::class),
                ])->columns(1)->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('preserve_no')
                    ->label('保存编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('preserve_position')
                    ->label('保存位置')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('appraise.cover')
                    ->label('评价封面图')
                    ->collection('cover')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.resource_no')
                    ->label('种质资源编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.name')
                    ->label('种质中文名')
                    ->searchable()
                    ->toggleable(), 
                Tables\Columns\TextColumn::make('appraise.az_name')
                    ->label('种质拉丁学名')
                    ->searchable()
                    ->toggleable(), 
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
            ->searchPlaceholder('搜索保存编号、保存位置等...')
            ->filtersFormWidth(MaxWidth::Medium)
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\Group::make()->schema([
                            Forms\Components\DatePicker::make('created_from')->label('创建开始时间')->columnSpan(1),
                            Forms\Components\DatePicker::make('created_until')->label('创建结束时间')->columnSpan(1),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('updated_at')
                    ->form([
                        Forms\Components\Group::make()->schema([
                            Forms\Components\DatePicker::make('updated_from')->label('更新开始时间')->columnSpan(1),
                            Forms\Components\DatePicker::make('updated_until')->label('更新结束时间')->columnSpan(1),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['updated_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('updated_at', '>=', $date),
                            )
                            ->when(
                                $data['updated_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('updated_at', '<=', $date),
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreserves::route('/'),
            'create' => Pages\CreatePreserve::route('/create'),
            'edit' => Pages\EditPreserve::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


    /**
     * 选择时修改数据
     */
    public static function afterUpdateAppraiseInfo(Forms\Components\Select $component, $state, Set $set)
    {
        $appraise = Appraise::findOrFail($state);
        $coverMedia = $appraise->getFirstMedia('cover');
        $set('resource_no', $appraise->resource_no);
        $set('germplasm_name', $appraise->name);
        $set('germplasm_az_name', $appraise->az_name);
        $set('cover', $coverMedia->getFullUrl());
    }


    /**
     * 编辑时，自动填充数据
     * 
     * @param array $data
     * @return array
     */
    public static function fillAppraiseInfo($data)
    {
        $appraise = Appraise::findOrFail($data['appraise_id']);
        $coverMedia = $appraise->getFirstMedia('cover');
        $data['resource_no'] = $appraise->resource_no;
        $data['germplasm_name'] = $appraise->name;
        $data['germplasm_az_name'] = $appraise->az_name;
        $data['cover'] = $coverMedia->getFullUrl();
        return $data;
    }
}
