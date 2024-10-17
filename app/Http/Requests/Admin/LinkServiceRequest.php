<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LinkServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'service_id' => [
                'required',
                'exists:services,id',
            ],
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'identifier' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }
}
