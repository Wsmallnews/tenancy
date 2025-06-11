<?php

namespace App\Filament\Resources;

use App\Enums\Awards\Status;
use App\Filament\Resources\AwardResource\Pages;
use App\Models\Award;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AwardResource extends Resource
{
    protected static ?string $model = Award::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '奖项';

    protected static ?string $navigationGroup = '研究成果';

    protected static ?string $slug = 'awards';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '奖项';

    protected static ?string $pluralModelLabel = '奖项';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('基础信息')->schema([
                        Forms\Components\TextInput::make('name')->label('奖项名称')
                            ->placeholder('请输入奖项名称')
                            ->required(),
                        Forms\Components\Select::make('award_type_id')->label('选择奖项类型')
                            ->relationship(name: 'awardType', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                return $query->normal()->orderBy('order_column', 'asc');
                            })
                            ->placeholder('请选择奖项类型')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('award_agency')->label('授奖机构')
                            ->placeholder('请输入授奖机构')
                            ->required(),
                        Forms\Components\TextInput::make('level')->label('级别')
                            ->placeholder('请输入奖项级别')
                            ->required(),
                        Forms\Components\TextInput::make('award_name')->label('获奖人/团队')
                            ->placeholder('请输入获奖人/团队')
                            ->required(),
                        Forms\Components\Textarea::make('remark')->label('备注'),
                    ]),
                    Forms\Components\Section::make('证书管理')->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('certs')->label('上传证书')
                            ->helperText('支持上传证书图片或者 PDF 格式的证书文件')
                            ->collection('certs')
                            ->required()
                            ->multiple()
                            ->downloadable()
                            ->reorderable()
                            ->appendFiles()
                            ->minFiles(1)
                            ->maxFiles(20)
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->imagePreviewHeight('100')
                            ->uploadingMessage('证书上传中...')
                            ->columns(1),
                    ])->columns(1),
                ])->columns(2)->columnSpan(2),
                Forms\Components\Section::make('状态')->schema([
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
                ])->columns(1)->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
            ->searchPlaceholder('搜索奖项名称、授权机构等...')
            ->filtersFormWidth(MaxWidth::Medium)
            ->filters([
                Tables\Filters\Filter::make('award_at')
                    ->form([
                        Forms\Components\Group::make()->schema([
                            Forms\Components\DatePicker::make('award_from')->label('获奖开始时间')->columnSpan(1),
                            Forms\Components\DatePicker::make('award_until')->label('获奖结束时间')->columnSpan(1),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['award_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('award_at', '>=', $date),
                            )
                            ->when(
                                $data['award_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('award_at', '<=', $date),
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
            'index' => Pages\ListAwards::route('/'),
            'create' => Pages\CreateAward::route('/create'),
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
