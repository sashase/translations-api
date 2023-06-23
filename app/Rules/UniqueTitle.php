<?php

namespace App\Rules;

use App\Models\ArticleTranslation;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class UniqueTitle implements ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $translationNumber = explode('.', $attribute)[1];
        $articleExists = ArticleTranslation::all()->where('language_code', $this->data[$translationNumber])->where('title', $value)->all();
        if (! empty($articleExists)) {
            $fail('The title for this language already exists');
        }
    }
}
