<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    public function scopeScopeTenant($query)
    {
        if (has_tenancy()) {
            return $query->where('team_id', current_tenant()->id);
        } else {
            return $query->whereNull('team_id');
        }
    }
}
