<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentItem extends Model
{
    public $guarded = [];

    public function DocumentTaxes(){
        return $this->hasMany('App\Models\DocumentTax');
    }

    public function basicItemData(){
        return $this->belongsTo('App\Models\Item', 'item_id');
    }

    public function document(){
        return $this->belongsTo('App\Models\Document');
    }
}
