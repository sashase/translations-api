<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route('article');
        $languageCode = $this->input('translations.*.language_code');

        return [
            'translations' => 'required|array',
            'translations.*.language_code' => 'required|in:en,ar,ja',
            'translations.*.title' => [
                'required',
                'max:255',
                Rule::unique('article_translations', 'title')
                    ->where('article_id', $id)
                    ->whereIn('language_code', $languageCode),
            ],
            'translations.*.text' => 'required',
        ];
    }
}
