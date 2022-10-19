<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use LogicException;

class EncodedJsonValuesMustBeStrings implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $array = json_decode($value, true);

        if (!is_array($array)) {
            throw new LogicException(sprintf('Rule [%s] was applied to a field that is not a json.', self::class));
        }

        $stringItems = array_filter($array, static function ($item) {
            return is_string($item);
        });

        return count($stringItems) === count($array);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A non-string item was found in encoded json.';
    }
}
