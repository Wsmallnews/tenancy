<?php

namespace App\Filament\Resources\Catalogs;

use BackedEnum;
use App\Enums\Catalogs\Status;
use App\Filament\Forms\Fields\DistrictSelect;
use App\Filament\Resources\Catalogs\Pages;
use App\Models\Appraise;
use App\Models\Catalog;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;
use UnitEnum;

class CatalogResource extends Resource
{
    protected static ?string $model = Catalog::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = '编目';

    protected static string | UnitEnum | null $navigationGroup = '种质资源库(圃)';

    protected static ?string $slug = 'catalogs';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '编目';

    protected static ?string $pluralModelLabel = '编目';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Section::make('种质信息')->schema([
                            Forms\Components\Select::make('appraise_id')->label('选择种质')
                                ->relationship(name: 'appraise', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                    return $query->normal()->orderBy('order_column', 'asc');
                                })
                                ->placeholder('请选择种质')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->required(),
                            Schemas\Components\Grid::make([
                                    'default' => 1,
                                    'lg' => 2,
                                    'xl' => 3,
                                ])
                                ->extraAttributes([
                                    'class' => 'sn-grid-table',
                                ])
                                ->schema(function(Get $get) {
                                    if ($get('appraise_id') && $appraise = Appraise::findOrFail($get('appraise_id'))) {
                                        $coverMedia = $appraise->getFirstMedia('cover');

                                        return [
                                            Infolists\Components\ImageEntry::make('appraise_cover')
                                                ->label('种质封面图')
                                                ->state($coverMedia?->getFullUrl())
                                                ->extraAttributes([
                                                    'class' => 'sn-two-rows'
                                                ]),
                                            Infolists\Components\TextEntry::make('appraise_resource_no')
                                                ->label('全国统一编号')
                                                ->state($appraise->resource_no),
                                            Infolists\Components\TextEntry::make('appraise_name')
                                                ->label('种质中文名')
                                                ->state($appraise->name),
                                            Infolists\Components\TextEntry::make('appraise_en_name')
                                                ->label('种质外文名')
                                                ->state($appraise->en_name),
                                            Infolists\Components\TextEntry::make('appraise_germplasm_no')
                                                ->label('种质圃编号')
                                                ->state($appraise->germplasm_no),
                                            Infolists\Components\TextEntry::make('appraise_germplasm_type')
                                                ->label('种质类型')
                                                ->state($appraise->germplasm_type),
                                            Infolists\Components\TextEntry::make('appraise_subject_name')
                                                ->label('科名')
                                                ->state($appraise->subject_name),
                                            Infolists\Components\TextEntry::make('appraise_genus_name')
                                                ->label('属名')
                                                ->state($appraise->genus_name),
                                            Infolists\Components\TextEntry::make('appraise_species_name')
                                                ->label('学名')
                                                ->state($appraise->species_name),
                                        ];
                                    }
                                })
                                ->visible(fn(Get $get): bool => boolval($get('appraise_id')))
                                ->columnSpanFull(),
                        ]),
                        Schemas\Components\Section::make('编目信息')->schema([
                            Forms\Components\TextInput::make('name')->label('作物名称')
                                ->placeholder('请输入作物名称')
                                ->required(),
                            Forms\Components\TextInput::make('code_type')->label('编码类型')
                                ->placeholder('请输入编码类型')
                                ->required(),
                            Forms\Components\TextInput::make('assemble_no')->label('收集编号')
                                ->placeholder('请输入收集编号')
                                ->required(),
                            Forms\Components\TextInput::make('original_no')->label('原始编号')
                                ->placeholder('请输入原始编号')
                                ->required(),
                            Forms\Components\DatePicker::make('assemble_at')->label('收集日期')
                                ->placeholder('请选择收集日期')
                                ->native(false)
                                ->displayFormat('Y-m-d')
                                ->required(),
                            Forms\Components\TextInput::make('resource_method')->label('资源来源方式')
                                ->placeholder('请输入资源来源方式')
                                ->required(),
                            Forms\Components\DatePicker::make('catalog_at')->label('编目时间')
                                ->placeholder('请选择编目时间')
                                ->native(false)
                                ->displayFormat('Y-m-d')
                                ->required(),
                        ])->columns(2),

                        Schemas\Components\Section::make('地址信息')->schema([
                            // 选择国家，省市区
                            Country::make('country_code')->label('选择原产国')
                                ->default('CN')
                                ->live()
                                ->options(
                                    // @sn todo 插件 bug 临时解决办法
                                    Country::make('country_code')->getOptions()
                                )
                                ->afterStateUpdated(function (Set $set, Country $component, $state) {
                                    $country_name = $component->getCountriesList()[$state] ?? null;
                                    $set('country_name', $country_name);
                                }),
                            Forms\Components\Hidden::make('country_name')
                                ->default('中国'),
                            DistrictSelect::make('district')
                                ->label('原产地区')
                                ->placeholder('选择原产省市')
                                ->district(false)
                                ->required()
                                ->visible(fn(Get $get): bool => $get('country_code') == 'CN'),
                            Forms\Components\TextInput::make('address')->label('原产地')
                                ->placeholder('请输入原产地址')
                                ->required(),
                            Country::make('source_country_code')->label('选择来源国')
                                ->default('CN')
                                ->live()
                                ->options(
                                    // @sn todo 插件 bug 临时解决办法
                                    Country::make('source_country_code')->getOptions()
                                )
                                ->afterStateUpdated(function (Set $set, Country $component, $state) {
                                    $source_country_name = $component->getCountriesList()[$state] ?? null;
                                    $set('source_country_name', $source_country_name);
                                }),
                            Forms\Components\Hidden::make('source_country_name')
                                ->default('中国'),
                            DistrictSelect::make('source_district')
                                ->label('来源地区')
                                ->placeholder('选择来源省市')
                                ->district(false)
                                ->afterStateHydrated(function (DistrictSelect $component, ?array $state) {
                                    $record = $component->getRecord();
                                    if (! $record) {
                                        $component->state($state);
                                        return;
                                    }
                                    $component->state([
                                        'province_name' => $record->source_province_name ?? null,
                                        'province_id' => $record->source_province_id ?? null,
                                        'city_name' => $record->source_city_name ?? null,
                                        'city_id' => $record->source_city_id ?? null,
                                    ]);
                                })
                                ->required()
                                ->visible(fn (Get $get): bool => $get('source_country_code') == 'CN'),
                            Forms\Components\TextInput::make('source_address')->label('来源地址')
                                ->placeholder('请输入来源地址')
                                ->required(),
                            Forms\Components\TextInput::make('altitude')->label('海拔')
                                ->integer()
                                ->placeholder('请输入海拔')
                                ->suffix('米')
                                ->rules(['integer'])
                                ->required(),
                            Forms\Components\TextInput::make('longitude')->label('经度')
                                ->placeholder('请输入经度')
                                ->required(),
                            Forms\Components\TextInput::make('latitude')->label('纬度')
                                ->placeholder('请输入纬度')
                                ->required(),
                        ])->columns(2),

                        Schemas\Components\Section::make('收集信息')->schema([
                            Forms\Components\TextInput::make('assemble_address')->label('收集地点')
                                ->placeholder('请输入收集地点')
                                ->required(),
                            Forms\Components\Select::make('assemble_company_id')->label('收集单位')
                                ->relationship(name: 'assembleCompany', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                    return $query->normal()->orderBy('order_column', 'asc');
                                })
                                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} (编号：{$record->code})")
                                ->createOptionForm(fn ($schema) => \App\Filament\Resources\Companies\Schemas\CompanyForm::configure($schema))
                                ->createOptionUsing(function (Forms\Components\Select $component, array $data, Schema $schema) {
                                    $data = \App\Filament\Resources\Companies\CompanyResource::operDistrictInfo($data);     // 处理省市区数据

                                    $record = $component->getRelationship()->getRelated();
                                    $record->fill($data);
                                    $record->save();
                                    $schema->model($record)->saveRelationships();
                                    return $record->getKey();
                                })
                                ->placeholder('请选择收集单位')
                                ->searchable(['name', 'code'])
                                ->preload()
                                ->required(),
                            Forms\Components\TextInput::make('assember')->label('收集者')
                                ->placeholder('请输入收集者')
                                ->required(),
                            Forms\Components\TextInput::make('assember_phone')->label('收集者手机号')
                                ->placeholder('请输入收集者手机号')
                                ->required(),
                            Forms\Components\TextInput::make('provider')->label('提供者')
                                ->placeholder('请输入提供者')
                                ->required(),
                            Forms\Components\TextInput::make('provider_phone')->label('提供者手机号')
                                ->placeholder('请输入提供者手机号')
                                ->required(),

                            Forms\Components\Select::make('temp_save_company_id')->label('临时保存单位')
                                ->relationship(name: 'tempSaveCompany', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                    return $query->normal()->orderBy('order_column', 'asc');
                                })
                                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} (编号：{$record->code})")
                                ->createOptionForm(fn ($schema) => \App\Filament\Resources\Companies\Schemas\CompanyForm::configure($schema))
                                ->createOptionUsing(function (Forms\Components\Select $component, array $data, Schema $schema) {
                                    $data = \App\Filament\Resources\Companies\CompanyResource::operDistrictInfo($data);     // 处理省市区数据

                                    $record = $component->getRelationship()->getRelated();
                                    $record->fill($data);
                                    $record->save();
                                    $schema->model($record)->saveRelationships();
                                    return $record->getKey();
                                })
                                ->placeholder('请选择临时保存单位')
                                ->searchable(['name', 'code'])
                                ->preload()
                                ->required(),
                            Forms\Components\Select::make('original_save_company_id')->label('原保存单位')
                                ->relationship(name: 'originalSaveCompany', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                    return $query->normal()->orderBy('order_column', 'asc');
                                })
                                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} (编号：{$record->code})")
                                ->createOptionForm(fn ($schema) => \App\Filament\Resources\Companies\Schemas\CompanyForm::configure($schema))
                                ->createOptionUsing(function (Forms\Components\Select $component, array $data, Schema $schema) {
                                    $data = \App\Filament\Resources\Companies\CompanyResource::operDistrictInfo($data);     // 处理省市区数据

                                    $record = $component->getRelationship()->getRelated();
                                    $record->fill($data);
                                    $record->save();
                                    $schema->model($record)->saveRelationships();
                                    return $record->getKey();
                                })
                                ->placeholder('请选择原保存单位')
                                ->searchable(['name', 'code'])
                                ->preload()
                                ->required(),
                            Forms\Components\TextInput::make('inspect_assemble_project')->label('考察收集项目')
                                ->placeholder('请输入考察收集项目')
                                ->required(),
                        ])->columns(2)
                    ])->columns(1),
                    Schemas\Components\Section::make('状态')->schema([
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
                    ->label('作物名称')
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

                Tables\Columns\TextColumn::make('code_type')
                    ->label('编码类型')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assemble_no')
                    ->label('收集编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('original_no')
                    ->label('原始编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assemble_at')
                    ->label('收集日期')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('catalog_at')
                    ->label('编目时间')
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
            ->reorderable('order_column')
            ->defaultSort('order_column', 'asc')
            ->searchPlaceholder('搜索保存编号、保存位置等...')
            ->filtersFormWidth(Width::Medium)
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->schema([
                        Schemas\Components\Group::make()->schema([
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
                    ->schema([
                        Schemas\Components\Group::make()->schema([
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
            ->recordActions([
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCataLogs::route('/'),
            'create' => Pages\CreateCatalog::route('/create'),
            'edit' => Pages\EditCatalog::route('/{record}/edit'),
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

        $sourceDistrict = $data['source_district'] ?? [];
        $data['source_province_name'] = $sourceDistrict['province_name'] ?? null;
        $data['source_province_id'] = $sourceDistrict['province_id'] ?? null;
        $data['source_city_name'] = $sourceDistrict['city_name'] ?? null;
        $data['source_city_id'] = $sourceDistrict['city_id'] ?? null;
        unset($data['source_district']);

        return $data;
    }
}
