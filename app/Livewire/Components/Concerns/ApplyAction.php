<?php

namespace App\Livewire\Components\Concerns;

use App\Features\Common;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Model;

trait ApplyAction
{
    public function applyAction(): Action
    {
        return Action::make('apply')
            ->label('用种申请')
            ->modalHeading('用种申请')
            ->modalDescription('请认真填写申请信息，确保信息准确无误，否则审批可能不通过。')
            ->schema([
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
                    ->schema([
                        Infolists\Components\TextEntry::make('saveCompany.name')
                            ->label('保存单位')
                            ->formatStateUsing(fn(Model $record, $state) => $record->saveCompany ? "{$record->saveCompany->name} (编号：{$record->saveCompany->code})" : '-'),
                        Infolists\Components\TextEntry::make('saveCompany.contact')
                            ->label('联系人'),
                        Infolists\Components\TextEntry::make('saveCompany.contact_phone')
                            ->label('联系电话'),
                        Infolists\Components\TextEntry::make('saveCompany.email')
                            ->label('联系邮箱')
                    ])->columnSpanFull(),
                Forms\Components\TextInput::make('name')
                    ->label('申请人')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('联系方式')
                    ->required(),
                Forms\Components\TextInput::make('company_name')
                    ->label('用种单位')
                    ->required(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('cover')->label('申请单')
                    ->helperText('上传申请单')
                    ->collection('apply_file')
                    ->required()
                    ->downloadable()
                    ->acceptedFileTypes(['application/*'])
                    ->imagePreviewHeight('100')
                    ->uploadingMessage('申请单上传中...')
                    ->columnSpanFull(1),
            ])
            ->action(function (array $arguments) {
                dd('Test action called', $arguments);
            })
            ->stickyModalHeader()
            ->stickyModalFooter()
            ->modalWidth(Width::SevenExtraLarge);
    }
}
