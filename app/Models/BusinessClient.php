<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessClient extends Model
{
    public $guarded = [];

    public function businessClientUser(){
        return $this->hasMany('App\Models\BusinessClientUser');
    }

    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }

    public function purchaseOrders()
    {
        return $this->hasMany('App\Models\PurchaseOrder', 'client_id')->where('client_type', 'b');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'client_id')->where('client_type', 'b');
    }
}
