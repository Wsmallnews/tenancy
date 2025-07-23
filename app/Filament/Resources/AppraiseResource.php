<?php

namespace App\Filament\Resources;

use App\Enums\Appraises\Status;
use App\Filament\Forms\Fields\DistrictSelect;
use App\Filament\Resources\AppraiseResource\Pages;
use App\Models\Appraise;
use App\Models\Category;
use CodeWithDennis\FilamentSelectTree\SelectTree;
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
use Illuminate\Support\Arr;
use Livewire\Component as Livewire;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class AppraiseResource extends Resource
{
    protected static ?string $model = Appraise::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '评价';

    protected static ?string $navigationGroup = '种质资源库';

    protected static ?string $slug = 'appraises';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '评价';

    protected static ?string $pluralModelLabel = '评价';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Tabs::make('Tabs')
                        ->tabs(function (Get $get) {
                            return [
                                Forms\Components\Tabs\Tab::make('基础信息')
                                    ->schema([
                                        ...self::getBaseSchema($get)
                                    ]),
                                ...self::getCategoryTabs($get),
                            ];
                        })
                        ->afterStateHydrated(function (Forms\Components\Tabs $component, ?array $state) {
                            self::hydratedFields($component, $state);
                        })
                        ->key('dynamicTabs')
                        ->columns(1)->columnSpan(2),
                ])->columns(1)->columnSpan(2),
                Forms\Components\Section::make('状态')->schema([
                    Forms\Components\TextInput::make('order_column')->label('排序')->integer()
                        ->placeholder('正序排列')
                        ->rules(['integer', 'min:0']),
                    Forms\Components\Radio::make('status')
                        ->label('评价状态')
                        ->default(Status::Normal)
                        ->inline()
                        ->options(Status::class),
                ])->columns(1)->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('分类')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('cover')
                    ->label('封面图')
                    ->collection('cover')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('resource_no')
                    ->label('种质资源编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('germplasm_no')
                    ->label('种质库编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('original_no')
                    ->label('原始编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('gather_no')
                    ->label('采集号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('中文名')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('en_name')
                    ->label('英文名')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('az_name')
                    ->label('拉丁学名')
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
                    ->label('种名')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('country_name')
                    ->label('国家')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('province_name')
                    ->label('省')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('city_name')
                    ->label('市')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('地址')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('altitude')
                    ->label('海拔')
                    ->suffix('米')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->label('经纬度')
                    ->formatStateUsing(function (Model $record, string $state): string {
                        return $record->longitude . ',' . $record->latitude;
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('source_country_name')
                    ->label('来源国家')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('source_province_name')
                    ->label('来源省')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('source_city_name')
                    ->label('来源市')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('source_address')
                    ->label('来源地址')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('save_company')
                    ->label('保存单位')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('save_company_no')
                    ->label('保存单位编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('pedigree')
                    ->label('系谱')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('breeding_company')
                    ->label('选育单位')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('cultivationd_at')
                    ->label('育成年份')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('breeding_method')
                    ->label('选育方法')
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
            ->searchPlaceholder('搜索评价名称、资源编号等...')
            ->filtersFormWidth(MaxWidth::Medium)
            ->filters([
                Tables\Filters\Filter::make('cultivationd_at')
                    ->form([
                        Forms\Components\Group::make()->schema([
                            Forms\Components\DatePicker::make('cultivationd_from')->label('育成开始时间')->columnSpan(1),
                            Forms\Components\DatePicker::make('cultivationd_until')->label('育成结束时间')->columnSpan(1),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['cultivationd_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('cultivationd_at', '>=', $date),
                            )
                            ->when(
                                $data['cultivationd_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('cultivationd_at', '<=', $date),
                            );
                    }),
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
            'index' => Pages\ListAppraises::route('/'),
            'create' => Pages\CreateAppraise::route('/create'),
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
            Forms\Components\Section::make('基础信息')->schema([
                SelectTree::make('category_id')->label('选择分类')
                    ->relationship(relationship: 'category', titleAttribute: 'name', parentAttribute: 'parent_id')
                    ->searchable()
                    // ->enableBranchNode()     // 可以选择非根节点
                    ->withCount()
                    ->live()
                    ->afterStateUpdated(function (Livewire $livewire) {
                        $tabs = $livewire->form->getComponent('dynamicTabs')
                            ->getChildComponentContainer()
                            ->getComponents();      // 这里获取的是 整个 tabs 数组, 直接 fill 填充 整个 tabs 好像不行

                        foreach ($tabs as $key => $tab) {
                            if ($key >= 1) {
                                $tab->getChildComponentContainer()->fill();
                            }
                        }
                        // return $livewire->form->getComponent('dynamicTabs')
                        //     ->getChildComponentContainer()
                        //     ->fill();
                    })
                    ->required()
                    ->placeholder('请选择分类')
                    ->emptyLabel('未搜索到分类')
                    ->treeKey('AppraiseCategoryId'),
                Forms\Components\TextInput::make('resource_no')->label('种质资源编号')
                    ->placeholder('请输入种质资源编号')
                    ->required(),
                Forms\Components\TextInput::make('germplasm_no')->label('种质库编号')
                    ->placeholder('请输入种质库编号')
                    ->required(),
                Forms\Components\TextInput::make('original_no')->label('原始编号')
                    ->placeholder('请输入原始编号')
                    ->required(),
                Forms\Components\TextInput::make('gather_no')->label('采集号')
                    ->placeholder('请输入采集号')
                    ->required(),
                Forms\Components\TextInput::make('name')->label('中文名')
                    ->placeholder('请输入中文名')
                    ->required(),
                Forms\Components\TextInput::make('en_name')->label('英文名')
                    ->placeholder('请输入英文名')
                    ->required(),
                Forms\Components\TextInput::make('az_name')->label('拉丁学名')
                    ->placeholder('请输入拉丁学名')
                    ->required(),
                Forms\Components\TextInput::make('subject_name')->label('科名')
                    ->placeholder('请输入科名')
                    ->required(),
                Forms\Components\TextInput::make('genus_name')->label('属名')
                    ->placeholder('请输入属名')
                    ->required(),
                Forms\Components\TextInput::make('species_name')->label('种名')
                    ->placeholder('请输入种名')
                    ->required(),
            ])->columns(2),
            Forms\Components\Section::make('地理信息')->schema([
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
                    ->label('地区')
                    ->placeholder('选择省市')
                    ->district(false)
                    ->required()
                    ->visible(fn(Get $get): bool => $get('country_code') == 'CN'),
                Forms\Components\TextInput::make('address')->label('地址')
                    ->placeholder('请输入地址')
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

                Country::make('source_country_code')->label('选择来源国家')
                    ->default('CN')
                    ->live()
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
            ])->columns(2),
            Forms\Components\Section::make('保存信息')->schema([
                Forms\Components\TextInput::make('save_company')->label('保存单位')
                    ->placeholder('请输入保存单位')
                    ->required(),
                Forms\Components\TextInput::make('save_company_no')->label('保存单位编号')
                    ->placeholder('请输入保存单位编号')
                    ->required(),
                Forms\Components\TextInput::make('pedigree')->label('系谱')
                    ->placeholder('请输入系谱')
                    ->required(),
                Forms\Components\TextInput::make('breeding_company')->label('选育单位')
                    ->placeholder('请输入选育单位')
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
            Forms\Components\Section::make('图集管理')->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('cover')->label('封面图')
                    ->helperText('支持上传图片')
                    ->collection('cover')
                    ->required()
                    ->downloadable()
                    ->image()
                    ->imagePreviewHeight('100')
                    ->uploadingMessage('封面上传中...')
                    ->columns(1),
                Forms\Components\SpatieMediaLibraryFileUpload::make('galleries')->label('详情图')
                    ->helperText('支持上传多张图片')
                    ->collection('galleries')
                    ->required()
                    ->multiple()
                    ->downloadable()
                    ->reorderable()
                    ->appendFiles()
                    ->minFiles(1)
                    ->maxFiles(20)
                    ->image()
                    ->imagePreviewHeight('100')
                    ->uploadingMessage('详情图上传中...')
                    ->columns(1),
            ])->columns(2),
        ];
    }


    private static function getCategoryTabs($get): array
    {
        $tabs = [];

        $category_id = $get('category_id');
        if ($category_id) {
            $category = Category::findOrFail($category_id);

            $fields = $category->options['fields'] ?? [];
            foreach ($fields as $key => $field) {
                $tabs[] = Forms\Components\Tabs\Tab::make($field['name'])
                    ->schema(function () use ($key, $field) {
                        $schemas = [];
                        foreach ($field['fields'] as $subKey => $subField) {
                            $fieldKey = 'options.fields.' . $key . '.fields.' . $subKey .  '.data.value';
                            if ($field = static::getFormFields($fieldKey, $subField)) {     // 根据参数获取对应的表单
                                $schemas[] = $field;
                            }

                            // $schemas[] = Forms\Components\TextInput::make('options.fields.' . $key . '.fields.' . $subKey .  '.value')
                            //     ->label($subField['name'])
                            //     ->placeholder($subField['placeholder'] ?? null)
                            //     ->suffix($subField['unit']?? null);
                        }

                        return $schemas;
                    })
                    ->columns(2);
            }
        }

        return $tabs;
    }

    private static function getFormFields($fieldKey, $subField): ?Forms\Components\Field
    {
        $type = $subField['type'] ?? null;
        $data = $subField['data'] ?? [];

        if ($type == 'textInput' || $type == 'number') {
            $regex_message = $data['regex_message'] ?? null;
            $validationMessages = [];
            if ($regex_message) {
                $validationMessages['regex'] = $regex_message;
            }

            $field = Forms\Components\TextInput::make($fieldKey)
                ->label($data['name'] ?? null)
                ->placeholder($data['placeholder'] ?? null)
                ->suffix($data['unit']?? null)
                ->required($data['is_required'] ?? false)
                ->regex($data['regex'] ?? null)
                ->validationMessages($validationMessages);
        } elseif ($type == 'select') {
            $options = $data['options'] ?? [];
            $options = Arr::mapWithKeys($options, function ($item) {
                return [$item => $item];
            });

            $field = Forms\Components\Select::make($fieldKey)
                ->label($data['name'] ?? null)
                ->placeholder($data['placeholder'] ?? null)
                ->suffix($data['unit'] ?? null)
                ->required($data['is_required'] ?? false)
                ->options($options);
        } elseif ($type == 'upload_image') {
            $field = Forms\Components\SpatieMediaLibraryFileUpload::make($fieldKey)
                ->label($data['name'] ?? null)
                ->helperText('支持上传图片')
                ->collection($data['collection_name'] ?? null)
                ->required($data['is_required'] ?? false)
                ->multiple($data['is_multiple'] ?? false)
                ->downloadable()
                ->reorderable()
                ->appendFiles()
                ->minFiles($data['min_files'] ?? 1)
                ->maxFiles((isset($data['max_files_num']) && $data['max_files_num'] > 0) ? $data['max_files_num'] : 20)
                ->image()
                ->imagePreviewHeight('100')
                ->uploadingMessage(($data['name'] ?? '图片') . '上传中...')
                ->columns(1);
        }

        return $field ?? null;
    }

    /**
     * tab 字段水化，保证分类中自定义字段，改变顺序时，数据库中保存的值也能正确显示
     *
     * @param Forms\Components\Tabs $component
     * @param array|null $state
     * @return void
     */
    public static function hydratedFields(Forms\Components\Tabs $component, ?array $state)
    {
        $record = $component->getRecord();
        if (! $record) {
            $component->state($state);
            return;
        }

        // 这里一定要使用 state 中的值 (不可使用 $record 数据库中的值，没有 media 数据),里面已经包括了关联查的数据,比如  laravel-medialibrary 关联的 media 资源标识
        $recordOptions = $state['options'] ?? [];
        $recordFields = $recordOptions['fields'] ?? [];

        $category_id = $state['category_id'];
        if (!$category_id) {
            $component->state($state);
            return;
        }

        $category = Category::findOrFail($category_id);
        $fields = $category->options['fields'] ?? [];       // 分类中的字段，可能更新了

        foreach ($fields as $key => $field) {
            $name = $field['name'] ?? null;
            $currentRecordFields = Arr::where($recordFields, function (array $value, int $key) use ($name) {
                $valueName = $value['name'] ?? null;
                return $valueName == $name && !is_null($valueName);
            });

            $recordField = Arr::first($currentRecordFields);
            if (empty($recordField)) {      // 没有找到数据库中对应的值，说明分类中添加了新的分组，或者老的分组改名了（分组旧值全部无效）
                continue;
            }

            foreach ($field['fields'] as $subKey => $subField) {
                // 首先找到数据库中是否有当前字段信息
                $currentRecordSubFields = Arr::where($recordField['fields'] ?? [], function (array $value, int $key) use ($subField) {
                    $valueName = $value['data']['name'] ?? null;
                    $subFieldName = $subField['data']['name'] ?? null;

                    return $valueName == $subFieldName && !is_null($valueName);
                });

                $recordSubField = Arr::first($currentRecordSubFields);

                if (empty($recordSubField)) {
                    continue;
                }

                $fields[$key]['fields'][$subKey]['data']['value'] = $recordSubField['data']['value'] ?? null;
            }
        }

        $state['options']['fields'] = $fields;

        $component->state($state);
    }


    public static function getFieldsInfo($data): array
    {
        $currentOptions = $data['options'] ?? [];
        $currentFields = $currentOptions['fields'] ?? [];
        $category_id = $data['category_id'];

        if ($category_id) {
            $category = Category::findOrFail($category_id);
            $fields = $category->options['fields'] ?? [];

            foreach ($fields as $key => $field) {
                foreach ($field['fields'] as $subKey => $subField) {
                    $fields[$key]['fields'][$subKey]['data']['value'] = $currentFields[$key]['fields'][$subKey]['data']['value'] ?? null;
                }
            }
            $currentOptions['fields'] = $fields;
        }

        return $currentOptions;
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
