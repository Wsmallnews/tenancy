<?php

namespace App\Filament\Resources;

use App\Enums\Patents\Status;
use App\Filament\Resources\PatentResource\Pages;
use App\Models\Patent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatentResource extends Resource
{
    protected static ?string $model = Patent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '专利';

    protected static ?string $navigationGroup = '研究成果';

    protected static ?string $slug = 'patents';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '专利';

    protected static ?string $pluralModelLabel = '专利';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('基础信息')->schema([
                        Forms\Components\TextInput::make('name')->label('专利名称')
                            ->placeholder('请输入专利名称')
                            ->required(),
                        Forms\Components\Select::make('patent_type_id')->label('选择专利类型')
                            ->relationship(name: 'patentType', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                return $query->normal()->orderBy('order_column', 'asc');
                            })
                            ->placeholder('请选择专利类型')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('patent_apply_no')->label('专利申请号')
                            ->placeholder('请输入专利申请号')
                            ->required(),
                        Forms\Components\TextInput::make('patent_no')->label('专利号')
                            ->placeholder('请输入专利号')
                            ->required(),
                        Forms\Components\TextInput::make('author_name')->label('发明人/作者')
                            ->placeholder('请输入发明人/作者')
                            ->required(),
                        Forms\Components\Textarea::make('description')->label('摘要')
                            ->placeholder('请输入专利摘要'),
                        Forms\Components\Textarea::make('remark')->label('备注'),
                    ]),
                    Forms\Components\Section::make('附件管理')->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('patents')->label('上传附件')
                            ->helperText('支持上传专利图片或者 PDF 格式的专利文件')
                            ->collection('patents')
                            ->required()
                            ->multiple()
                            ->downloadable()
                            ->reorderable()
                            ->appendFiles()
                            ->minFiles(1)
                            ->maxFiles(20)
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->imagePreviewHeight('100')
                            ->uploadingMessage('专利文件上传中...')
                            ->columns(1),
                    ])->columns(1),
                ])->columns(2)->columnSpan(2),
                Forms\Components\Section::make('状态')->schema([
                    Forms\Components\DatePicker::make('applied_at')->label('申请日期')
                        ->placeholder('请选择申请日期')
                        ->native(false)
                        ->required(),
                    Forms\Components\DatePicker::make('authd_at')->label('授权日期')
                        ->placeholder('请选择授权日期')
                        ->native(false)
                        ->required(),
                    Forms\Components\TextInput::make('order_column')->label('排序')->integer()
                        ->placeholder('正序排列')
                        ->rules(['integer', 'min:0']),
                    Forms\Components\Radio::make('status')
                        ->label('状态')
                        ->default(Status::Ing)
                        ->inline()
                        ->options(Status::class),
                ])->columns(1)->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('专利名称')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),
                Tables\Columns\TextColumn::make('patentType.name')
                    ->label('专利类型')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('patent_apply_no')
                    ->label('专利申请号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('patent_no')
                    ->label('专利号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('applied_at')
                    ->label('申请日期')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('authd_at')
                    ->label('授权日期')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('专利状态')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('author_name')
                    ->label('发明人/作者')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('order_column')
                    ->label('排序')
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
            ->searchPlaceholder('搜索专利名称、专利号等...')
            ->filtersFormWidth(MaxWidth::Medium)
            ->filters([
                Tables\Filters\Filter::make('applied_at')
                    ->form([
                        Forms\Components\Group::make()->schema([
                            Forms\Components\DatePicker::make('applied_from')->label('申请开始时间')->columnSpan(1),
                            Forms\Components\DatePicker::make('applied_until')->label('申请结束时间')->columnSpan(1),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['applied_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('applied_at', '>=', $date),
                            )
                            ->when(
                                $data['applied_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('applied_at', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('authd_at')
                    ->form([
                        Forms\Components\Group::make()->schema([
                            Forms\Components\DatePicker::make('authd_from')->label('授权开始时间')->columnSpan(1),
                            Forms\Components\DatePicker::make('authd_until')->label('授权结束时间')->columnSpan(1),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['authd_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('authd_at', '>=', $date),
                            )
                            ->when(
                                $data['authd_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('authd_at', '<=', $date),
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
            'index' => Pages\ListPatents::route('/'),
            'create' => Pages\CreatePatent::route('/create'),
            'edit' => Pages\EditPatent::route('/{record}/edit'),
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
