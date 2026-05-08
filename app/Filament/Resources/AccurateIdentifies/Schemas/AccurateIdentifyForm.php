<?php

namespace App\Filament\Resources\AccurateIdentifies\Schemas;

use App\Enums\AccurateIdentifies\Status;
use App\Models\AccurateIdentify;
use App\Models\Appraise;
use App\Settings\AppraiseSettings;
use Filament\Schemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Infolists;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Wsmallnews\Support\Filament\Forms\FormComponents;
use Illuminate\Support\Arr;

class AccurateIdentifyForm
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
                                            Infolists\Components\TextEntry::make('appraise_country_name')
                                                ->label('种质原产国')
                                                ->state($appraise->country_name),
                                            Infolists\Components\TextEntry::make('appraise_district_name')
                                                ->label('种质原产地区')
                                                ->state($appraise->province_name . ' / ' . $appraise->city_name)
                                                ->visible(fn(?Model $record) => $appraise?->country_code == 'CN'),
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
                                ->visible(fn(Get $get): bool => boolval($get('appraise_id')))
                                ->columnSpanFull(),
                        ]),
                        Schemas\Components\Section::make('鉴定信息')->schema([
                            Forms\Components\Select::make('gene_identify_method')->label('基因型鉴定方法')
                                ->placeholder('请选择基因型鉴定方法')
                                ->required()
                                ->options(fn (AppraiseSettings $settings) => Arr::mapWithKeys($settings->gene_identify_method, function ($item) {
                                    return [$item => $item];
                                })),
                            Forms\Components\TextInput::make('method_params')->label('方法参数')
                                ->placeholder('请输入方法参数')
                                ->required(),
                            Forms\Components\TextInput::make('sequencing_platform')->label('测序平台')
                                ->placeholder('请输入测序平台')
                                ->required(),
                            Forms\Components\TextInput::make('sequencing_technology')->label('测序技术')
                                ->placeholder('请输入测序技术')
                                ->required(),
                            Forms\Components\TextInput::make('f_reads_length')->label('F端reads读长')
                                ->placeholder('请输入F端reads读长')
                                ->rules(['integer', 'min:0'])
                                ->required(),
                            Forms\Components\TextInput::make('entity_data_one')->label('实体数据1 MD5')
                                ->placeholder('请输入实体数据1 MD5')
                                ->required(),
                            Forms\Components\TextInput::make('r_reads_length')->label('R端reads读长')
                                ->placeholder('请输入R端reads读长')
                                ->rules(['integer', 'min:0'])
                                ->required(),
                            Forms\Components\TextInput::make('entity_data_two')->label('实体数据2 MD5')
                                ->placeholder('请输入实体数据2 MD5')
                                ->required(),
                            Forms\Components\TextInput::make('reference_sequence')->label('参考序列 MD5')
                                ->placeholder('请输入参考序列 MD5')
                                ->required(),
                            Forms\Components\TextInput::make('sample_no')->label('样本编号')
                                ->placeholder('请输入样本编号')
                                ->required(),
                            Forms\Components\TextInput::make('identify_name')->label('鉴定人')
                                ->placeholder('请输入鉴定人')
                                ->required(),
                            Forms\Components\DateTimePicker::make('identify_at')->label('鉴定时间')
                                ->placeholder('请选择鉴定时间')
                                ->native(false)
                                ->seconds(false)
                                ->displayFormat('Y-m-d H:i')
                                ->required(),
                            Forms\Components\TextInput::make('identify_conclusion')->label('鉴定结论')
                                ->placeholder('请输入鉴定结论')
                                ->required(),
                        ])->columns(2),
                        Schemas\Components\Section::make('样本图片')->schema([
                            FormComponents::mediaImageUpload('sample_galleries', 'sample_galleries')->label('样本图片')
                                ->helperText('支持上传多张图片')
                                ->required()
                                ->multiple()
                                ->minFiles(1)
                                ->maxFiles(20)
                                ->uploadingMessage('样本图片上传中...')
                                ->columns(1),
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
}
