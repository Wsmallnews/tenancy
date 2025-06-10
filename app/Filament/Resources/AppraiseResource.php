<?php

namespace App\Filament\Resources;

use App\Enums\Appraises\Status;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                        })->columns(1)->columnSpan(2),
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
                // Tables\Columns\TextColumn::make('name')
                //     ->label('奖项名称')
                //     ->searchable()
                //     ->limit(50)
                //     ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                //         $state = $column->getState();

                //         if (strlen($state) <= $column->getCharacterLimit()) {
                //             return null;
                //         }

                //         return $state;
                //     }),
                // Tables\Columns\TextColumn::make('awardType.name')
                //     ->label('奖项类型')
                //     ->searchable()
                //     ->toggleable(),
                // Tables\Columns\TextColumn::make('award_agency')
                //     ->searchable()
                //     ->label('授奖机构')
                //     ->toggleable(),
                // Tables\Columns\TextColumn::make('award_at')
                //     ->label('获奖日期')
                //     ->toggleable()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('level')
                //     ->label('级别')
                //     ->toggleable(),
                // Tables\Columns\TextColumn::make('award_name')
                //     ->label('获奖人/团队')
                //     ->toggleable(),
                // Tables\Columns\TextColumn::make('order_column')
                //     ->label('排序')
                //     ->toggleable(),
                // Tables\Columns\TextColumn::make('status')
                //     ->label('状态')
                //     ->toggleable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->label('创建时间')
                //     ->toggleable()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->label('更新时间')
                //     ->toggleable()
                //     ->sortable(),
            ])
            ->deferFilters()        // 延迟过滤,用户点击 apply 按钮后才会应用过滤器
            ->reorderable('order_column')
            ->defaultSort('order_column', 'asc')
            ->searchPlaceholder('搜索评价名称、资源编号等...')
            ->filtersFormWidth(MaxWidth::Medium)
            ->filters([
                // Tables\Filters\Filter::make('award_at')
                //     ->form([
                //         Forms\Components\Group::make()->schema([
                //             Forms\Components\DatePicker::make('award_from')->label('获奖开始时间')->columnSpan(1),
                //             Forms\Components\DatePicker::make('award_until')->label('获奖结束时间')->columnSpan(1),
                //         ])->columns(2),
                //     ])
                //     ->query(function (Builder $query, array $data): Builder {
                //         return $query
                //             ->when(
                //                 $data['award_from'],
                //                 fn(Builder $query, $date): Builder => $query->whereDate('award_at', '>=', $date),
                //             )
                //             ->when(
                //                 $data['award_until'],
                //                 fn(Builder $query, $date): Builder => $query->whereDate('award_at', '<=', $date),
                //             );
                //     }),
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
                    ->enableBranchNode()
                    ->withCount()
                    ->live()
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

                Forms\Components\TextInput::make('address')->label('地址')
                    ->placeholder('请输入地址')
                    ->required(),
                Forms\Components\TextInput::make('altitude')->label('海拔')
                    ->integer()
                    ->placeholder('请输入海拔')
                    ->rules(['integer'])
                    ->required(),
                Forms\Components\TextInput::make('longitude')->label('经度')
                    ->placeholder('请输入经度')
                    ->required(),
                Forms\Components\TextInput::make('latitude')->label('纬度')
                    ->placeholder('请输入纬度')
                    ->required(),

                // 选择来源国家，省市区
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
                            $schemas[] = Forms\Components\TextInput::make('options.fields.' . $key . '.fields.' . $subKey .  '.value')
                                ->label($subField['name'])
                                ->placeholder($subField['placeholder'] ?? null)
                                ->suffix($subField['unit']?? null);
                        }

                        return $schemas;
                    })
                    ->columns(2);
            }
        }

        return $tabs;
    }


    public static function getFieldsInfo($data): array
    {
        $currentOptions = $data['options']?? [];
        $currentFields = $currentOptions['fields'] ?? [];
        $category_id = $data['category_id'];

        if ($category_id) {
            $category = Category::findOrFail($category_id);
            $fields = $category->options['fields'] ?? [];

            foreach ($fields as $key => $field) {
                foreach ($field['fields'] as $subKey => $subField) {
                    $fields[$key]['fields'][$subKey]['value'] = $currentFields[$key]['fields'][$subKey]['value']?? null;
                }
            }
            $currentOptions['fields'] = $fields;
        }

        return $currentOptions;
    } 
}
