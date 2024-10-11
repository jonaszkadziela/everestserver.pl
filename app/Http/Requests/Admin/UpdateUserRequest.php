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
            'username' => [
                'required',
                'string',
                'lowercase',
                'alpha_dash:ascii',
                'min:3',
                'max:20',
                Rule::unique(User::class)->ignore($this->route('user')->id),
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->route('user')->id),
            ],
            'password' => [
                'nullable',
                Password::defaults(),
            ],
            'is_admin' => [
                'nullable',
                'in:on',
            ],
            'is_enabled' => [
                'nullable',
                'in:on',
            ],
            'is_verified' => [
                'nullable',
                'in:on',
            ],
            'language' => [
                'required',
                'in:' . join(',', config('app.languages')),
            ],
        ];
    }
}
