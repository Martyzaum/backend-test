<?php

namespace App\Http\Requests\Redirects;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateReq extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'destiny_url' => 'active_url|starts_with:https://',
            'status' => 'in:active,inactive'
        ];
    }

    public function messages()
    {
        return [
            'destiny_url.starts_with' => 'The field destiny_url have to start with https://',
            'status.in' => 'The field status must be active or inactive'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 400));
    }
}
