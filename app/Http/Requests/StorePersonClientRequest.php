<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonClientRequest extends FormRequest
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
            'national_id' => 'required|digits:14|unique:person_clients,national_id,'. $this->personClient,
            'mobile' => 'required|digits_between:4,16|unique:person_clients,mobile,'. $this->personClient,
            // 'mobile' => 'nullable|digits_between:4,16|unique:person_clients,mobile,'. $this->person_client,
            'country_id' => 'required',
            'city_id'  => 'required',
            'region_city' => 'required|min:2|max:75',
            'street' => 'required|min:5|max:100',
            'building_no'  => 'required|min:1|max:20',
        ];
    }
}
