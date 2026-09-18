<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RekapKelasRequest extends FormRequest
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
            'bulan' => ['nullable', 'date_format:Y-m'],
            'tanggal_mulai' => ['nullable', 'date_format:Y-m-d', 'required_with:tanggal_selesai'],
            'tanggal_selesai' => ['nullable', 'date_format:Y-m-d', 'required_with:tanggal_mulai', 'after_or_equal:tanggal_mulai'],
        ];
    }
}
