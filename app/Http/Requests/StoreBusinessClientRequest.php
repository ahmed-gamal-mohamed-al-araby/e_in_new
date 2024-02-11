<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessClientRequest extends FormRequest
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
            'name' => 'required|max:255|min:3',
            'fax' => 'nullable|max:255|min:3',
            'mobile' => 'nullable|digits_between:4,16|unique:business_clients,mobile,' . $this->businessClient,
            'phone' => 'required|digits_between:4,16|unique:business_clients,phone,' . $this->businessClient,
            'email' => 'nullable|email|unique:business_clients,email,' . $this->businessClient,
            'gmap_url' => 'nullable|url|unique:business_clients,gmap_url,' . $this->businessClient,
            'website_url' => 'nullable|url|unique:business_clients,website_url,' . $this->businessClient,
            
            'country_id' => 'required',
            'city_id'  => 'required',
            'region_city' => 'required|min:2|max:75',
            'street' => 'required|min:5|max:100',
            'building_no'  => 'required|min:1|max:20',

            'tax_id_number' => 'required|unique:business_clients,tax_id_number,' . $this->businessClient,
            'commercial_registeration_number' => 'nullable|unique:business_clients,commercial_registeration_number,' . $this->businessClient,
            'tax_file_number' => 'nullable|unique:business_clients,tax_file_number,' . $this->businessClient,
        ];
    }
}
