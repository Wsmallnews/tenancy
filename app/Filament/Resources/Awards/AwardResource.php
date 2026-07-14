<?php

namespace App\Filament\Resources\Awards;

use App\Enums\Awards\Status;
use App\Filament\Resources\Awards\Exports\AwardExporter;
use App\Filament\Resources\Awards\Schemas\AwardInfolist;
use App\Filament\Resources\AwardTypes\Schemas\AwardTypeForm;
use App\Models\Award;
use BackedEnum;
use Filament\Actions;
use Filament\Actions\ExportAction as FilamentExportAction;
use Filament\Actions\ExportBulkAction as FilamentExportBulkAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;
use Wsmallnews\Support\Filament\Filters\FilterComponents;
use Wsmallnews\Support\Filament\Forms\FormComponents;

class AwardResource extends Resource
{
    protected static ?string $model = Award::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Trophy;

    protected static ?string $navigationLabel = '奖项';

    protected static string|UnitEnum|null $navigationGroup = '研究成果';

    protected static ?string $slug = 'awards';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '奖项';

    protected static ?string $pluralModelLabel = '奖项';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Section::make('基础信息')->schema([
                            Forms\Components\TextInput::make('name')->label('奖项名称')
                                ->placeholder('请输入奖项名称')
                                ->required(),
                            Forms\Components\Select::make('award_type_id')->label('选择奖项类型')
                                ->relationship(name: 'awardType', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                    return $query->normal()->orderBy('order_column', 'asc');
                                })
                                ->createOptionForm(fn ($schema) => AwardTypeForm::configure($schema))
                                ->placeholder('请选择奖项类型')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\TextInput::make('award_agency')->label('授奖机构')
                                ->placeholder('请输入授奖机构')
                                ->required(),
                            Forms\Components\TextInput::make('level')->label('奖项级别')
                                ->placeholder('请输入奖项级别')
                                ->required(),
                            Forms\Components\TextInput::make('award_name')->label('获奖人/团队')
                                ->placeholder('请输入获奖人/团队')
                                ->required(),
                            Forms\Components\Textarea::make('remark')->label('备注'),
                        ])->columns(2),
                        Schemas\Components\Section::make('证书管理')->schema([
                            FormComponents::mediaFileUpload('certs', 'certs')->label('上传证书')
                                ->helperText('支持上传证书图片或者 PDF 格式的证书文件')
                                ->required()
                                ->multiple()
                                ->minFiles(1)
                                ->maxFiles(20)
                                ->acceptedFileTypes(['application/pdf', 'image/*'])
                                ->uploadingMessage('证书上传中...')
                                ->panelLayout('compact')
                                ->columns(1),
                        ])->columns(1),
                    ])->columns(1),
                    Schemas\Components\Section::make('状态')->schema([
                        Forms\Components\DatePicker::make('award_at')->label('获奖日期')
                            ->placeholder('请选择获奖日期')
                            ->native(false)
                            ->required(),
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
                    ->from('lg'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AwardInfolist::configure($schema);
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
                Tables\Columns\TextColumn::make('name')
                    ->label('奖项名称')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),
                Tables\Columns\TextColumn::make('awardType.name')
                    ->label('奖项类型')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('award_agency')
                    ->searchable()
                    ->label('授奖机构')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('award_at')
                    ->label('获奖日期')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('级别')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('award_name')
                    ->label('获奖人/团队')
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
            ->searchPlaceholder('搜索奖项名称、授权机构等...')
            ->filtersFormWidth(Width::Medium)
            ->filters([
                FilterComponents::dateTimeRangeFilter('award_at', '获奖'),
                ...FilterComponents::createUpdateRangeFilter(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                FilamentExportAction::make()
                    ->exporter(AwardExporter::class)
                    ->icon(Heroicon::ArrowDownTray)
                    ->color('gray'),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    FilamentExportBulkAction::make()
                        ->exporter(AwardExporter::class)
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
            'index' => Pages\ListAwards::route('/'),
            'create' => Pages\CreateAward::route('/create'),
            'view' => Pages\ViewAward::route('/{record}'),
            'edit' => Pages\EditAward::route('/{record}/edit'),
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
