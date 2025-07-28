<?php

namespace App\Filament\Resources;

use App\Enums\NewVarieties\Status;
use App\Filament\Forms\Fields\DistrictSelect;
use App\Filament\Resources\NewVarietyResource\Pages;
use App\Models\Appraise;
use App\Models\NewVariety;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewVarietyResource extends Resource
{
    protected static ?string $model = NewVariety::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '新品种';

    protected static ?string $navigationGroup = '种质目录';

    protected static ?string $slug = 'new-varieties';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '新品种';

    protected static ?string $pluralModelLabel = '新品种';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Split::make([
                    Forms\Components\Group::make()->schema([
                        Forms\Components\Section::make('种质信息')->schema([
                            Forms\Components\Select::make('appraise_id')->label('选择种质')
                                ->relationship(name: 'appraise', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                    return $query->normal()->orderBy('order_column', 'asc');
                                })
                                ->placeholder('请选择种质')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\ViewField::make('appraiseInfo')
                                ->view('forms.fields.fields-info')
                                ->viewData(function (Get $get) {
                                    $data = [
                                        'title' => '种质信息',
                                        'fields' => [],
                                        'count' => 11,      // 图片算两个
                                    ];

                                    if ($get('appraise_id')) {
                                        $appraise = Appraise::findOrFail($get('appraise_id'));
                                        $coverMedia = $appraise->getFirstMedia('cover');

                                        $data['fields'][] = [
                                            'type' => 'image',
                                            'field_name' => 'cover',
                                            'label' => '种质封面图',
                                            'value' => $coverMedia->getFullUrl(),
                                        ];
                                        $data['fields'][] = [
                                            'type' => 'text',
                                            'field_name' => 'resource_no',
                                            'label' => '种质资源编号',
                                            'value' => $appraise->resource_no,
                                        ];
                                        $data['fields'][] = [
                                            'type' => 'text',
                                            'field_name' => 'name',
                                            'label' => '种质中文名',
                                            'value' => $appraise->name,
                                        ];
                                        $data['fields'][] = [
                                            'type' => 'text',
                                            'field_name' => 'en_name',
                                            'label' => '种质外文名',
                                            'value' => $appraise->en_name,
                                        ];
                                        $data['fields'][] = [
                                            'type' => 'text',
                                            'field_name' => 'country_name',
                                            'label' => '种质原产国',
                                            'value' => $appraise->country_name,
                                        ];
                                        $data['fields'][] = [
                                            'type' => 'text',
                                            'field_name' => 'district_name',
                                            'label' => '种质原产地区',
                                            'value' => $appraise->province_name . '/' . $appraise->city_name,
                                        ];
                                        $data['fields'][] = [
                                            'type' => 'text',
                                            'field_name' => 'address',
                                            'label' => '种质原产地址',
                                            'value' => $appraise->address,
                                        ];
                                        $data['fields'][] = [
                                            'type' => 'text',
                                            'field_name' => 'subject_name',
                                            'label' => '科名',
                                            'value' => $appraise->subject_name,
                                        ];
                                        $data['fields'][] = [
                                            'type' => 'text',
                                            'field_name' => 'genus_name',
                                            'label' => '属名',
                                            'value' => $appraise->genus_name,
                                        ];
                                        $data['fields'][] = [
                                            'type' => 'text',
                                            'field_name' => 'species_name',
                                            'label' => '学名',
                                            'value' => $appraise->species_name,
                                        ];
                                    }
                                    return $data;
                                })
                                ->dehydrated(false)
                                ->visible(fn(Get $get): bool => boolval($get('appraise_id')))
                                ->columnSpanFull(),
                        ]),
                        Forms\Components\Section::make('品种信息')->schema([
                            Forms\Components\TextInput::make('variety_no')->label('品种权号')
                                ->placeholder('请输入品种权号')
                                ->required(),
                            Forms\Components\TextInput::make('name')->label('品种权人')
                                ->placeholder('请输入品种权人')
                                ->required(),
                            Forms\Components\DatePicker::make('variety_at')->label('年份')
                                ->placeholder('请选择年份')
                                ->native(false)
                                ->displayFormat('Y-m')
                                ->required(),
                            Forms\Components\TextInput::make('cultivate_name')->label('培育人')
                                ->placeholder('请输入培育人')
                                ->required(),
                        ])->columns(2),
                    ])->columns(1),
                    Forms\Components\Section::make('状态')->schema([
                        Forms\Components\TextInput::make('order_column')->label('排序')->integer()
                            ->placeholder('正序排列')
                            ->rules(['integer', 'min:0']),
                        Forms\Components\Radio::make('status')
                            ->label('状态')
                            ->default(Status::Normal)
                            ->inline()
                            ->options(Status::class),
                    ])->grow(false),
                ])
                ->columnSpanFull()
                ->from('lg')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('variety_no')
                    ->label('品种权号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('品种权人')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('variety_at')
                    ->label('年份')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('cultivate_name')
                    ->label('培育人')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('appraise.cover')
                    ->label('种质封面图')
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
                        return $record->appraise ? ($record->appraise->province_name . '/' . $record->appraise->city_name) : '';
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.address')
                    ->label('种质原产地址')
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
            ->searchPlaceholder('搜索品种权号、品种权人等...')
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
            'index' => Pages\ListNewVarieties::route('/'),
            'create' => Pages\CreateNewVariety::route('/create'),
            'edit' => Pages\EditNewVariety::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
