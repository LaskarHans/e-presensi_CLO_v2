<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNamaSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSiswa() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, ValidationRule|string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama siswa',
        ];
    }
}
