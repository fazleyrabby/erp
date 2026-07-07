<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckOutCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    // this is used In SaleController (CheckOutCart Function)
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customerName' => 'required|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'customerAddress' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'partyPhoneNumber' => 'required',
            'category' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'customerName.required' => 'Party is required',
            'partyPhoneNumber.required' => 'Phone Number is required',
            'category.required' => 'Category required',
        ];
    }
}
