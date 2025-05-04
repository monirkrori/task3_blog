<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class FutureDateRule implements Rule
{

   public function passes($attribute, $value)
   {
       return ($value && strtotime($value) >= time());
   }

    public function message(): string
    {
     return 'Future date is required';
    }
}

