<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectIzinRequest extends FormRequest
{
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
            'catatan_verifikasi' => ['required', 'string', 'max:1000'],
        ];
    }
}
