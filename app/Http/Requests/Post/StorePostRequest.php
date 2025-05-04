<?php

namespace App\Http\Requests\Post;

use App\Rules\FutureDateRule;
use App\Rules\KeywordsRule;
use App\Rules\SlugRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * This method is used to verify user permissions
     * In this example, we're returning true to simplify the application
     * In a real application, you would check user permissions here
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     *
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts', new SlugRule],
            'body' => 'required|string|min:100|not_regex:/<script\b[^>]*>(.*?)<\/script>/i',
            'is_published' => 'boolean',
            'publish_date' => ['nullable', 'date', new FutureDateRule],
            'meta_description' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'keywords' => ['nullable', 'array', new KeywordsRule()],
            'keywords.*' => 'string|max:30',
        ];
    }


    /**
     * this function handel error rules message
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'slug.max' => 'The slug may not be greater than 255 characters.',
            'slug.unique' => 'The slug must be unique.',
            'body.required' => 'The body field is required.',
            'body.not_regex' => 'The content contains invalid script tags.',
            'is_published.boolean' => 'The published status must be true or false.',
            'publish_date.date' => 'The published date must be a valid date.',
            'tags.array' => 'The tags must be an array.',
            'tags.max' => 'The tags may not be greater than 50 characters.',
            'keywords.array' => 'The keywords must be an array.',
            'keywords.max' => 'The keywords may not be greater than 50 characters.',
        ];
    }


    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'user_id' => 'User',
            'slug' => 'Slug',
            'body' => 'Body',
            'is_published' => 'Is Published',
            'publish_date' => 'Publish Date',
            'meta_description' => 'Meta Description',
            'tags' => 'Tags',
            'keywords' => 'Keywords',

        ];

    }

    /**
     * this function handle the request
     *before validation
     */
    public function prepareForValidation(): void
    {
        //Generate slug automatically from title
        if($this->has('title') && !$this->has('slug')) {
            $this->merge([
                'slug' => Str::slug($this->title),
            ]);
        }

        //Generate meta automatically from title
        if ($this->has('title') && !$this->has('meta_description')) {
            $this->merge([
                'meta_description' => Str::limit(strip_tags($this->title),160)
            ]);
        }
    }

    /**
     * This method is called after successful data validation
     * It can be used to perform any additional data processing after validation
     */
    protected function passedValidation(): void
    {
        if ($this->has('tags') && is_array($this->tags)) {
            $this->merge([
                'tags' => array_map('strtolower', $this->tags),
            ]);
        }
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'failed validation',
            'errors' => $validator->errors(),
        ], 422));
    }

}
