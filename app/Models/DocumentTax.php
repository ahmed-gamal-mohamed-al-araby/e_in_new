<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTax extends Model
{
    public $guarded = [];

    public function purchaseOrderTax(){
        return $this->belongsTo('App\Models\PurchaseOrderTax', 'purchase_order_tax_id');
    }
}
