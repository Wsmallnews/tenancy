<?php

namespace App\Filament\Resources;

use App\Enums\Posts\Status;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '资讯管理';

    protected static ?string $navigationGroup = '内容管理';

    protected static ?string $slug = 'posts';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = '资讯';

    protected static ?string $pluralModelLabel = '资讯';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('基础信息')->schema([
                        // 单选 分类
                        // SelectTree::make('category_id')->label('选择分类')
                        //     ->relationship(relationship: 'category', titleAttribute: 'name', parentAttribute: 'parent_id')
                        //     ->searchable()
                        //     ->parentNullValue(0)
                        //     ->enableBranchNode()
                        //     ->withCount()
                        //     // ->placeholder(__('请选择资讯分类'))
                        //     // ->emptyLabel(__('未搜索到分类'))
                        //     ->treeKey('postCategoryId')
                        //     ,

                        // 多选分类
                        // SelectTree::make('categories')->label('选择分类')
                        //     ->relationship(relationship: 'categories', titleAttribute: 'name', parentAttribute: 'parent_id')
                        //     ->searchable()
                        //     ->enableBranchNode()
                        //     ->withCount()
                        //     // ->placeholder(__('请选择资讯分类'))
                        //     // ->emptyLabel(__('未搜索到分类'))
                        //     ->treeKey('postCategories')
                        //     ,

                        Forms\Components\TextInput::make('title')->label('标题')
                            ->placeholder('请输入内容标题')
                            ->required(),
                        Forms\Components\Textarea::make('description')->label('描述')
                            ->placeholder('请输入描述'),
                    ]),
                    Forms\Components\Section::make('内容')->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('image')->label('主图')
                            ->collection('main')
                            ->required()
                            ->openable()
                            ->image()
                            ->downloadable()
                            ->uploadingMessage('主图上传中...')
                            ->imagePreviewHeight('100'),
                        // SpatieMediaLibraryFileUpload::make('images')->label('轮播图')
                        //     ->collection('gallery')
                        //     ->image()
                        //     ->required()
                        //     ->multiple()
                        //     ->openable()
                        //     ->downloadable()
                        //     ->reorderable()
                        //     ->appendFiles()
                        //     ->minFiles(1)
                        //     ->maxFiles(20)
                        //     ->uploadingMessage('轮播图片上传中...')
                        //     ->imagePreviewHeight('100'),
                        Forms\Components\Group::make()
                            ->relationship('content')
                            ->schema([
                                // Components\RichEditor::make('content')
                                //     ->fileAttachmentsDirectory('contents/' . date('Ymd'))
                                //     ->label('内容详情'),
                                \Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor::make('content')
                                    ->label('内容详情')
                                    ->fileAttachmentsDirectory('contents/' . date('Ymd'))
                                    // ->toolbarSticky(true)
                            ])->columns(1),
                    ])->columns(1),
                ])->columns(2)->columnSpan(2),
                Forms\Components\Section::make('状态')->schema([
                    Forms\Components\SpatieTagsInput::make('tags')->label('标签')->type('post_tags'),
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
                Tables\Columns\TextColumn::make('title')
                    ->label('标题')
                    ->searchable()
                    ->description(fn($record) => $record->description)
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    }),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->label('主图')
                    ->collection('main')
                    ->toggleable(),
                // Tables\Columns\TextColumn::make('category.name')
                //     ->label('分类')
                //     ->searchable()
                //     ->toggleable(),
                Tables\Columns\SpatieTagsColumn::make('tags')
                    ->label('标签')
                    ->type('post_tags')
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
            ->searchPlaceholder('搜索标题、描述、标签等...')
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
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
