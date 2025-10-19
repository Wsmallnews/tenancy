<?php

namespace App\Filament\Platform\Pages;

use BackedEnum;
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;
use UnitEnum;

class Backup extends BaseBackups
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cpu-chip';

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return '设置管理';
    }

}
