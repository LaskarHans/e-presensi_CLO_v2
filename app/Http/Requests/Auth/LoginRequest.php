<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'nomor_induk' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     */
    public function authenticate(): void
    {
        $credentials = $this->safe()->only(['nomor_induk', 'password']);

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            throw ValidationException::withMessages([
                'nomor_induk' => 'Nomor induk atau kata sandi tidak valid.',
            ]);
        }
    }
}
