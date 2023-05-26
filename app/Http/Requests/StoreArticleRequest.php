<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
        return [
            'translations' => 'required|array|min:3',
            'translations.*.title' => 'required|max:255|unique:article_translations,title',
            'translations.*.text' => 'required',
            'translations.*.language_code' => 'required|in:en,ar,ja|distinct',
        ];
    }
}
