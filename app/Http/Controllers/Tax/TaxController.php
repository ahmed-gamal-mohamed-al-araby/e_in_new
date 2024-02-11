<?php

namespace App\Http\Controllers\Tax;

use App\Http\Controllers\Controller;
use App\Models\DocumentTax;

class TaxController extends Controller
{
    public static $item_id = null;
    public static function set_item_id($item_id)
    {
        self::$item_id = $item_id;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($basicItemDataTaxes){
        foreach ($basicItemDataTaxes as $taxIndex => $tax) {
            $tax['document_item_id'] = self::$item_id;
            $d = DocumentTax::create($tax);
        }
    }

}



