<?php

namespace App\Filament\Resources\Appraises\Pages;

use App\Filament\Resources\Appraises\Actions\QrCodeAction;
use App\Filament\Resources\Appraises\AppraiseResource;
use App\Filament\Resources\PhenotypeIdentifies\Widgets\PhenotypeIdentifyTableWidget;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAppraise extends ViewRecord
{
    protected static string $resource = AppraiseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            QrCodeAction::make(),
            EditAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            PhenotypeIdentifyTableWidget::class,
        ];
    }
}
