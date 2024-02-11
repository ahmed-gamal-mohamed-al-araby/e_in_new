<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarrantyChecks extends Model
{
    use SoftDeletes;
    public $guarded = [];

    public function businessClient(){
        return $this->belongsTo('App\Models\BusinessClient', 'client_id');
    }

    public function foreignerClient(){
        return $this->belongsTo('App\Models\ForeignerClient', 'client_id');
    }

    public function personClient(){
        return $this->belongsTo('App\Models\PersonClient', 'client_id');
    }
    public function bank()
    {
        return $this->belongsTo('App\Models\Bank');
    }

    public function purchaseOrder(){
        return $this->belongsTo('App\Models\PurchaseOrder', 'supply_order');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'giver_name');
    }
}
