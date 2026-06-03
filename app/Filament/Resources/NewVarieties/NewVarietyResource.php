<?php

namespace App\Filament\Resources\NewVarieties;

use App\Enums\NewVarieties\Status;
use App\Filament\Resources\NewVarieties\Exports\NewVarietyExporter;
use App\Filament\Resources\NewVarieties\Schemas\NewVarietyInfolist;
use App\Models\Appraise;
use App\Models\NewVariety;
use BackedEnum;
use Filament\Actions;
use Filament\Actions\ExportAction as FilamentExportAction;
use Filament\Actions\ExportBulkAction as FilamentExportBulkAction;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;
use Wsmallnews\Support\Filament\Filters\FilterComponents;

class NewVarietyResource extends Resource
{
    protected static ?string $model = NewVariety::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::QueueList;

    protected static ?string $navigationLabel = '新品种';

    protected static string|UnitEnum|null $navigationGroup = '研究成果';

    protected static ?string $slug = 'new-varieties';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '新品种';

    protected static ?string $pluralModelLabel = '新品种';

    protected static ?int $navigationSort = 4;

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
                                ->required()
                                ->columnSpanFull(),

                            Schemas\Components\Grid::make([
                                'default' => 1,
                                'lg' => 2,
                                'xl' => 3,
                            ])
                                ->extraAttributes([
                                    'class' => 'sn-grid-table',
                                ])
                                ->schema(function (Get $get) {
                                    if ($get('appraise_id') && $appraise = Appraise::findOrFail($get('appraise_id'))) {
                                        $coverMedia = $appraise->getFirstMedia('cover');

                                        return [
                                            Infolists\Components\ImageEntry::make('appraise_cover')
                                                ->label('种质封面图')
                                                ->state($coverMedia?->getFullUrl())
                                                ->extraAttributes([
                                                    'class' => 'sn-two-rows',
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
                                            Infolists\Components\TextEntry::make('appraise_country_name')
                                                ->label('种质原产国')
                                                ->state($appraise->country_name),
                                            Infolists\Components\TextEntry::make('appraise_district_name')
                                                ->label('种质原产地区')
                                                ->state($appraise->province_name.' / '.$appraise->city_name)
                                                ->visible(fn (?Model $record) => $appraise?->country_code == 'CN'),
                                            Infolists\Components\TextEntry::make('appraise_address')
                                                ->label('种质原产地址')
                                                ->state($appraise->address),
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
                                ->visible(fn (Get $get): bool => boolval($get('appraise_id')))
                                ->columnSpanFull(),
                        ]),
                        Schemas\Components\Section::make('品种信息')->schema([
                            Forms\Components\TextInput::make('variety_no')->label('品种权号')
                                ->placeholder('请输入品种权号')
                                ->required(),
                            Forms\Components\TextInput::make('name')->label('品种权人')
                                ->placeholder('请输入品种权人')
                                ->required(),
                            Forms\Components\DatePicker::make('variety_at')->label('年份')
                                ->placeholder('请选择年份')
                                ->native(false)
                                ->displayFormat('Y-m')
                                ->required(),
                            Forms\Components\TextInput::make('cultivate_name')->label('培育人')
                                ->placeholder('请输入培育人')
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
                    ->from('lg'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NewVarietyInfolist::configure($schema);
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
                Tables\Columns\TextColumn::make('variety_no')
                    ->label('品种权号')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('品种权人')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('variety_at')
                    ->label('年份')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('cultivate_name')
                    ->label('培育人')
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
                Tables\Columns\TextColumn::make('appraise.country_name')
                    ->label('种质原产国')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.district_name')
                    ->label('种质原产地区')
                    ->searchable()
                    ->state(function (Model $record): string {
                        if ($record->appraise?->country_code == 'CN') {
                            return $record->appraise->province_name.' / '.$record->appraise->city_name;
                        }

                        return '/';
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appraise.address')
                    ->label('种质原产地址')
                    ->searchable()
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
            ->searchPlaceholder('搜索品种权号、品种权人等...')
            ->filtersFormWidth(Width::Medium)
            ->filters([
                ...FilterComponents::createUpdateRangeFilter(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                FilamentExportAction::make()
                    ->exporter(NewVarietyExporter::class)
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
                        ->exporter(NewVarietyExporter::class)
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
            'index' => Pages\ListNewVarieties::route('/'),
            'create' => Pages\CreateNewVariety::route('/create'),
            'view' => Pages\ViewNewVariety::route('/{record}'),
            'edit' => Pages\EditNewVariety::route('/{record}/edit'),
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
