<?php

namespace App\Filament\Resources\Appraises\Actions;

use App\Features\QrCodeService;
use Filament\Actions\Action;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class QrCodeAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'qrcode';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('二维码')
            ->icon(Heroicon::QrCode)
            ->modalHeading('种质评价二维码')
            ->modalAlignment(Alignment::Center)
            ->modalWidth(Width::Medium)
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('关闭')
            ->schema(fn ($record) => [
                // QR SVG 展示
                Text::make(new HtmlString(
                    '<div class="w-56 h-56 mt-4 border border-gray-300 dark:border-gray-700 rounded-md overflow-hidden">'.
                    QrCodeService::getAppraiseQrSvg($record).
                    '</div>'
                ))->extraAttributes([
                    'class' => 'w-full flex justify-center items-center',
                ]),

                Text::make(new HtmlString('全国统一编号：'.$record->resource_no)),
                Text::make(new HtmlString('种质圃编号：'.$record->germplasm_no)),
                Text::make(new HtmlString('种质名称：'.$record->name)),

                Flex::make([
                    // 下载按钮
                    Action::make('downloadQr')
                        ->label('下载二维码')
                        ->extraAttributes(['class' => 'w-full'])
                        ->icon(Heroicon::ArrowDownTray)
                        ->url(fn ($record) => route('admin.appraises.download-qrcode', $record))
                        ->openUrlInNewTab()
                        ->button(),

                    // 前端详情链接
                    Action::make('viewFrontend')
                        ->label('查看前端详情')
                        ->extraAttributes(['class' => 'w-full'])
                        ->icon(Heroicon::ArrowTopRightOnSquare)
                        ->url(fn ($record) => QrCodeService::getAppraiseUrl($record))
                        ->openUrlInNewTab()
                        ->color('gray')
                        ->button(),
                ])
                    ->extraAttributes(['class' => 'w-full'])
                    ->grow()
                    ->columns(2),
            ]);
    }
}
