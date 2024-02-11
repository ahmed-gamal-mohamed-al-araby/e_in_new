<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = [];

    public function purchaseOrder(){
        return $this->belongsTo('App\Models\PurchaseOrder');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }

    public function purchaseOrderTaxes(){
        return $this->hasMany('App\Models\PurchaseOrderTax');
    }

    public function documentItems(){
        return $this->hasMany('App\Models\DocumentItem');
    }

    public function productUnit()
    {
        return $this->belongsTo('App\Models\ProductUnit', 'unit', 'code');
    }
}
