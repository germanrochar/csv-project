<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use LogicException;

class MappingValuesCannotBeEmpty implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $mappings = json_decode($value, true);

        return count(array_filter($mappings)) === count($mappings);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'An empty item was found in encoded json.';
    }
}
