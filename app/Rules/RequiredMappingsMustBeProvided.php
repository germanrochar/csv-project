<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredMappingsMustBeProvided implements Rule
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

        return in_array('phone', array_values($mappings), true);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Contact field [phone] must be mapped.';
    }
}
