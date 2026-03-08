<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DecisionNodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'node_code' => 'required|string|max:20|unique:decision_nodes,node_code,' . $this->id,
            'gejala_id' => 'nullable|exists:gejala,id',
            'penyakit_id' => 'nullable|exists:penyakit,id',
            'parent_id' => 'nullable|exists:decision_nodes,id',
            'yes_branch' => 'nullable|string|max:20',
            'no_branch' => 'nullable|string|max:20',
            'is_leaf' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'node_code.required' => 'Kode node wajib diisi.',
            'gejala_id.exists' => 'Gejala tidak ditemukan.',
            'penyakit_id.exists' => 'Penyakit tidak ditemukan.',
            'parent_id.exists' => 'Node induk tidak valid.',
        ];
    }
}
