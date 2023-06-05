<?php

namespace App\Http\Requests;

use App\Rules\UniqueTitle;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $languages = config('languages');

        $languageCodes = $this->input('translations.*.language_code');
        $titleRule = new UniqueTitle;
        $titleRule->setData($languageCodes);
        return [
            'translations' => 'required|array|min:3',
            'translations.*.title' => [
                'required', 'max:255', $titleRule
            ],
            'translations.*.text' => 'required',
            'translations.*.language_code' => ['required', Rule::in($languages), 'distinct'],
        ];
    }
}
