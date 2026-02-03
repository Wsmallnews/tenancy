<?php

namespace App\Livewire\User;

use App\Livewire\Base;
use App\Enums\AppraiseApplies\Status;
use App\Models\AppraiseApply as AppraiseApplyModel;
use Filament\Actions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns;
use Illuminate\Http\Request;
use Livewire\Attributes\Title;
use Wsmallnews\Cms\Support\Utils as CmsUtils;

class AppraiseApplies extends Base implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $user = auth()->guard(CmsUtils::getConfig('guard', 'web'))->user();

        return $table
            ->query(AppraiseApplyModel::query()->scopeTenant()
                ->with(['appraise', 'team'])
                ->where('user_id', $user->id)
                ->orderBy('id', 'desc'))
            ->columns([
                Columns\SpatieMediaLibraryImageColumn::make('appraise.cover')
                    ->label('种质封面图')
                    ->collection('cover')
                    ->toggleable(),
                Columns\TextColumn::make('appraise.resource_no')
                    ->label('全国统一编号')
                    ->searchable()
                    ->toggleable(),
                Columns\TextColumn::make('appraise.name')
                    ->label('种质中文名')
                    ->searchable()
                    ->toggleable(),
                Columns\TextColumn::make('name')
                    ->label('申请人')
                    ->searchable()
                    ->toggleable(),
                Columns\TextColumn::make('phone')
                    ->label('联系方式')
                    ->searchable()
                    ->toggleable(),
                Columns\TextColumn::make('company_name')
                    ->label('用种单位')
                    ->searchable()
                    ->toggleable(),
                Columns\TextColumn::make('status')
                    ->label('Status'),
                Columns\TextColumn::make('created_at')
                    ->label('申请时间')
                    ->toggleable()
                    ->sortable(),
                Columns\TextColumn::make('updated_at')
                    ->label('处理时间')
                    ->toggleable()
                    ->sortable(),
            ])
            ->recordActions([
                Actions\ViewAction::make()->url(fn(AppraiseApplyModel $record): string => CmsUtils::route('user.appraise-applies.show', ['id' => $record->id])),
            ]);
    }


    #[Title('种质申请')]
    public function render()
    {
        $breadcrumbs = [
            ['label' => '个人中心', 'url' => CmsUtils::route('profile')],
            ['label' => '种质申请', 'url' => CmsUtils::route('user.appraise-applies')],
        ];

        return view('livewire.user.appraise-applies', [
            'breadcrumbs' => $breadcrumbs,
        ])->layout(CmsUtils::getLayout());
    }
}
