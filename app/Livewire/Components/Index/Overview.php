<?php

namespace App\Livewire\Components\Index;

use App\Models\Appraise as AppraiseModel;
use App\Models\Award as AwardModel;
use App\Models\NewVariety as NewVarietyModel;
use App\Models\Patent as PatentModel;
use App\Models\Personnel as PersonnelModel;
use App\Models\Thesis as ThesisModel;
use Filament\Support\Icons\Heroicon;
use Wsmallnews\Cms\Livewire\Components\Base;

class Overview extends Base
{
    public function render()
    {
        // 新品种数量
        $newVarietyCount = NewVarietyModel::query()->scopeTenant()->normal()->count();

        // 种质资源数量
        $appraiseCount = AppraiseModel::query()->scopeTenant()->normal()->count();

        // 科研专家数量
        $personnelCount = PersonnelModel::query()->scopeTenant()->normal()->count();

        // 科研成果数量 (专利数量 + 奖项数量 + 论文数量)
        $patentCount = PatentModel::query()->scopeTenant()->authd()->count()     // 专利数量
            + AwardModel::query()->scopeTenant()->normal()->count()              // 奖项数量
            + ThesisModel::query()->scopeTenant()->normal()->count();            // 论文数量

        $stats = [
            [
                'title' => '新品种',
                'value' => $newVarietyCount,
                'icon' => Heroicon::BookOpen,
            ],
            [
                'title' => '种质资源',
                'value' => $appraiseCount,
                'icon' => Heroicon::CircleStack,
            ],
            [
                'title' => '科研专家',
                'value' => $personnelCount,
                'icon' => Heroicon::Users,
            ],
            [
                'title' => '科研成果',
                'value' => $patentCount,
                'icon' => Heroicon::Trophy,
            ],
        ];

        return view('livewire.components.index.overview', [
            'stats' => $stats,
        ]);
    }
}
