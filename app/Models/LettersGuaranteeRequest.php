<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LettersGuaranteeRequest extends Model
{
    use SoftDeletes;
    protected $table = 'letters_guarantee_request';

    public $guarded = [];

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
    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'main_project_name');
    }
}
