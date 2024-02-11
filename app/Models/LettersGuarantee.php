<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LettersGuarantee extends Model
{
    use SoftDeletes;

    protected $table = 'letters_guarantee';
    protected $guarded = [];

    public function purchaseOrder(){
        return $this->belongsTo('App\Models\PurchaseOrder', 'supply_order');
    }
    
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

    public function user()
    {
        return $this->belongsTo('App\User', 'giver_name');
    }

   

}
