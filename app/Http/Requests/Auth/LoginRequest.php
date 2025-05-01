<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define validation rules for login.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'email' => 'Email Address',
            'password' => 'Password',
        ];
    }

    /**
     * Sanitize input after validation passes.
     */
    protected function passedValidation(): void
    {
        $this->replace($this->sanitizeInput());
    }

    /**
     * Strip unwanted input formatting.
     */
    protected function sanitizeInput(): array
    {
        $sanitized = $this->validated();

        if (isset($sanitized['email'])) {
            $sanitized['email'] = strtolower(trim($sanitized['email']));
        }

        return $sanitized;
    }

    /**
     * Prepare input before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim($this->input('email'))),
        ]);
    }

    /**
     * Handle failed validation.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        throw ValidationException::withMessages($validator->errors()->toArray());
    }
}
