<?php

/**
 *مافي ولا كومنت هون
 *نفسن تبعوت ال storePost
 *تعبت انا و ترجم للانغلش وهندل الدكيومنت
 *فدا عيون الاستاذ يوسف
 */


namespace App\Http\Requests\Post;

use App\Rules\FutureDateRule;
use App\Rules\KeywordsRule;
use App\Rules\SlugRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('posts')->ignore($this->route('post')),
                new SlugRule,
            ],
            'body' => 'sometimes|string|min:100|not_regex:/<script\b[^>]*>(.*?)<\/script>/i',
            'is_published' => 'boolean',
            'publish_date' => ['nullable', 'date', new FutureDateRule],
            'meta_description' => 'sometimes|nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'keywords' => [ 'nullable', 'array', new KeywordsRule()],
            'keywords.*' => 'string|max:30',
        ];
    }


    public function messages(): array
    {

        return [
            'title.required' => 'The post title is required.',
            'title.max' => 'The title may not be greater than :max characters.',
            'title.unique' => 'This title already exists. Please choose a different one.',
            'slug.required' => 'The post slug is required.',
            'slug.unique' => 'This slug is already in use. Please choose another one.',
            'body.required' => 'The post content is required.',
            'body.min' => 'The post content must be at least :min characters.',
            'is_published.boolean' => 'The published status must be true or false.',
            'publish_date.date' => 'The publish date must be a valid date.',
            'tags.max' => 'You can add maximum :max tags.',
            'tags.*.distinct' => 'Duplicate tags are not allowed.',
            'keywords.*.distinct' => 'Duplicate keywords are not allowed.',
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
            'title' => 'post title',
            'slug' => 'post slug',
            'body' => 'post content',
            'is_published' => 'publication status',
            'publish_date' => 'publication date',
            'meta_description' => 'meta description',
            'tags' => 'tags',
            'keywords' => 'keywords',
        ];
    }


    protected function prepareForValidation(): void
    {
        if ($this->has('title') && !$this->has('slug')) {
            $this->merge([
                'slug' => Str::slug($this->title),
            ]);
        }

        if ($this->has('title') && $this->has('body') && !$this->has('meta_description')) {
            $description = $this->title . ' - ' . Str::words(strip_tags($this->body), 10);
            $this->merge([
                'meta_description' => Str::limit($description, 160),
            ]);
        }
    }

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
