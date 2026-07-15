<?php

namespace App\Livewire\Components\Concerns;

use App\Enums\AppraiseApplies\Status;
use App\Features\Common;
use App\Models\Appraise as AppraiseModel;
use App\Models\AppraiseApply;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Filament\Schemas;
use Filament\Support\Enums\Width;
use Livewire\Component;
use Wsmallnews\Cms\Support\Utils;
use Wsmallnews\Support\Filament\Forms\FormComponents;

trait ApplyAction
{
    public function applyAction(): CreateAction
    {
        $this->skipRender();        // 跳过渲染

        return CreateAction::make('apply')
            ->label('用种申请')
            ->modalHeading('用种申请')
            ->modalDescription('请认真填写申请信息，确保信息准确无误，否则审批可能不通过。')
            ->successNotificationTitle('申请成功')
            ->beforeFormFilled(function (CreateAction $action, Component $livewire) {
                if (auth()->guard(Utils::getConfig('guard', 'web'))->check()) {
                    return;
                }

                Notification::make()
                    ->danger()
                    ->title('请先登录')
                    ->body('您还没有登录，请先登录')
                    ->send();

                $livewire->redirect(Utils::route('login'), true);

                $action->cancel();
                $action->halt();
            })
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
                                    ->state(fn () => $saveCompany ? "{$saveCompany->name} (编号：{$saveCompany->code})" : '-'),
                                Infolists\Components\TextEntry::make('saveCompany.contact')
                                    ->label('联系人')
                                    ->state(fn () => $saveCompany ? $saveCompany->contact : '-'),
                                Infolists\Components\TextEntry::make('saveCompany.contact_phone')
                                    ->label('联系电话')
                                    ->state(fn () => $saveCompany ? $saveCompany->contact_phone : '-'),
                                Infolists\Components\TextEntry::make('saveCompany.email')
                                    ->label('联系邮箱')
                                    ->state(fn () => $saveCompany ? $saveCompany->email : '-'),
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
                    FormComponents::mediaFileUpload('apply_file', 'apply_file')->label('申请资料')
                        ->helperText('上传申请资料（word、PDF、图片等）')
                        ->required()
                        ->multiple()
                        ->minFiles(1)
                        ->maxFiles(20)
                        ->acceptedFileTypes(['application/*', 'image/*'])
                        ->uploadingMessage('申请资料上传中...')
                        ->panelLayout('compact')
                        ->columns(1),
                ];
            })
            ->mutateDataUsing(function (array $data, array $arguments): array {
                $data['user_id'] = auth()->guard(Utils::getConfig('guard', 'web'))->id();
                $data['appraise_id'] = $arguments['appraise_id'];
                $data['team_id'] = current_tenant()?->id;
                $data['status'] = Status::Applying;

                return $data;
            })
            ->modalSubmitActionLabel('提交申请')
            ->createAnother(false)
            ->model(AppraiseApply::class)       // 当前保存主表模型
            ->visible(true)
            ->stickyModalHeader()
            ->stickyModalFooter()
            ->modalWidth(Width::ThreeExtraLarge);
    }
}
