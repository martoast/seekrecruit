<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'scheduled_at' => ['sometimes', 'date', 'after:now'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['sometimes', Rule::in(['technical', 'hr', 'final'])],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
