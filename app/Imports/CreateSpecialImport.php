<?php

namespace App\Imports;

use App\Models\DocumentItem;
use App\Models\Item;
use App\Models\WarrantyChecks;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class CreateSpecialImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function collection(Collection $rows)
    {

        Validator::make($rows->toArray(), [

            '*.document_id' => 'required|exists:documents,id',
            '*.item_id' => 'required|exists:items,id',
            '*.quantity' => 'required',

        ])->validate();


        foreach ($rows as $row) {

            if ($row['quantity'] > 0) {

                $item = Item::where("id", $row['item_id'])->first();
                $total = $item->item_price * $row['quantity'];
                $check = DocumentItem::create([
                    'document_id'  => $row['document_id'],
                    'item_id'  => $row['item_id'],
                    'description'  => $item->description,
                    'quantity'  => $row['quantity'],
                    'item_price'  => $item->item_price,
                    'rate'  => 1,
                    'discount_item_rate'  => 0,
                    'discount_item_amount'  => 0,
                    'taxable_fees'  => 0,
                    'value_difference'  => 0,
                    'net_total'  => $total,
                    'total_amount'  => $total,
                ]);
            }
        }
    }
}
