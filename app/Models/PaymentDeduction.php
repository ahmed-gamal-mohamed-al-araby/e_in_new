<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDeduction extends Model
{
    public $guarded = [];
    public $timestamps = false;

    public function payment(){
        return $this->belongsTo('App\Models\Payment', 'payment_id');
    }

    public function deduction()
    {
        return $this->belongsTo('App\Models\Deduction');
    }
}
