<?php

namespace App\Filament\Pages;

use App\Enums\Categories\Status;
use App\Models\Category as CategoryModel;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Infolists;
use Filament\Support\Enums\Alignment;
use Kalnoy\Nestedset\QueryBuilder;
use Studio15\FilamentTree\Components\TreePage;

class Category extends TreePage
{
    
    protected static ?string $navigationLabel = '分类';

    protected static ?string $navigationGroup = '种质目录';

    protected static ?string $slug = 'categories';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = '分类';

    protected static ?string $pluralModelLabel = '分类';

    protected static ?int $navigationSort = 1;

    public static function getModel(): string|QueryBuilder
    {
        if (Filament::getTenant()) {
            return CategoryModel::scoped(['team_id' => Filament::getTenant()->id]);
        } else {
            return CategoryModel::class;
        }
    }

    public static function getCreateForm(): array
    {
        return static::getSchemas();
    }

    public static function getEditForm(): array
    {
        return static::getSchemas();
    }

    public static function getInfolistColumns(): array
    {
        return [
            Infolists\Components\TextEntry::make('remark')
                ->label('备注')
                ->visible(fn($state): bool => $state ? true : false),
            Infolists\Components\IconEntry::make('status')
                ->label('状态'),
        ];
    }



    private static function getSchemas()
    {
        return [
            Forms\Components\TextInput::make('name')->label('分类名称')
                ->placeholder('请输入分类名称')
                ->required(),
            Forms\Components\Textarea::make('remark')->label('备注'),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Radio::make('status')
                        ->label('状态')
                        ->default(Status::Normal)
                        ->inline()
                        ->options(Status::class)
                        ->columnSpan(1),
                ])->columns(2),
            Forms\Components\Repeater::make('options.fields')
                ->label('自定义字段')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->hiddenLabel()
                        ->placeholder('请输入字段分组名称')
                        ->required()
                        ->live(onBlur: true)
                        ->columnSpan(1),
                    TableRepeater::make('fields')
                        ->label('字段')
                        ->hiddenLabel()
                        ->headers([
                            Header::make('name')
                                ->label('字段名称')
                                ->markAsRequired()
                                ->width('150px'),
                            Header::make('unit')
                                ->label('字段单位')
                                ->width('150px'),
                            Header::make('placeholder')
                                ->label('字段输入提示')
                                ->width('150px'),
                        ])
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('字段名称')
                                ->placeholder('请输入字段名称')
                                ->required()
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('unit')
                                ->label('字段单位')
                                ->placeholder('请输入字段单位')
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('placeholder')
                                ->label('字段输入提示')
                                ->placeholder('请输入字段输入提示')
                                ->columnSpan(1),
                        ])
                        ->cloneable()
                        ->addActionAlignment(Alignment::Start)
                        ->emptyLabel('请设置分组的字段信息')
                        ->columnSpanFull()
                ])
                // ->deleteAction(          // 需要研究下 modal 的层级，如何不关闭当前编辑的 modal
                //     fn(Action $action) => $action->requiresConfirmation(),
                // )
                ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                ->collapsible()
                ->cloneable()
                ->addActionAlignment(Alignment::Start)
                ->columns(2)
        ];
    }
}
