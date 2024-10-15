<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateServiceRequest extends FormRequest
{
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'createService';

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Service::class),
            ],
            'description' => [
                'required',
                'json',
            ],
            'icon' => [
                'required',
                'string',
                'max:255',
            ],
            'link' => [
                'required',
                'url',
                'max:255',
            ],
            'is_public' => [
                'nullable',
                'in:on',
            ],
            'is_enabled' => [
                'nullable',
                'in:on',
            ],
        ];
    }
}
