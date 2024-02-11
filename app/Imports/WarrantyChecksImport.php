<?php

namespace App\Imports;

use App\Models\WarrantyChecks;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class WarrantyChecksImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function collection(Collection $rows)
    {

        Validator::make($rows->toArray(), [

            '*.cheque_number' => 'required|unique:warranty_checks',
        //     '*.name_en' => 'required|unique:items_coding|name_en',
        //     '*.code' => 'required|unique:items_coding|code',
        //     // '*.unit_id' => 'required',
        //     // '*.family_name_id' => 'required',
        //     // '*.group_id' => 'required',
        //     // '*.sub_group_id' => 'required',

        ])->validate();


        foreach ($rows as $row) {

            if ($row['type'] =="payment_accepted"){

                $row['bank_id']=null;
            }
            else{
                $row['bank_name']=null;
 
            }
         
           $check= WarrantyChecks::create([
                'client_type'    => "b",
                'client_id'    => $row['client_id'],
                'side'    => $row['side'],
                'purpose'    => $row['purpose'],
                'project_number'    => $row['project_number'],
                'supply_order'    => $row['supply_order'],
                'value'    => $row['value'],
                'bank_id'    => $row['bank_id'],
                'type'    => $row['type'],

                'recipient_name'    => $row['recipient_name'],
                'giver_name'    => auth()->user()->id,
                'source_name'    => $row['source_name'],
                'bank_name'    => $row['bank_name'],
                'cheque_number'    => $row['cheque_number'],
                'document_nature'    => $row['document_nature'],
                'check_date'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['check_date'])->format('Y-m-d'),

            ]);

            if (isset($row['reply_date'])) {
                WarrantyChecks::where("id", $check->id)->update([
                    'reply_date'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['reply_date'])->format('Y-m-d'),
                ]);
            }
        }
    }
}
