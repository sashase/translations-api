<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $languages = config('languages');

        $id = $this->route('article');

        return [
            'translations' => 'required|array',
            'translations.*.language_code' => ['required', Rule::in($languages), 'distinct'],
            'translations.*.title' => [
                'required',
                'max:255',
                Rule::unique('article_translations', 'title')
                    ->where(function ($query) use ($id, $languages) {
                        $query->where('article_id', '!=', $id)
                            ->whereIn('language_code', $languages);
                    }),
            ],
            'translations.*.text' => 'required',
        ];
    }
}
