<?php

namespace App\Livewire;

use App\Models\Personnel as PersonnelModel;
use Livewire\Attributes\Url;

class Personnel extends Base
{

    public int $id;

    public function render()
    {
        return view('livewire.personnel');
    }
}