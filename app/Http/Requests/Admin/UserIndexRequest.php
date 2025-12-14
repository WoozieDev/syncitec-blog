<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', \App\Models\User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:100'],
            'trashed' => ['nullable', 'in:with,only'],
        ];
    }

    public function filters(): array
    {
        return [
            'search' => $this->input('search'),
            'role' => $this->input('role'),
            'trashed' => $this->input('trashed'),
        ];
    }
}
