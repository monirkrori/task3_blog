<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class SlugRule implements Rule
{

    public function passes($attribute, $value)
    {
        return (preg_match('/^[a-z0-9-]+$/', $value));
    }

    public function message(){
        return 'The slug field must contain only letters, numbers, and underscores.';
    }
}
