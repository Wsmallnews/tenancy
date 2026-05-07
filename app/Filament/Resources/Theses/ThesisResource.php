<?php

namespace App\Filament\Resources\Theses;

use BackedEnum;
use App\Enums\Theses\Status;
use App\Filament\Resources\Theses\Pages;
use App\Filament\Resources\Theses\Schemas\ThesisInfolist;
use App\Models\Thesis;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Wsmallnews\Support\Helpers\FilamentHelper;
use UnitEnum;

class ThesisResource extends Resource
{
    protected static ?string $model = Thesis::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::AcademicCap;

    protected static ?string $navigationLabel = '论文';

    protected static string | UnitEnum | null $navigationGroup = '研究成果';

    protected static ?string $slug = 'theses';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = '论文';

    protected static ?string $pluralModelLabel = '论文';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Section::make('基础信息')->schema([
                            Forms\Components\Select::make('thesis_type_id')->label('选择论文类型')
                                ->relationship(name: 'thesisType', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                    return $query->normal()->orderBy('order_column', 'asc');
                                })
                                ->createOptionForm(fn ($schema) => \App\Filament\Resources\ThesisTypes\Schemas\ThesisTypeForm::configure($schema))
                                ->placeholder('请选择论文类型')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\TextInput::make('title')->label('标题')
                                ->placeholder('请输入论文标题')
                                ->required(),
                            Forms\Components\TextInput::make('author_name')->label('作者')
                                ->placeholder('请输入论文作者')
                                ->required(),
                            Forms\Components\Select::make('company_id')->label('所属单位')
                                ->relationship(name: 'company', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
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
                                ->placeholder('请选择论文所属单位')
                                ->searchable(['name', 'code'])
                                ->preload()
                                ->required(),
                            Forms\Components\Textarea::make('description')->label('摘要')
                                ->placeholder('请输入论文摘要'),
                            Forms\Components\Textarea::make('remark')->label('备注'),
                        ]),
                        Schemas\Components\Section::make('附件管理')->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('theses')->label('附件')
                                ->collection('theses')
                                ->required()
                                ->multiple()
                                ->downloadable()
                                ->reorderable()
                                ->appendFiles()
                                ->minFiles(1)
                                ->maxFiles(20)
                                ->acceptedFileTypes(['application/pdf'])
                                ->uploadingMessage('附件上传中...')
                                ->columns(1),
                        ]),
                    ])->columns(1),
                    Schemas\Components\Section::make('状态')->schema([
                        Forms\Components\TextInput::make('journal')->label('发布期刊')
                            ->placeholder('请输入论文发布期刊')
                            ->required(),
                        Forms\Components\TextInput::make('issue_number')->label('卷期号')
                            ->placeholder('请输入论文卷期号')
                            ->required(),
                        Forms\Components\DatePicker::make('published_at')->label('出版日期')
                            ->placeholder('请选择出版日期')
                            ->native(false)
                            ->required(),
                        Forms\Components\SpatieTagsInput::make('tags')->label('关键字')->type('keywords'),
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


    public static function infolist(Schema $schema): Schema
    {
        return ThesisInfolist::configure($schema);
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
                Tables\Columns\TextColumn::make('title')
                    ->label('论文标题')
                    ->searchable()
                    ->description(fn($record) => $record->description)
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),
                Tables\Columns\TextColumn::make('thesisType.name')
                    ->label('论文类型')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('author_name')
                    ->searchable()
                    ->label('作者')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->formatStateUsing(fn ($record) => $record?->company ? "{$record->company->name} (编号：{$record->company->code})" : null)
                    ->searchable()
                    ->label('所属单位')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('journal')
                    ->searchable()
                    ->label('发布期刊')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('issue_number')
                    ->searchable()
                    ->label('卷期号')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('出版日期')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\SpatieTagsColumn::make('keywords')
                    ->label('关键字')
                    ->type('keywords')
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
            ->searchPlaceholder('搜索论文标题、作者等...')
            ->filtersFormWidth(Width::Medium)
            ->filters([
                FilamentHelper::dateTimeRangeFilter('published_at', '发布'),
                ...FilamentHelper::createUpdateRangeFilter(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
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
            'index' => Pages\ListTheses::route('/'),
            'create' => Pages\CreateThesis::route('/create'),
            'view' => Pages\ViewThesis::route('/{record}'),
            'edit' => Pages\EditThesis::route('/{record}/edit'),
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
