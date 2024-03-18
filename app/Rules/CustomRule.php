<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CustomRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Implement your validation logic here
        return true; // Change this based on your logic
    }

    public function message()
    {
        return 'Custom validation failed.';
    }
}
