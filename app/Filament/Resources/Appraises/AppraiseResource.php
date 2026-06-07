<?php

namespace App\Filament\Resources\Appraises;

use App\Enums\Appraises\Status;
use App\Features\Nhgrc\Nhgrc;
use App\Filament\Forms\Fields\DistrictSelect;
use App\Filament\Resources\Appraises\Actions\QrCodeAction;
use App\Filament\Resources\Appraises\Exports\AppraiseExporter;
use App\Filament\Resources\Appraises\Schemas\AppraiseInfolist;
use App\Filament\Resources\Companies\CompanyResource;
use App\Filament\Resources\Companies\Schemas\CompanyForm;
use App\Filament\Resources\Concerns\HasCategoryFields;
use App\Models\Appraise;
use App\Settings\AppraiseSettings;
use BackedEnum;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Actions;
use Filament\Actions\ExportAction as FilamentExportAction;
use Filament\Actions\ExportBulkAction as FilamentExportBulkAction;
use Filament\Forms;
use Filament\Notifications\Notification;
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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;
use Livewire\Component as Livewire;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;
use UnitEnum;
use Wsmallnews\Support\Filament\Filters\FilterComponents;
use Wsmallnews\Support\Filament\Forms\FormComponents;

class AppraiseResource extends Resource
{
    use HasCategoryFields;

    protected static ?string $model = Appraise::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::CircleStack;

    protected static ?string $navigationLabel = '评价';

    protected static string|UnitEnum|null $navigationGroup = '种质资源库(圃)';

    protected static ?string $slug = 'appraises';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '评价';

    protected static ?string $pluralModelLabel = '评价';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Tabs::make('Tabs')
                            ->tabs(function (Get $get) {
                                return [
                                    Schemas\Components\Tabs\Tab::make('基础信息')
                                        ->schema([
                                            ...self::getBaseSchema($get),
                                        ]),
                                    ...self::getCategoryTabs($get),
                                ];
                            })
                            ->afterStateHydrated(function (Schemas\Components\Tabs $component, ?array $state) {
                                self::hydratedFields($component, $state);
                            })
                            ->key('dynamicTabs')
                            ->columns(1)->columnSpan(2),
                    ])->columns(1),
                    Schemas\Components\Section::make('状态')->schema([
                        Forms\Components\TextInput::make('order_column')->label('排序')->integer()
                            ->placeholder('正序排列')
                            ->rules(['integer', 'min:0']),
                        Forms\Components\Radio::make('status')
                            ->label('评价状态')
                            ->default(Status::Normal)
                            ->inline()
                            ->options(Status::class),
                    ])->grow(false),
                ])
                    ->columnSpanFull()
                    ->from('lg'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AppraiseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('分类')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('cover')
                    ->label('封面图')
                    ->collection('cover')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('resource_no')
                    ->label('全国统一编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('germplasm_no')
                    ->label('种质圃编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('original_no')
                    ->label('引种号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('gather_no')
                    ->label('采集号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('种质名称')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('en_name')
                    ->label('种质外文名')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('subject_name')
                    ->label('科名')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('genus_name')
                    ->label('属名')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('species_name')
                    ->label('学名')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('country_name')
                    ->label('原产国')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('district_name')
                    ->label('原产地区')
                    ->searchable()
                    ->state(function (Model $record): string {
                        if ($record->country_code == 'CN') {
                            return $record->province_name.' / '.$record->city_name;
                        }

                        return '/';
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('原产地址')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('altitude')
                    ->label('海拔')
                    ->suffix('米')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('lng_lat')
                    ->label('经纬度')
                    ->state(function (Model $record): string {
                        return $record->longitude.', '.$record->latitude;
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('source_country_name')
                    ->label('来源国')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('source_district_name')
                    ->label('来源地区')
                    ->searchable()
                    ->state(function (Model $record): string {
                        if ($record->source_country_code == 'CN') {
                            return $record->source_province_name.' / '.$record->source_city_name;
                        }

                        return '/';
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('source_address')
                    ->label('来源地址')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('saveCompany.name')
                    ->formatStateUsing(fn ($record) => $record?->saveCompany ? "{$record->saveCompany->name} (编号：{$record->saveCompany->code})" : null)
                    ->searchable()
                    ->label('保存单位')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('pedigree')
                    ->label('系谱')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('breedingCompany.name')
                    ->formatStateUsing(fn ($record) => $record?->breedingCompany ? "{$record->breedingCompany->name} (编号：{$record->breedingCompany->code})" : null)
                    ->searchable()
                    ->label('选育单位')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('cultivationd_at')
                    ->label('育成年份')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('breeding_method')
                    ->label('选育方法')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('germplasm_type')
                    ->label('种质类型')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('germplasm_use')
                    ->label('种质用途')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('fruit_use')
                    ->label('果实用途')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('plant_use')
                    ->label('植株用途')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assemble_resource')
                    ->label('种植收集源')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assemble_material_type')
                    ->label('收集材料类型')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('observe_place')
                    ->label('观测地点')
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
            ->searchPlaceholder('搜索种质名称、种质圃编号等...')
            ->filtersFormWidth(Width::Medium)
            ->filters([
                FilterComponents::dateTimeRangeFilter('cultivationd_at', '育成'),
                ...FilterComponents::createUpdateRangeFilter(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                FilamentExportAction::make()
                    ->exporter(AppraiseExporter::class)
                    ->icon(Heroicon::ArrowDownTray)
                    ->color('gray'),
            ])
            ->recordActions([
                Actions\Action::make('submit')
                    ->label('提交园艺库')
                    ->action(function (Model $record) {
                        try {
                            $nhgrc = new Nhgrc;
                            $result = $nhgrc->submitGermplasm($record);

                            Notification::make()
                                ->title('提交成功')
                                ->body($result['msg'])
                                ->success()->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('提交失败')
                                ->body($e->getMessage())
                                ->danger()->send();
                        }
                    }),
                QrCodeAction::make(),
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkAction::make('submit')
                    ->label('提交园艺库')
                    ->action(function (Collection $records) {
                        try {
                            $nhgrc = new Nhgrc;
                            $result = $nhgrc->batchSubmitGermplasm($records);

                            Notification::make()
                                ->title('提交成功')
                                ->body($result['msg'])
                                ->success()->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('提交失败')
                                ->body($e->getMessage())
                                ->danger()->send();
                        }
                    }),
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('downloadQrCodes')
                        ->label('下载二维码')
                        ->icon(Heroicon::QrCode)
                        ->action(function (Collection $records) {
                            $this->redirect(route('admin.appraises.batch-download-qrcode', ['ids' => $records->pluck('id')->toArray()]));
                        }),

                    // Actions\BulkAction::make('downloadQrCodesaa')
                    //     ->label('下载二维码aa')
                    //     ->icon(Heroicon::QrCode)
                    //     ->action(function (Collection $records) {
                    //         $zipPath = QrCodeService::generateAppraiseQrZip($records);
                    //         $zipFilename = '种质评价二维码_'.now()->format('YmdHis').'.zip';

                    //         return response()->streamDownload(function () use ($zipPath) {
                    //             readfile($zipPath);
                    //         }, $zipFilename, [
                    //             'Content-Type' => 'application/zip',
                    //         ])->deleteFileAfterSend(true);
                    //     }),
                    FilamentExportBulkAction::make()
                        ->exporter(AppraiseExporter::class)
                        ->icon(Heroicon::ArrowDownTray)
                        ->color('gray'),
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
            'index' => Pages\ListAppraises::route('/'),
            'create' => Pages\CreateAppraise::route('/create'),
            'view' => Pages\ViewAppraise::route('/{record}'),
            'edit' => Pages\EditAppraise::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    private static function getBaseSchema(): array
    {
        return [
            Schemas\Components\Section::make('基础信息')->schema([
                // 单选
                SelectTree::make('category_id')->label('选择分类')
                    ->relationship(relationship: 'category', titleAttribute: 'name', parentAttribute: 'parent_id', modifyQueryUsing: function ($query) {
                        return $query->scopeable('appraise', 0);
                    }, modifyChildQueryUsing: function ($query) {
                        return $query->scopeable('appraise', 0);
                    })
                    ->searchable()
                    ->enableBranchNode()
                    ->withCount()
                    ->live()
                    ->afterStateUpdated(function (Livewire $livewire) {     // 字段更新后触发，只能前端更新才会触发，$set 更新该无效
                        // 动态变动新增的字段必须通过调用 fill 主动填充; 文档: https://filamentphp.com/docs/3.x/forms/advanced#dynamic-fields-based-on-a-select-option
                        $tabs = $livewire->form->getComponent('dynamicTabs')
                            ?->getChildSchema()
                            ->getComponents();      // 这里获取的是 整个 tabs 数组, 直接 fill 填充 整个 tabs 好像不行

                        foreach ($tabs as $key => $tab) {
                            if ($key >= 1) {
                                $tab?->getChildSchema()?->fill();
                            }
                        }
                    })
                    ->required()
                    ->disabled(fn (string $operation) => $operation == 'edit')
                    ->placeholder('请选择分类')
                    ->emptyLabel('未搜索到分类')
                    ->treeKey('AppraiseCategoryId'),
                Forms\Components\TextInput::make('resource_no')->label('全国统一编号')
                    ->placeholder('请输入全国统一编号')
                    ->required(),
                Forms\Components\TextInput::make('germplasm_no')->label('种质圃编号')
                    ->placeholder('请输入种质圃编号')
                    ->required(),
                Forms\Components\TextInput::make('original_no')->label('引种号')
                    ->placeholder('请输入引种号')
                    ->required(),
                Forms\Components\TextInput::make('gather_no')->label('采集号')
                    ->placeholder('请输入采集号')
                    ->required(),
                Forms\Components\TextInput::make('name')->label('种质名称')
                    ->placeholder('请输入种质名称')
                    ->required(),
                Forms\Components\TextInput::make('en_name')->label('种质外文名')
                    ->placeholder('请输入种质外文名')
                    ->required(),
                Forms\Components\TextInput::make('subject_name')->label('科名')
                    ->placeholder('请输入科名')
                    ->required(),
                Forms\Components\TextInput::make('genus_name')->label('属名')
                    ->placeholder('请输入属名')
                    ->required(),
                Forms\Components\TextInput::make('species_name')->label('学名')
                    ->placeholder('请输入学名')
                    ->required(),
            ])->columns(2),
            Schemas\Components\Section::make('地理信息')->schema([
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
                    ->visible(fn (Get $get): bool => $get('country_code') == 'CN'),
                Forms\Components\TextInput::make('address')->label('原产地')
                    ->placeholder('请输入原产地址')
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
                Forms\Components\TextInput::make('source_address')->label('来源地')
                    ->placeholder('请输入来源地址')
                    ->required(),
            ])->columns(2),
            Schemas\Components\Section::make('保存信息')->schema([
                Forms\Components\Select::make('save_company_id')->label('保存单位')
                    ->relationship(name: 'saveCompany', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                        return $query->normal()->orderBy('order_column', 'asc');
                    })
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} (编号：{$record->code})")
                    ->createOptionForm(fn ($schema) => CompanyForm::configure($schema))
                    ->createOptionUsing(function (Forms\Components\Select $component, array $data, Schema $schema) {
                        $data = CompanyResource::operDistrictInfo($data);     // 处理省市区数据

                        $record = $component->getRelationship()->getRelated();
                        $record->fill($data);
                        $record->save();
                        $schema->model($record)->saveRelationships();

                        return $record->getKey();
                    })
                    ->placeholder('请选择保存单位')
                    ->searchable(['name', 'code'])
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('pedigree')->label('系谱')
                    ->placeholder('请输入系谱')
                    ->required(),
                Forms\Components\Select::make('breeding_company_id')->label('选育单位')
                    ->relationship(name: 'breedingCompany', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                        return $query->normal()->orderBy('order_column', 'asc');
                    })
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} (编号：{$record->code})")
                    ->createOptionForm(fn ($schema) => CompanyForm::configure($schema))
                    ->createOptionUsing(function (Forms\Components\Select $component, array $data, Schema $schema) {
                        $data = CompanyResource::operDistrictInfo($data);     // 处理省市区数据

                        $record = $component->getRelationship()->getRelated();
                        $record->fill($data);
                        $record->save();
                        $schema->model($record)->saveRelationships();

                        return $record->getKey();
                    })
                    ->placeholder('请选择选育单位')
                    ->searchable(['name', 'code'])
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('cultivationd_at')->label('育成年份')
                    ->placeholder('请选择育成年份')
                    ->native(false)
                    ->displayFormat('Y-m')
                    ->required(),
                Forms\Components\TextInput::make('breeding_method')->label('选育方法')
                    ->placeholder('请输入选育方法')
                    ->required(),
            ])->columns(2),
            // 新增种质特性部分
            Schemas\Components\Section::make('种质特性')->schema([
                Forms\Components\Select::make('germplasm_type')->label('种质类型')
                    ->placeholder('请选择种质类型')
                    ->required()
                    ->options(fn (AppraiseSettings $settings) => Arr::mapWithKeys($settings->germplasm_type, function ($item) {
                        return [$item => $item];
                    })),
                Forms\Components\Select::make('germplasm_use')->label('用途')
                    ->placeholder('请选择用途')
                    ->required()
                    ->options(fn (AppraiseSettings $settings) => Arr::mapWithKeys($settings->germplasm_use, function ($item) {
                        return [$item => $item];
                    })),
                Forms\Components\Select::make('fruit_use')->label('果实用途')
                    ->placeholder('请选择果实用途')
                    ->required()
                    ->options(fn (AppraiseSettings $settings) => Arr::mapWithKeys($settings->fruit_use, function ($item) {
                        return [$item => $item];
                    })),
                Forms\Components\Select::make('plant_use')->label('植株用途')
                    ->placeholder('请选择植株用途')
                    ->required()
                    ->options(fn (AppraiseSettings $settings) => Arr::mapWithKeys($settings->plant_use, function ($item) {
                        return [$item => $item];
                    })),
                Forms\Components\Select::make('assemble_resource')->label('种植收集源')
                    ->placeholder('请选择种植收集源')
                    ->required()
                    ->options(fn (AppraiseSettings $settings) => Arr::mapWithKeys($settings->assemble_resource, function ($item) {
                        return [$item => $item];
                    })),
                Forms\Components\Select::make('assemble_material_type')->label('收集材料类型')
                    ->placeholder('请选择收集材料类型')
                    ->required()
                    ->options(fn (AppraiseSettings $settings) => Arr::mapWithKeys($settings->assemble_material_type, function ($item) {
                        return [$item => $item];
                    })),
                Forms\Components\TextInput::make('observe_place')->label('观测地点')
                    ->placeholder('请输入观测地点')
                    ->required(),
            ])->columns(2),
            Schemas\Components\Section::make('图集管理')->schema([
                FormComponents::mediaImageUpload('cover', 'cover')->label('封面图')
                    ->helperText('支持上传图片')
                    ->required()

                    ->uploadingMessage('封面上传中...')
                    ->columns(1),
                FormComponents::mediaImageUpload('galleries', 'galleries')->label('详情图')
                    ->helperText('支持上传多张图片')
                    ->required()
                    ->multiple()
                    ->minFiles(1)
                    ->maxFiles(20)
                    ->uploadingMessage('详情图上传中...')
                    ->columns(1),
            ])->columns(2),
        ];
    }

    /**
     * 保存前处理数据,然后处理结果保存到数据库，CreateAppraise & EditAppraise 中调用
     */
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
