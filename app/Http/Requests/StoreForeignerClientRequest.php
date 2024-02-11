<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreForeignerClientRequest extends FormRequest
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
            // 'person_name' => 'required|max:255|min:3',
            // 'person_email' => 'required|email|unique:foreigner_clients,person_mobile,' . $this->foreignerClient,
            // 'person_mobile' => 'required|digits_between:4,16|unique:foreigner_clients,person_mobile,' . $this->foreignerClient,
            // 'vat_id' => 'nullable|unique:foreigner_clients,vat_id,'. $this->foreignerClient,

            // 'national_id' => 'required|digits:14|unique:foreigner_clients,national_id,' . $this->foreignerClient,
            // 'person_mobile' => 'nullable|digits_between:4,16|unique:foreigner_clients,person_mobile,'. $this->person_client,
            'country_id' => 'required',
            // 'city_id'  => 'required',
            // 'region_city' => 'required|min:2|max:75',
            // 'street' => 'required|min:5|max:100',
            // 'building_no'  => 'required|min:1|max:20',
        ];
    }
}
