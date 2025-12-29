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

    public function rules(): array
    {
        return [
            'university' => ['sometimes', 'string', 'max:255'],
            'degree' => ['sometimes', 'string', 'max:255'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:12'],
            'graduation_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['string', 'max:100'],
            'location' => ['sometimes', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:16', 'max:100'],
            'gender' => ['sometimes', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
