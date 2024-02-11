<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'product_name' => 'required|max:255|min:3',
            'product_code' => 'required|unique:products,product_code,'. $this->product,
            'internal_code' => 'required|numeric|unique:products,internal_code,'. $this->product,
            'standard_code_type' => 'required',
        ];
    }
}
