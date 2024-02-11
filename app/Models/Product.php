<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $guarded = [];

    public function invoicesItems()
    {
        return $this->hasOne('App\Models\Item');
    }

}
