<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'vcard'   =>   'required',
            'date'   =>   'required',
            'datetime'   =>   'required',
            'value'   =>   'required',
            'old_balance'   =>   'required',
            'new_balance'   =>   'required',
            'payment_type'   =>   'required',
            'payment_reference'   =>   'required',
        ];
    }
}
