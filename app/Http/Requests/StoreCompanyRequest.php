<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_name' => 'required|max:255|min:3',
            'commercial_registeration_number' => 'required|max:7|min:4|unique:companies,commercial_registeration_number,'. $this->company,
            'tax_id_number' => 'required|max:11|min:11|unique:companies,tax_id_number,'. $this->company,
            'tax_file_number' => 'required',
            'country_id' => 'required',
            'city_id'  => 'required',
            'region_city' => 'required|min:2|max:75',
            'street' => 'required|min:5|max:100',
            'building_no'  => 'required|min:1|max:20',
        ];
    }
}
