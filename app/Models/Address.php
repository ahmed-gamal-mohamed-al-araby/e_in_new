<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
}
