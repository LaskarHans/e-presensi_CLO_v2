<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KoreksiPresensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpha'])],
            'alasan_koreksi' => ['required', 'string', 'max:1000'],
        ];
    }
}
