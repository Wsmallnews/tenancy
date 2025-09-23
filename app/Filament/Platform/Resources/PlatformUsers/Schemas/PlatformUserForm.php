<?php

namespace App\Filament\Platform\Resources\PlatformUsers\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PlatformUserForm
{

    /**
     * 配置表单
     * 
     * @return Schema
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Section::make('基础信息')->schema(self::getBaseFormsComponent()),
                    ])->columns(1),
                    Schemas\Components\Section::make('分配角色')->schema([
                        Forms\Components\Select::make('roles')
                            ->relationship(name: 'roles', titleAttribute: 'name')
                            ->saveRelationshipsUsing(function (Model $record, $state) {
                                $record->roles()->syncWithPivotValues($state, [config('permission.column_names.team_foreign_key') => getPermissionsTeamId()]);
                            })
                            ->multiple()
                            ->preload()
                            ->searchable(),
                        // Forms\Components\Radio::make('status')
                        //     ->label('状态')
                        //     ->default(Status::Normal)
                        //     ->inline()
                        //     ->options(Status::class),
                    ])
                    ->extraAttributes(['style' => 'min-width: 300px;'])
                    ->grow(false),
                ])
                ->columnSpanFull()
                ->from('lg')
            ]);
    }


    /**
     * 给团队创建管理员
     * 
     * @return Schema
     */
    public static function teamConfigure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Schemas\Components\Section::make('基础信息')->schema(self::getBaseFormsComponent()),
                    Schemas\Components\Section::make('分配角色')->schema([
                        Forms\Components\Select::make('roles')
                            ->relationship(name: 'roles', titleAttribute: 'name')
                            ->saveRelationshipsUsing(function (Model $record, $state) {
                                $record->roles()->syncWithPivotValues($state, [config('permission.column_names.team_foreign_key') => getPermissionsTeamId()]);
                            })
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ])
            ]);
    }


    /**
     * 基础表单
     * 
     * @return array
     */
    public static function getBaseFormsComponent(): array
    {
        return [
            Forms\Components\TextInput::make('name')->label('管理员名称')
                ->placeholder('请输入管理员名称')
                ->required(),
            Forms\Components\FileUpload::make('avatar_url')->label('头像')
                ->avatar()
                ->required()
                ->directory('users/avatars')
                ->openable()
                ->uploadingMessage('头像上传中...'),
            Forms\Components\TextInput::make('email')->label('邮箱')
                ->placeholder('请输入登录邮箱')
                ->required(),
            Forms\Components\TextInput::make('password')
                ->label(__('filament-panels::auth/pages/edit-profile.form.password.label'))
                ->placeholder('不修改则留空')
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->rule(Password::default())
                ->autocomplete('new-password')
                ->dehydrated(fn($state): bool => filled($state))
                ->dehydrateStateUsing(fn($state): string => Hash::make($state))
                // ->same('passwordConfirmation')       // 是否需要确认密码
                ->live(debounce: 500),
        ];
    }
}
