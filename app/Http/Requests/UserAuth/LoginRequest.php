<?php

namespace App\Http\Requests\UserAuth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|min:6',
        ];
    }
}
