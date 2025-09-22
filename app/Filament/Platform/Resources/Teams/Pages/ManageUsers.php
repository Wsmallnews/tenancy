<?php

namespace App\Filament\Platform\Resources\Teams\Pages;

use App\Filament\Platform\Resources\Teams\TeamResource;
use App\Models\Team;
use Filament\Actions;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\TextColumn::make('roles.name')
                    ->state(function ($record) {
                        $recordTenant = $this->getOwnerRecord();        // 关系所属租户
                        
                        setPermissionsTeamId($recordTenant->id);
                        $roles = $record->roles()->get();

                        setPermissionsTeamId(null);                     // 清空租户信息

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
            ->searchPlaceholder('搜索管理员姓名、邮箱等...')
            ->headerActions([
                Actions\CreateAction::make(),
                Actions\AttachAction::make()
                    ->schema(fn (Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('roles')
                            ->label('选择角色')
                            ->options(fn () => $this->getOwnerRecord()->roles()->pluck('name', 'id'))
                            ->getSearchResultsUsing(fn(string $search): array => $this->getOwnerRecord()->roles()->where('name', 'like', "%{$search}%")->limit(30)->pluck('name', 'id')->toArray())
                            ->saveRelationshipsUsing(function (Model $record, $state) {
                                $recordTenant = $this->getOwnerRecord();        // 关系所属租户
                                $record->roles()->syncWithPivotValues($state, [config('permission.column_names.team_foreign_key') => $recordTenant->id]);
                            })
                            ->required()
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ])
                    ->recordTitle(fn (Model $record) => "{$record->name} (邮箱：{$record->email})")
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                // ActivityLogTimelineTableAction::make('Activities')
                //     ->label('操作记录')
                //     ->activitiesUsing(function (?Model $record, ActivityLogTimelineTableAction $component) {
                //         return \App\Models\Activity::query()
                //             ->with(['subject', 'causer'])
                //             ->where(function (Builder $query) use ($record, $component) {
                //                 $query->where(function (Builder $q) use ($record) {
                //                     $q->where('causer_type', $record->getMorphClass())
                //                         ->where('causer_id', $record->getKey());
                //                 })->when($component->getWithRelations(), function (Builder $query, array $relations) use ($record) {
                //                     foreach ($relations as $relation) {
                //                         $model = get_class($record->{$relation}()->getRelated());
                //                         $query->orWhere(function (Builder $q) use ($record, $model, $relation) {
                //                             $q->where('subject_type', (new $model)->getMorphClass())
                //                                 ->whereIn('subject_id', $record->{$relation}()->pluck('id'));
                //                         });
                //                     }
                //                 });
                //             })
                //             ->latest()
                //             ->limit($component->getLimit())
                //             ->get();
                //     })
                //     ->modifyTitleUsing(function ($state) {
                //         return $state['description'];
                //     })
                //     ->timelineIcons(LogEvent::getIcons(true))
                //     ->timelineIconColors(LogEvent::getColors(true))
                //     ->limit(10),
                Actions\EditAction::make(),
                Actions\DetachAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
