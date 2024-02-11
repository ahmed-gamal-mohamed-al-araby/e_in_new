<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $guarded = [];
    public $timestamps = false;


    public function cities() {
        return $this->hasMany('App\Models\City');
    }
}
