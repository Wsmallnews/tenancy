<?php

namespace App\Filament\Resources\Posts;

use BackedEnum;
use App\Enums\Posts\Status;
use App\Features\Common;
use App\Filament\Resources\Posts\Pages;
use App\Models\Post;
use CodeWithDennis\FilamentSelectTree\SelectTree;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = '图文管理';

    protected static string | UnitEnum | null $navigationGroup = '网站管理';

    protected static ?string $slug = 'posts';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = '图文';

    protected static ?string $pluralModelLabel = '图文';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Section::make('基础信息')->schema([
                            // 单选 分类
                            // SelectTree::make('category_id')->label('选择分类')
                            //     ->relationship(relationship: 'category', titleAttribute: 'name', parentAttribute: 'parent_id')
                            //     ->searchable()
                            //     ->parentNullValue(0)
                            //     ->enableBranchNode()
                            //     ->withCount()
                            //     // ->placeholder(__('请选择图文分类'))
                            //     // ->emptyLabel(__('未搜索到分类'))
                            //     ->treeKey('postCategoryId')
                            //     ,

                            // 多选分类
                            SelectTree::make('categories')->label('选择分类')
                                ->relationship(relationship: 'categories', titleAttribute: 'name', parentAttribute: 'parent_id')
                                ->searchable()
                                ->enableBranchNode()
                                ->withCount()
                                // ->placeholder(__('请选择图文分类'))
                                // ->emptyLabel(__('未搜索到分类'))
                                ->treeKey('postCategories')
                                ,

                            Forms\Components\TextInput::make('title')->label('标题')
                                ->placeholder('请输入内容标题')
                                ->required(),
                            Forms\Components\Textarea::make('description')->label('描述')
                                ->placeholder('请输入描述'),
                        ])->columns(1),
                        Schemas\Components\Section::make('内容')->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('image')->label('主图')
                                ->collection('main')
                                ->required()
                                ->openable()
                                ->image()
                                ->downloadable()
                                ->uploadingMessage('主图上传中...')
                                ->imagePreviewHeight('200'),
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
                            //     ->imagePreviewHeight('200'),
                            Schemas\Components\Group::make()
                                ->relationship('content')
                                ->schema([
                                    Forms\Components\RichEditor::make('content')
                                        ->fileAttachmentsDirectory('contents/' . date('Ymd'))
                                        ->label('内容详情'),
                                    // \Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor::make('content')
                                    //     ->label('内容详情')
                                    //     ->fileAttachmentsDirectory('contents/' . date('Ymd'))
                                        // ->toolbarSticky(true)
                                ])->columns(1),
                        ])->columns(1),
                    ])->columns(1),
                    Schemas\Components\Section::make('状态')->schema([
                        Forms\Components\SpatieTagsInput::make('tags')->label('标签')->type('post_tags'),
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
                Tables\Columns\TextColumn::make('views')
                    ->label('浏览量')
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
            ->searchPlaceholder('搜索标题、描述、标签等...')
            ->filtersFormWidth(Width::Medium)
            ->filters([
                ...Common::createUpdateRangeFilter(),
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
