<?php

namespace App\Imports;

use App\Models\LettersGuarantee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class LettersGuaranteeImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function collection(Collection $rows)
    {

        Validator::make($rows->toArray(), [

            '*.letter_guarantee_num' => 'required|unique:letters_guarantee',
            //     '*.name_en' => 'required|unique:items_coding|name_en',
            //     '*.code' => 'required|unique:items_coding|code',
            //     // '*.unit_id' => 'required',
            //     // '*.family_name_id' => 'required',
            //     // '*.group_id' => 'required',
            //     // '*.sub_group_id' => 'required',

        ])->validate();


        foreach ($rows as $row) {

            if ($row['type'] == "Down Payment") {
                $row['type'] = "prepaid";
            }
            if ($row['type'] == "Retention") {
                $row['type'] = "instant";
            }
            $letter = LettersGuarantee::create([
                'client_type'    => "b",
                'client_id'    => $row['client_id'],
                'client_name'    => $row['client_name'],
                'side'    => $row['side'],
                'purpose'    => $row['purpose'],
                'project_number'    => $row['project_number'],
                'supply_order'    => $row['supply_order'],
                'supply_order_name'    => $row['supply_order_name'],
                'value'    => $row['value'],
                'cash_margin'    => $row['cash_margin'],
                'type'    => $row['type'],
                'bank_id'    => $row['bank_id'],
                'letter_guarantee_num'    => $row['letter_guarantee_num'],
                'release_date'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['release_date'])->format('Y-m-d'),
                'expiry_date'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['expiry_date'])->format('Y-m-d'),
                'recipient_name'    => $row['recipient_name'],

            ]);
            if (isset($row['reply_date'])) {
                LettersGuarantee::where("id", $letter->id)->update([
                    'reply_date'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['reply_date'])->format('Y-m-d'),
                ]);
            }
        }
    }
}
