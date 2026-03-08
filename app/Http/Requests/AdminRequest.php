<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:50'],
            'email' => ['required', 'email', Rule::unique('admins', 'email')->ignore($this->admin?->id)],
            'password' => ['required', 'min:8'],
        ];
    }
}
