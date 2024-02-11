<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $guarded = [];
    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
