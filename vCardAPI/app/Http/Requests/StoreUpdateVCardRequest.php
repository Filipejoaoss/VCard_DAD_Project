<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateVCardRequest extends FormRequest
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
            'phone_number' => 'required',
            'name' => 'required',
            'email' => 'required',
            'photo_url' => 'optional',
            'password' => 'required',
            'confirmation_code' => 'required',
            'custom_options' => 'optional',
            'custom_data' => 'optional',
        ];
    }
}
