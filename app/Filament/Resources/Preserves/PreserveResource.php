<?php

namespace App\Filament\Resources\Preserves;

use BackedEnum;
use App\Enums\Preserves\Status;
use App\Features\Common;
use App\Filament\Resources\Preserves\Pages;
use App\Filament\Resources\Preserves\Schemas\PreserveInfolist;
use App\Models\Appraise;
use App\Models\Preserve;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PreserveResource extends Resource
{
    protected static ?string $model = Preserve::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = '保存';

    protected static string | UnitEnum | null $navigationGroup = '种质资源库(圃)';

    protected static ?string $slug = 'preserves';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '保存';

    protected static ?string $pluralModelLabel = '保存';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Section::make('种质信息')->schema([
                            Forms\Components\Select::make('appraise_id')->label('选择种质')
                                ->relationship(name: 'appraise', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                    return $query->normal()->orderBy('order_column', 'asc');
                                })
                                ->placeholder('请选择种质')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->required(),
                            Schemas\Components\Grid::make([
                                    'default' => 1,
                                    'lg' => 2,
                                    'xl' => 3,
                                ])
                                ->extraAttributes([
                                    'class' => 'sn-grid-table',
                                ])
                                ->schema(function(Get $get) {
                                    if ($get('appraise_id') && $appraise = Appraise::findOrFail($get('appraise_id'))) {
                                        $coverMedia = $appraise->getFirstMedia('cover');

                                        return [
                                            Infolists\Components\ImageEntry::make('appraise_cover')
                                                ->label('种质封面图')
                                                ->state($coverMedia?->getFullUrl())
                                                ->extraAttributes([
                                                    'class' => 'sn-two-rows'
                                                ]),
                                            Infolists\Components\TextEntry::make('appraise_resource_no')
                                                ->label('全国统一编号')
                                                ->state($appraise->resource_no),
                                            Infolists\Components\TextEntry::make('appraise_name')
                                                ->label('种质中文名')
                                                ->state($appraise->name),
                                            Infolists\Components\TextEntry::make('appraise_en_name')
                                                ->label('种质外文名')
                                                ->state($appraise->en_name),
                                            Infolists\Components\TextEntry::make('appraise_subject_name')
                                                ->label('科名')
                                                ->state($appraise->subject_name),
                                            Infolists\Components\TextEntry::make('appraise_genus_name')
                                                ->label('属名')
                                                ->state($appraise->genus_name),
                                            Infolists\Components\TextEntry::make('appraise_species_name')
                                                ->label('学名')
                                                ->state($appraise->species_name),
                                        ];
                                    }
                                })
                                ->visible(fn(Get $get): bool => boolval($get('appraise_id')))
                                ->columnSpanFull(),
                        ]),
                        Schemas\Components\Section::make('保存信息')->schema([
                            Forms\Components\TextInput::make('preserve_no')->label('保存编号')
                                ->placeholder('请输入保存编号')
                                ->required(),
                            Forms\Components\TextInput::make('preserve_position')->label('保存位置')
                                ->placeholder('请输入保存位置')
                                ->required(),
                        ])->columns(2),
                    ])->columns(1),
                    Schemas\Components\Section::make('状态')->schema([
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
        return PreserveInfolist::configure($schema);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('preserve_no')
                    ->label('保存编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('preserve_position')
                    ->label('保存位置')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('appraise.cover')
                    ->label('种质封面图')
                    ->collection('cover')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.resource_no')
                    ->label('全国统一编号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.name')
                    ->label('种质中文名')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.en_name')
                    ->label('种质外文名')
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
            ->reorderable('order_column')
            ->defaultSort('order_column', 'asc')
            ->searchPlaceholder('搜索保存编号、保存位置等...')
            ->filtersFormWidth(Width::Medium)
            ->filters([
                ...Common::createUpdateRangeFilter(),
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
            'index' => Pages\ListPreserves::route('/'),
            'create' => Pages\CreatePreserve::route('/create'),
            'view' => Pages\ViewPreserve::route('/{record}'),
            'edit' => Pages\EditPreserve::route('/{record}/edit'),
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
