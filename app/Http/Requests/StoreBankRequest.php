<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankRequest extends FormRequest
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
            'bank_code' => 'required|numeric|unique:banks,bank_code,'. $this->bank,
            'bank_name' => 'required',
            'currency' => 'required',
            'bank_account_number' => 'required',
            'bank_account_iban' => 'required',
            'swift_code' => 'required',
            'bank_address' => 'required',
        ];
    }
}
