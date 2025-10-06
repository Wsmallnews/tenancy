<?php

namespace App\Livewire;

use App\Models\Personnel as PersonnelModel;
use Livewire\Attributes\Url;

class Personnels extends Base
{

    public function render()
    {
        return view('livewire.personnels');
    }
}