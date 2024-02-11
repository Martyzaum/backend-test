<?php

namespace App\Http\Requests\Redirects;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreReq extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'destiny_url' => 'required|active_url|starts_with:https://'
        ];
    }

    public function messages()
    {
        return [
            'destiny_url.required' => 'The field destiny_url is required',
            'destiny_url.starts_with' => 'The field destiny_url have to start with https://'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 400));
    }
}
