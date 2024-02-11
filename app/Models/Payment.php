<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];
//           protected $fillable;
    public function businessClient(){
        return $this->belongsTo('App\Models\BusinessClient', 'client_id');
    }

    public function foreignerClient(){
        return $this->belongsTo('App\Models\ForeignerClient', 'client_id');
    }

    public function personClient(){
        return $this->belongsTo('App\Models\PersonClient', 'client_id');
    }

    public function purchaseOrder(){
        return $this->belongsTo('App\Models\PurchaseOrder', 'table_id');
    }

    public function document(){
        return $this->belongsTo('App\Models\Document', 'table_id');
    }

    public function cheque(){
        return $this->belongsTo('App\Models\Cheque', 'payment_method_id');
    }

    public function bank_transfer(){
        return $this->belongsTo('App\Models\BankTransfer', 'payment_method_id');
    }

    public function deductions(){
        return $this->hasMany('App\Models\PaymentDeduction');
    }
}
