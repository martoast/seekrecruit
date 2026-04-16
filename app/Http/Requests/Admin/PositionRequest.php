<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'salary_min' => ['nullable', 'integer', 'min:0', 'max:99999999'],
            'salary_max' => ['nullable', 'integer', 'min:0', 'max:99999999', 'gte:salary_min'],
            'salary_currency' => ['nullable', 'string', 'size:3'],
            'employment_type' => ['required', Rule::in(['full_time', 'part_time', 'internship', 'contract'])],
            'modality' => ['required', Rule::in(['on_site', 'remote', 'hybrid'])],
            'status' => ['required', Rule::in(['open', 'closed', 'draft'])],
        ];
    }

    public function messages(): array
    {
        return [
            'salary_max.gte' => 'The maximum salary must be greater than or equal to the minimum.',
            'salary_currency.size' => 'Currency must be a 3-letter code (e.g., USD, MXN).',
        ];
    }
}
