<?php

namespace App\Http\Requests\Admin;

use App\Http\Controllers\Admin\PanelController;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'column' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'direction' => [
                'sometimes',
                'required',
                'in:asc,desc',
            ],
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],
            'tab' => [
                'nullable',
                'in:' . implode(',', PanelController::ALLOWED_TABS),
            ],
        ];
    }
}
