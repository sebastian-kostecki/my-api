<?php

namespace App\Rules;

use App\Lib\Connections\ArtificialIntelligence\OpenAI;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ModelRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $models = OpenAI::factory()->getModels();
        $models = collect($models)->map(function ($model) {
            return $model['name'];
        });

        if (!in_array($value, $models->toArray())) {
            $fail('The :attribute is not a valid model.');
        }
    }
}
