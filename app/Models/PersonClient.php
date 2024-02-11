<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonClient extends Model
{
    public $guarded = [];

    public function purchaseOrders()
    {
        return $this->hasMany('App\Models\PurchaseOrder', 'client_id')->where('client_type', 'p');
    }

    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'client_id')->where('client_type', 'p');
    }
}
