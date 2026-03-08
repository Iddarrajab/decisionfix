<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_user' => 'required|string|max:100',
            'gejala_dipilih' => 'required|array|min:1',
            'gejala_dipilih.*' => 'exists:gejala,id',

            // CF untuk tiap gejala boleh kosong tapi harus array
            'cf_gejala' => 'array',
            'cf_gejala.*' => 'nullable|numeric|min:0|max:1',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_user.required' => 'Nama pengguna atau hewan wajib diisi.',
            'gejala_dipilih.required' => 'Pilih minimal satu gejala untuk diagnosa.',
            'gejala_dipilih.*.exists' => 'Gejala yang dipilih tidak valid.',

            'cf_gejala.array' => 'Nilai CF harus dalam format yang benar.',
            'cf_gejala.*.numeric' => 'Nilai CF harus berupa angka desimal antara 0 dan 1.',
            'cf_gejala.*.min' => 'Nilai CF minimal adalah 0.',
            'cf_gejala.*.max' => 'Nilai CF maksimal adalah 1.',
        ];
    }
}
