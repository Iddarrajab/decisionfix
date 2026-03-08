<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiagnosaRequest extends FormRequest
{
    /**
     * Menentukan apakah user diizinkan untuk mengirim request ini.
     */
    public function authorize(): bool
    {
        // Jika sistem kamu tidak pakai auth untuk user umum, biarkan true
        return true;
    }

    /**
     * Aturan validasi untuk menyimpan diagnosa.
     */
    public function rules(): array
    {
        return [
            'nama_hewan' => ['nullable', 'string', 'max:100'],
            'gejala_terpilih' => ['required', 'array', 'min:1'],
            'gejala_terpilih.*' => ['exists:gejalas,id'],
            'cf_gejala' => ['required', 'array'],
            'cf_gejala.*' => ['numeric', 'min:0', 'max:1'],
        ];
    }

    /**
     * Pesan error khusus untuk validasi.
     */
    public function messages(): array
    {
        return [
            'gejala_terpilih.required' => 'Pilih minimal satu gejala untuk melakukan diagnosa.',
            'gejala_terpilih.*.exists' => 'Terdapat gejala yang tidak valid dalam daftar.',
            'cf_gejala.required' => 'Nilai CF untuk setiap gejala wajib diisi.',
            'cf_gejala.*.numeric' => 'Nilai CF harus berupa angka desimal antara 0 dan 1.',
            'cf_gejala.*.min' => 'Nilai CF tidak boleh kurang dari 0.',
            'cf_gejala.*.max' => 'Nilai CF tidak boleh lebih dari 1.',
        ];
    }
}
