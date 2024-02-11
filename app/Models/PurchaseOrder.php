<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    public $guarded = [];

    protected $appends = ['document_path'];

    public function getDocumentPathAttribute(){
        return asset('uploads/purchase_order_document/' . $this->purchaseorder_document);
    } // end of purchaseorder_document path

    public function bank()
    {
        return $this->belongsTo('App\Models\Bank');
    }

    public function deliveryCountryOrigin()
    {
        return $this->belongsTo('App\Models\Country', 'delivery_country_origin');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\Document');
    }

    public function items(){
        return $this->hasMany('App\Models\Item');
    }

    public function businessClient(){
        return $this->belongsTo('App\Models\BusinessClient', 'client_id');
    }
    public function project(){
        return $this->belongsTo('App\Models\Project', 'main_project_name');
    }

    public function foreignerClient(){
        return $this->belongsTo('App\Models\ForeignerClient', 'client_id');
    }

    public function personClient(){
        return $this->belongsTo('App\Models\PersonClient', 'client_id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'table_id')->where('table', 'PO');
    }

}
