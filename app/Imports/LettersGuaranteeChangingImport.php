<?php

namespace App\Imports;

use App\Models\LettersGuarantee;
use App\Models\LettersGuaranteeChanging;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class LettersGuaranteeChangingImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function collection(Collection $rows)
    {

        Validator::make($rows->toArray(), [

            '*.letter_guarantee_num' => 'required',
            '*.value' => 'required',
            '*.cash_margin' => 'required',
            '*.expiry_date' => 'required',
        //     // '*.unit_id' => 'required',
        //     // '*.family_name_id' => 'required',
        //     // '*.group_id' => 'required',
        //     // '*.sub_group_id' => 'required',

        ])->validate();


        foreach ($rows as $row) {       
            $letters_guarantee_id=LettersGuarantee::where('letter_guarantee_num',$row['letter_guarantee_num'])->pluck('id')->first();
            if (isset($letters_guarantee_id)) {
                LettersGuaranteeChanging::create([
                    'letters_guarantee_id'    => $letters_guarantee_id,
                    'value'    => $row['value'],
                    'cash_margin'    => $row['cash_margin'],
                    'user_id'    => auth()->user()->id,
                    'expiry_date'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['expiry_date'])->format('Y-m-d'),
                    
                ]);
            }
            else{
                
                dd($row['letter_guarantee_num'] ." is not correct");
            }
           
        }
    }
}
