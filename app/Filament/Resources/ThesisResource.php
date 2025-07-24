<?php

namespace App\Filament\Resources;

use App\Enums\Theses\Status;
use App\Filament\Resources\ThesisResource\Pages;
use App\Models\Thesis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThesisResource extends Resource
{
    protected static ?string $model = Thesis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '论文';

    protected static ?string $navigationGroup = '研究成果';

    protected static ?string $slug = 'theses';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = '论文';

    protected static ?string $pluralModelLabel = '论文';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Split::make([
                    Forms\Components\Group::make()->schema([
                        Forms\Components\Section::make('基础信息')->schema([
                            Forms\Components\Select::make('thesis_type_id')->label('选择论文类型')
                                ->relationship(name: 'thesisType', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                    return $query->normal()->orderBy('order_column', 'asc');
                                })
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
                            Forms\Components\TextInput::make('company_name')->label('所属单位')
                                ->placeholder('请输入论文所属单位')
                                ->required(),
                            Forms\Components\Textarea::make('description')->label('摘要')
                                ->placeholder('请输入论文摘要'),
                            Forms\Components\Textarea::make('remark')->label('备注'),
                        ]),
                        Forms\Components\Section::make('附件管理')->schema([
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
                    Forms\Components\Section::make('状态')->schema([
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\TextColumn::make('company_name')
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
            ->searchPlaceholder('搜索论文标题、作者等...')
            ->filtersFormWidth(MaxWidth::Medium)
            ->filters([
                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\Group::make()->schema([
                            Forms\Components\DatePicker::make('published_from')->label('发布开始时间')->columnSpan(1),
                            Forms\Components\DatePicker::make('published_until')->label('发布结束时间')->columnSpan(1),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
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
            'index' => Pages\ListTheses::route('/'),
            'create' => Pages\CreateThesis::route('/create'),
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
