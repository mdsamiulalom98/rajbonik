<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Hostel extends Authenticatable
{
    protected $guard = 'hostel';

    protected $guarded = [];
    
    public function district(){
        return $this->hasOne('App\Models\District','id','district_id');
    }
}
