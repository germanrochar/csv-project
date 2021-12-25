<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredContactFieldsMustBeProvided implements Rule
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
        $columns = json_decode($value, true);

        if (!is_array($columns)) {
            throw new \LogicException(sprintf('Rule [%s] was applied to a field that is not a json.', self::class));
        }

        return in_array('phone', $columns, true) && in_array('team_id', $columns, true);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Contact fields [phone] and [team_id] must be mapped.';
    }
}
