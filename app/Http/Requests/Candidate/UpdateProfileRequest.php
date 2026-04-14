<?php

namespace App\Http\Requests\Candidate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Skills arrive as a comma-separated string from the tag input
        if ($this->has('skills') && is_string($this->input('skills'))) {
            $skills = array_values(array_filter(array_map(
                'trim',
                explode(',', (string) $this->input('skills'))
            )));

            $this->merge(['skills' => $skills]);
        }
    }

    public function rules(): array
    {
        return [
            'university' => ['required', 'string', 'max:255'],
            'degree' => ['required', 'string', 'max:255'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:12'],
            'graduation_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:16', 'max:100'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
