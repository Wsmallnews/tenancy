<?php

namespace App\Livewire;

use App\Models\Appraise as AppraiseModel;
use Livewire\Attributes\Url;

class Appraise extends Base
{

    public int $id;
    
    public function render()
    {
        return view('livewire.appraise');
    }
}