<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = [];

    public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\PurchaseOrder','purchase_order_id');
    }

    public function items(){
        return $this->hasMany('App\Models\DocumentItem');
    }

    public function company(){
        return $this->belongsTo('App\Models\Company');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'table_id')->where('table', 'D');
    }

    public function documentinvalidsteps()
    {
        return $this->hasOne('App\Models\DocumentsInvalidSteps');
    }
    public function purchaseOrderTaxes(){
        return $this->hasMany('App\Models\PurchaseOrderTax');
    }
}
