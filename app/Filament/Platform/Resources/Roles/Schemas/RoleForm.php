<?php

namespace App\Filament\Platform\Resources\Roles\Schemas;

use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;

class RoleForm
{

    use HasShieldFormComponents;


    /**
     * 配置表单
     * 
     * @return Schema
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(
                self::baseSchemas(getPermissionsTeamId()),
            );
    }


    



    /**
     * 给团队创建管理员
     * 
     * @return Schema
     */
    public static function teamConfigure(Schema $schema, $team_id): Schema
    {
        return $schema
            ->components(
                self::baseSchemas($team_id),
            );
    }



    public static function baseSchemas($team_id)
    {
        return [
            Grid::make()
                ->schema([
                    Section::make()
                        ->schema([
                            TextInput::make('name')
                                ->label(__('filament-shield::filament-shield.field.name'))
                                ->unique(
                                    ignoreRecord: true,
                                    /** @phpstan-ignore-next-line */
                                    modifyRuleUsing: fn(Unique $rule): Unique => $rule->where(config('permission.column_names.team_foreign_key'), $team_id)
                                )
                                ->required()
                                ->maxLength(255),

                            TextInput::make('guard_name')
                                ->label(__('filament-shield::filament-shield.field.guard_name'))
                                ->default(Utils::getFilamentAuthGuard())
                                ->nullable()
                                ->maxLength(255),

                            Hidden::make(config('permission.column_names.team_foreign_key'))
                                ->default($team_id),
                            // Select::make(config('permission.column_names.team_foreign_key'))
                            //     ->label(__('filament-shield::filament-shield.field.team'))
                            //     ->placeholder(__('filament-shield::filament-shield.field.team.placeholder'))
                            //     /** @phpstan-ignore-next-line */
                            //     ->default(Filament::getTenant()?->id)
                            //     ->options(fn (): array => in_array(Utils::getTenantModel(), [null, '', '0'], true) ? [] : Utils::getTenantModel()::pluck('name', 'id')->toArray())
                            //     ->visible(fn (): bool => static::shield()->isCentralApp() && Utils::isTenancyEnabled())
                            //     ->dehydrated(fn (): bool => static::shield()->isCentralApp() && Utils::isTenancyEnabled()),
                            static::getSelectAllFormComponent(),

                        ])
                        ->columns([
                            'sm' => 2,
                            'lg' => 3,
                        ])
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
                static::getShieldFormComponents(),
        ];
    }
}
