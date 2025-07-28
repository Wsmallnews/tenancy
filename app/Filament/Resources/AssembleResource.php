<?php

namespace App\Filament\Resources;

use App\Enums\Assembles\Status;
use App\Filament\Forms\Fields\DistrictSelect;
use App\Filament\Resources\AssembleResource\Pages;
use App\Models\Appraise;
use App\Models\Assemble;
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
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class AssembleResource extends Resource
{
    protected static ?string $model = Assemble::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '收集';

    protected static ?string $navigationGroup = '种质资源库';

    protected static ?string $slug = 'assembles';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '收集';

    protected static ?string $pluralModelLabel = '收集';

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
                                        'count' => 8,      // 图片算两个
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
                                            'field_name' => 'subject_name',
                                            'label' => '科',
                                            'value' => $appraise->subject_name,
                                        ];
                                        $data['fields'][] = [
                                            'type' => 'text',
                                            'field_name' => 'genus_name',
                                            'label' => '属',
                                            'value' => $appraise->genus_name,
                                        ];
                                        $data['fields'][] = [
                                            'type' => 'text',
                                            'field_name' => 'species_name',
                                            'label' => '种',
                                            'value' => $appraise->species_name,
                                        ];
                                    }
                                    return $data;
                                })
                                ->dehydrated(false)
                                ->visible(fn(Get $get): bool => boolval($get('appraise_id')))
                                ->columnSpanFull(),
                        ])->columns(2),
                        Forms\Components\Section::make('收集信息')->schema([
                            Forms\Components\TextInput::make('name')->label('收集人')
                                ->placeholder('请输入收集人')
                                ->required(),
                            Forms\Components\TextInput::make('assemble_no')->label('收集编号')
                                ->placeholder('请输入收集编号')
                                ->required(),
                            Forms\Components\TextInput::make('company')->label('收集单位')
                                ->placeholder('请输入收集单位')
                                ->required(),
                            Forms\Components\TextInput::make('subject_no')->label('所属课题编号')
                                ->placeholder('请输入所属课题编号')
                                ->required(),
                            Forms\Components\TextInput::make('sub_subject_no')->label('所属子课题编号')
                                ->placeholder('请输入所属子课题编号')
                                ->required(),
                        ])->columns(2),
                        Forms\Components\Section::make('收集地信息')->schema([
                            // 选择国家，省市区
                            Country::make('country_code')->label('选择国家')
                                ->default('CN')
                                ->live()
                                ->afterStateUpdated(function (Set $set, Country $component, $state) {
                                    $country_name = $component->getCountriesList()[$state] ?? null;
                                    $set('country_name', $country_name);
                                }),
                            Forms\Components\Hidden::make('country_name')
                                ->default('中国'),
                            DistrictSelect::make('district')
                                ->label('收集地区')
                                ->placeholder('选择省市')
                                ->district(false)
                                ->required()
                                ->visible(fn(Get $get): bool => $get('country_code') == 'CN'),
                            Forms\Components\TextInput::make('address')->label('收集地址')
                                ->placeholder('请输入收集地址')
                                ->required(),
                            Forms\Components\TextInput::make('longitude')->label('经度')
                                ->placeholder('请输入收集地经度')
                                ->required(),
                            Forms\Components\TextInput::make('latitude')->label('纬度')
                                ->placeholder('请输入收集地纬度')
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
                Tables\Columns\TextColumn::make('name')
                    ->label('收集人')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('company')
                    ->label('收集单位')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assemble_no')
                    ->label('收集编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('subject_no')
                    ->label('所属课题编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sub_subject_no')
                    ->label('所属子课题编号')
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
                Tables\Columns\TextColumn::make('country_name')
                    ->label('收集国家')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('district_name')
                    ->label('收集地区')
                    ->searchable()
                    ->state(function (Model $record): string {
                        return $record->province_name . '/' . $record->city_name;
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('收集地址')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->label('经纬度')
                    ->formatStateUsing(function (Model $record, string $state): string {
                        return $record->longitude . ',' . $record->latitude;
                    })
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
            ->searchPlaceholder('搜索收集编号、收集人等...')
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
            'index' => Pages\ListAssembles::route('/'),
            'create' => Pages\CreateAssemble::route('/create'),
            'edit' => Pages\EditAssemble::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function operDistrictInfo($data): array
    {
        $district = $data['district'] ?? [];
        $data['province_name'] = $district['province_name'] ?? null;
        $data['province_id'] = $district['province_id'] ?? null;
        $data['city_name'] = $district['city_name'] ?? null;
        $data['city_id'] = $district['city_id'] ?? null;
        unset($data['district']);

        return $data;
    }
}
