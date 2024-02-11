<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransfer extends Model
{
    public $guarded = [];

    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'payment_method_id')->where('payment_method', 'bank_transfer');
    }

    public function bank()
    {
        return $this->belongsTo('App\Models\Bank');
    }
}
