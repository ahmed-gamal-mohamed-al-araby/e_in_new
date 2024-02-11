<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    public $guarded = [];
    public $timestamps = false;

    public function deductionType()
    {
        return $this->belongsTo('App\Models\DeductionType', 'deductionType_id');
    }
}
