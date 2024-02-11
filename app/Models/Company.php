<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $guarded = [];

    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }
}
