<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanySetting extends FormRequest
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
            'companyName' => 'required',
            'companyStockManage' => 'required',
        ];
    }

    // Customizing The Error Messages
    public function messages()
    {
        return [
            'companyName.required' => 'Please Enter Company Name',
            'companyStockManage.required' => 'Please Select CompanyStockManage'
        ];
    }
}
