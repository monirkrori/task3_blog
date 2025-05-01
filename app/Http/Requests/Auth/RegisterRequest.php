<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'max:50',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],

        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name is required.',
            'name.max' => 'The name must not exceed 255 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email is already taken.',
            'password.required' => 'The password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'The password must be at least 8 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',

        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        // You can add post-validation handling here
        $this->replace($this->sanitizeInput());
    }

    /**
     * Sanitize the input data.
     *
     * @return array
     */
    protected function sanitizeInput(): array
    {
        // Clean up unwanted input data
        $sanitized = $this->validated();

        // Strip HTML tags from the name
        if (isset($sanitized['name'])) {
            $sanitized['name'] = strip_tags($sanitized['name']);
        }

        return $sanitized;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Pre-process data before validation
        $this->merge([
            'email' => strtolower($this->input('email')),
            'name' => trim($this->input('name')),
        ]);
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        // Customize validation failure behavior
        throw ValidationException::withMessages($validator->errors()->toArray());
    }
}
