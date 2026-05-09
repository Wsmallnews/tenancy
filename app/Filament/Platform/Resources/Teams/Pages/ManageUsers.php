<?php

namespace App\Filament\Platform\Resources\Teams\Pages;

use App\Filament\Platform\Resources\Teams\TeamResource;
use App\Filament\Platform\Resources\PlatformUsers\Schemas\PlatformUserForm;
use App\Models\Team;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Wsmallnews\Support\Filament\Resources\ActivityLogs\Concerns\CauserTimelineAction;

class ManageUsers extends ManageRelatedRecords
{
    protected static string $resource = TeamResource::class;

    protected static ?string $title = '租户管理员';

    protected static ?string $modelLabel = '租户管理员';

    protected static ?string $pluralModelLabel = '租户管理员';

    protected static ?string $relationshipTitle = '租户管理员';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string $relationship = 'users';

    protected static bool $shouldSkipAuthorization = true;      // @sn todo 跳过授权


    public function form(Schema $schema): Schema
    {
        $recordTenant = $this->getOwnerRecord();        // 关系所属租户
        setPermissionsTeamId($recordTenant->id);
        
        return PlatformUserForm::teamConfigure($schema);
    }


    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_type', 'admin'))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('管理员名称'),
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('头像')
                    ->circular()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('邮箱')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('roles_name')
                    ->state(function ($record) {
                        $recordTenant = $this->getOwnerRecord();        // 关系所属租户
                        
                        setPermissionsTeamId($recordTenant->id);        // 一次性请求，没有后续了，不需要刻意还原之前的 team_id
                        $roles = $record->roles()->get();

                        return $roles->pluck('name');
                    })
                    ->label('角色组')
                    ->badge()
                    ->toggleable()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->toggleable()
                    ->sortable(),
            ])
            ->modelLabel(self::$modelLabel)
            ->pluralModelLabel(self::$pluralModelLabel)
            ->searchPlaceholder('搜索管理员姓名、邮箱等...')
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('创建管理员')
                    ->mutateDataUsing(function (array $data): array {
                        $data['user_type'] = 'admin';       // 租户管理员
                        return $data;
                    }),
                Actions\AttachAction::make()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->where('user_type', 'admin'))
                    ->schema(fn (Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('roles')
                            ->label('选择角色')
                            ->options(fn () => $this->getOwnerRecord()->roles()->pluck('name', 'id'))
                            ->getSearchResultsUsing(fn(string $search): array => $this->getOwnerRecord()->roles()->where('name', 'like', "%{$search}%")->limit(30)->pluck('name', 'id')->toArray())
                            ->required()
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ])
                    ->after(function ($data) {      // 在这里给用户附加角色 (因为主体是 team 并不是 user, 所以 select roles 不能使用 relationship)
                        $recordTenant = $this->getOwnerRecord();        // 关系所属租户

                        $user_id = $data['recordId'];
                        $user = User::find($user_id);
                        $user->roles()->syncWithPivotValues($data['roles'], [config('permission.column_names.team_foreign_key') => $recordTenant->id]);
                    })
                    ->recordTitle(fn (Model $record) => "{$record->name} (邮箱：{$record->email})")
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                CauserTimelineAction::make()
                    ->label('操作日志')
                    ->modifyQueryUsing(function ($query) {
                        $recordTenant = $this->getOwnerRecord();        // 关系所属租户
                        $query->where('team_id', $recordTenant->id);
                    })
                    ->color('info'),
                Actions\EditAction::make(),
                Actions\DetachAction::make()
                    ->using(function (Model $record, Table $table) {
                        $recordTenant = $this->getOwnerRecord();        // 关系所属租户
                        setPermissionsTeamId($recordTenant->id);
                        // 用户与角色分离
                        $record->roles()->detach();
                        
                        // 用户与租户分离 （下面是 原 detachAction 的 process 方法内容）
                        /** @var BelongsToMany $relationship */
                        $relationship = $table->getRelationship();
                        if ($table->allowsDuplicates()) {
                            $record->getRelationValue($relationship->getPivotAccessor())->delete();
                        } else {
                            $relationship->detach($record);
                        }
                    }),
            ]);
    }
}
