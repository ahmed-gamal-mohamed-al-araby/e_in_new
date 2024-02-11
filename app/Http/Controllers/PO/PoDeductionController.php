<?php

namespace App\Http\Controllers\PO;

use App\Http\Controllers\Controller;
use App\Models\Deduction;
use App\Models\po_deduction;

class PoDeductionController extends Controller
{
    public static $purchase_order_id = null;

    public static function set_purchase_order_id($purchase_order_id)
    {
        self::$purchase_order_id = $purchase_order_id;
    }

    public static function store($poDeductions)
    {
        foreach ($poDeductions['deductions'] as $key => $deduction) {
            $deduction['value'] = $deduction['row_total_tax'];
            $deduction['deduction_id'] = $deduction['row_total_tax'];
            po_deduction::create($deduction);

            foreach ($deduction as $deductionIndex => $basicDeductionData) {
                // $key is the key of item and the value of it

                $deduction = Deduction::create([
                    'purchase_order_id' => self::$purchase_order_id,
                    'deduction_name' => $basicDeductionData['deduction_name'],
                    'value' => $basicDeductionData['value'],
                ]);

//                PoDeductionController::set_po_item_id($deduction->id);
                PoDeductionController::store($basicDeductionData);
            }
        }
    }
}
