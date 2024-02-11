<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    public $guarded = [];

    public function purchaseorders()
    {
        return $this->hasMany('App\Models\PurchaseOrder');
    }
}
