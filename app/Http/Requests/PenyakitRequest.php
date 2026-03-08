<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenyakitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'     => 'required|string|max:10|unique:penyakit,code,' . $this->id,
            'penyakit' => 'required|string|max:100',
            'solusi'   => 'required|string',
            'obat'     => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'     => 'Kode wajib diisi.',
            'code.unique'       => 'Kode sudah digunakan.',
            'penyakit.required' => 'Nama penyakit wajib diisi.',
            'solusi.required'   => 'Solusi wajib diisi.',
            'obat.required'     => 'Obat wajib diisi.',
        ];
    }
}
