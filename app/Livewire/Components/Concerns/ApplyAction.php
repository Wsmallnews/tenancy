<?php

namespace App\Livewire\Components\Concerns;

use App\Enums\AppraiseApplies\Status;
use App\Features\Common;
use App\Models\Appraise as AppraiseModel;
use App\Models\AppraiseApply;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Support\Enums\Width;
use Wsmallnews\Cms\Support\Utils;

trait ApplyAction
{
    public function applyAction(): CreateAction
    {
        return CreateAction::make('apply')
            ->label('用种申请')
            ->modalHeading('用种申请')
            ->modalDescription('请认真填写申请信息，确保信息准确无误，否则审批可能不通过。')
            ->schema(function (array $arguments) {
                return [
                    Schemas\Components\Text::make(Common::title('保存单位信息', 'applyAppraiseSave')),
                    Schemas\Components\Grid::make()
                        ->gridContainer()
                        ->columns([
                            '@md' => 1,
                            '@2xl' => 2,
                            '@6xl' => 3,
                        ])
                        ->extraAttributes([
                            'class' => 'sn-grid-table',
                        ])
                        ->schema(function () use ($arguments) {
                            $appraise = AppraiseModel::query()->scopeTenant()->normal()->with('saveCompany')->findOrFail($arguments['appraise_id']);
                            $saveCompany = $appraise->saveCompany;
                            return [
                                Infolists\Components\TextEntry::make('saveCompany.name')
                                    ->label('保存单位')
                                    ->state(fn() => $saveCompany ? "{$saveCompany->name} (编号：{$saveCompany->code})" : '-'),
                                Infolists\Components\TextEntry::make('saveCompany.contact')
                                    ->label('联系人')
                                    ->state(fn() => $saveCompany ? $saveCompany->contact : '-'),
                                Infolists\Components\TextEntry::make('saveCompany.contact_phone')
                                    ->label('联系电话')
                                    ->state(fn() => $saveCompany ? $saveCompany->contact_phone : '-'),
                                Infolists\Components\TextEntry::make('saveCompany.email')
                                    ->label('联系邮箱')
                                    ->state(fn() => $saveCompany ? $saveCompany->email : '-'),
                            ];
                        })->columnSpanFull(),
                    Forms\Components\TextInput::make('name')
                        ->label('申请人')
                        ->required(),
                    Forms\Components\TextInput::make('phone')
                        ->label('联系方式')
                        ->required(),
                    Forms\Components\TextInput::make('company_name')
                        ->label('用种单位')
                        ->required(),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('apply_file')->label('申请单')
                        ->helperText('上传申请单')
                        ->collection('apply_file')
                        ->required()
                        ->downloadable()
                        ->acceptedFileTypes(['application/*'])
                        ->imagePreviewHeight('100')
                        ->uploadingMessage('申请单上传中...')
                        ->columnSpanFull(1),
                ];
            })
            ->mutateDataUsing(function (array $data, array $arguments): array {
                $data['user_id'] = auth()->guard(Utils::getConfig('guard', 'web'))->id();
                $data['appraise_id'] = $arguments['appraise_id'];
                $data['team_id'] = current_tenant()?->id;
                $data['status'] = Status::Applying;
                return $data;
            })
            ->model(AppraiseApply::class)       // 当前保存主表模型
            ->visible(auth()->guard(Utils::getConfig('guard', 'web'))->check())
            ->stickyModalHeader()
            ->stickyModalFooter()
            ->modalWidth(Width::ThreeExtraLarge);
    }
}
