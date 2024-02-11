<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    public $guarded = [];

    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'payment_method_id')->where('payment_method', 'cheque');
    }

    public function bank()
    {
        return $this->belongsTo('App\Models\Bank');
    }

}
