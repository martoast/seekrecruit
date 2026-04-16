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

    /**
     * HR admins can only create positions for their own client. Rather than
     * render a hidden client_id input in the form, fill it in here from the
     * authenticated user so validation still sees the required field.
     */
    protected function prepareForValidation(): void
    {
        $user = $this->user();

        if ($user && $user->isHrAdmin()) {
            $this->merge(['client_id' => $user->client_id]);
        }
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
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
            'client_id.required' => 'Please choose a client for this position.',
        ];
    }
}
