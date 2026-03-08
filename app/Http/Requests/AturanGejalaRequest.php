<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AturanGejalaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'aturan_id' => 'required|exists:aturan,id',
            'gejala_id' => 'required|exists:gejala,id',
            'cf' => 'required|numeric|min:0|max:1',
        ];
    }

    public function messages(): array
    {
        return [
            'aturan_id.required' => 'Aturan harus dipilih.',
            'gejala_id.required' => 'Gejala harus dipilih.',
            'cf.required' => 'Nilai CF wajib diisi.',
            'cf.numeric' => 'Nilai CF harus berupa angka antara 0 sampai 1.',
            'cf.min' => 'Nilai CF minimal 0.',
            'cf.max' => 'Nilai CF maksimal 1.',
        ];
    }
}
