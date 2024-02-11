<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Tax\TaxController;
use App\Models\DocumentItem;

class ItemController extends Controller
{
    public static $document_id = null;
    public static function set_document_id($document_id)
    {
        self::$document_id = $document_id;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($invoiceItems)
    {

          // for items per invoiceItems -> use the next foreach
          foreach ($invoiceItems as $key => $item) {
            foreach ($item as $itemIndex => $basicItemData) {
                 // $key is the key of item and the value of it
                 if (!isset($basicItemData['rate'])) {
                    $basicItemData['rate']=1;
                 }
                $item_id = DocumentItem::create([
                    'document_id' => self::$document_id,
                    'item_id' => $basicItemData['item_id'],
                    'description' =>$basicItemData['description'],
                    'quantity' =>$basicItemData['quantity'],
                    'item_price' =>$basicItemData['item_price'],
                    'rate' => $basicItemData['rate'],
                    'discount_item_rate' =>$basicItemData['discount_items_rate'] ?? 0,
                    'discount_item_amount' =>$basicItemData['discount_items_number'] ?? 0,
                    'taxable_fees' =>$basicItemData['taxable_fees'] ?? 0,
                    'value_difference' =>$basicItemData['differ_value'] ?? 0,
                    'net_total' =>$basicItemData['net_total'],
                    'total_amount' =>$basicItemData['total_amount'],
                ]);

                TaxController::set_item_id($item_id->id);
                TaxController::store($basicItemData['temp_tax_items']);
            }
        }
    }
}
