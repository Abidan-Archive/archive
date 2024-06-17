<?php

namespace App\Http\Requests;

use App\Rules\ReCaptchaV3;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'username' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'confirmed', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'new_password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
            'password' => ['required', 'current_password'],
            'recaptcha' => ['required', new ReCaptchaV3('user/update', 0.5)],
        ];
    }
}
