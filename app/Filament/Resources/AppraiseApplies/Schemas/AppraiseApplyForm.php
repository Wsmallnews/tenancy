<?php

namespace App\Filament\Resources\AppraiseApplies\Schemas;

use App\Enums\AppraiseApplies\Status;
use App\Models\Appraise;
use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Wsmallnews\Support\Filament\Forms\FormComponents;

class AppraiseApplyForm
{
    public static function configure(Schema $schema): Schema
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
                        ])->columns(2),

                        Schemas\Components\Section::make('申请信息')->schema([
                            Forms\Components\Select::make('user_id')->label('申请用户')
                                ->relationship('user', 'name')
                                ->required(),
                            Forms\Components\TextInput::make('name')->label('申请人')
                                ->placeholder('请输入申请人')
                                ->required(),
                            Forms\Components\TextInput::make('phone')->label('联系方式')
                                ->placeholder('请输入联系方式')
                                ->required(),
                            Forms\Components\TextInput::make('company_name')->label('用种单位名称')
                                ->placeholder('请输入用种单位名称')
                                ->required(),
                            FormComponents::mediaFileUpload('apply_file', 'apply_file')->label('申请单')
                                ->helperText('上传申请单')
                                ->required()
                                ->acceptedFileTypes(['application/*'])
                                ->uploadingMessage('申请单上传中...')
                                ->columnSpanFull(1),
                        ]),
                    ])->columns(1),
                    Schemas\Components\Section::make('状态')->schema([
                        Forms\Components\ToggleButtons::make('status')
                            ->label('状态')
                            ->options(Status::class)
                            ->default(Status::Applying)
                            ->inline(),
                    ])->grow(false),
                ])
                    ->columnSpanFull()
                    ->from('lg'),
            ]);
    }
}
