<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LettersGuaranteeChanging extends Model
{
    use SoftDeletes;
    protected $table = 'letters_guarantee_changing';

    public $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
