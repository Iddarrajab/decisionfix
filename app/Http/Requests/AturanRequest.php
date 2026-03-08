<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AturanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:20|unique:aturan,code,' . $this->id,
            'penyakit_id' => 'required|exists:penyakit,id',
            'gejala_ids' => 'required|array|min:1',
            'gejala_ids.*' => 'exists:gejala,id',
            'cf' => 'required|array',
            'cf.*' => 'nullable|numeric|min:0|max:1',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode aturan wajib diisi.',
            'penyakit_id.required' => 'Silakan pilih penyakit.',
            'gejala_ids.required' => 'Pilih minimal satu gejala.',
            'cf.required' => 'Nilai CF setiap gejala wajib diisi.',
        ];
    }
}
