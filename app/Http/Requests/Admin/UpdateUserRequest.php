<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'editUser';

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'new_username' => [
                'required',
                'string',
                'lowercase',
                'alpha_dash:ascii',
                'min:3',
                'max:20',
                Rule::unique(User::class, 'username')->ignore($this->route('user')->id),
            ],
            'new_email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($this->route('user')->id),
            ],
            'new_password' => [
                'nullable',
                Password::defaults(),
            ],
            'new_is_admin' => [
                'nullable',
                'in:on',
            ],
            'new_is_enabled' => [
                'nullable',
                'in:on',
            ],
            'new_is_verified' => [
                'nullable',
                'in:on',
            ],
            'new_language' => [
                'required',
                'in:' . join(',', config('app.languages')),
            ],
        ];
    }
}
