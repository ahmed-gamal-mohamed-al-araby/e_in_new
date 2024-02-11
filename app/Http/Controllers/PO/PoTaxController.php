<?php

namespace App\Http\Controllers\PO;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrderTax;

class PoTaxController extends Controller
{
    public static $item_id = null;
    public static function set_po_item_id($item_id)
    {
        self::$item_id = $item_id;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($basicItemData){
        foreach ($basicItemData['tax_items'] as $taxIndex => $tax) {
            $tax['amount_tax'] = $tax['row_total_tax'];
            $tax['item_id'] = self::$item_id;
            unset($tax['row_total_tax']);
            PurchaseOrderTax::create($tax);
        }
    }

    public static function storeIndividualItemTaxes($taxes){
        foreach ($taxes as $taxIndex => $tax) {
            $tax['item_id'] = self::$item_id;
            PurchaseOrderTax::create($tax);
        }
    }
    
}
