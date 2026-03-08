<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Gejala;


class GejalaRequest extends FormRequest
{
    protected ?Gejala $gejala = null;
    protected ?Gejala $model = null;
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Ini tempatnya
        $this->model = $this->route('gejala');
        $this->gejala = $this->route('gejala');
    }

    public function rules(): array
    {
        return [
            'code' => ['required', Rule::unique('gejala', 'code')->ignore($this->gejala?->id)],
            'gejala' => 'required|string|max:55',
        ];
    }
}
