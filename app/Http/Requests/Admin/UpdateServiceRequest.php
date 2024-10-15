<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'updateService';

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'new_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Service::class, 'name')->ignore($this->route('service')->id),
            ],
            'new_description' => [
                'required',
                'json',
            ],
            'new_icon' => [
                'required',
                'string',
                'max:255',
            ],
            'new_link' => [
                'required',
                'url',
                'max:255',
            ],
            'new_is_public' => [
                'nullable',
                'in:on',
            ],
            'new_is_enabled' => [
                'nullable',
                'in:on',
            ],
        ];
    }
}
