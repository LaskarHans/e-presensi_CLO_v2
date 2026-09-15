<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StorePengajuanIzinRequest extends FormRequest
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
            'tanggal' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'jenis' => ['required', 'in:izin,sakit'],
            'alasan' => ['required', 'string', 'max:1000'],
            'lampiran' => ['required', File::types(['jpg', 'jpeg', 'png', 'pdf'])->max('2mb')],
        ];
    }

    public function attributes(): array
    {
        return [
            'tanggal' => 'tanggal pengajuan',
            'jenis' => 'jenis pengajuan',
            'alasan' => 'alasan',
            'lampiran' => 'lampiran bukti',
        ];
    }
}
