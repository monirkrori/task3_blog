<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class KeywordsRule implements Rule
{
    protected $maxKeywords;
    protected $errorType;

    public function __construct(int $maxKeywords = 5)
    {
        $this->maxKeywords = $maxKeywords;
    }

    public function passes($attribute, $value)
    {
        if (is_array($value)) {
           $this->errorType = 'not-array';
        }

        elseif (count($value) <= $this->maxKeywords)
        {
            $this->errorType = 'max-keywords';
            return false;
        }

        elseif (count($value) !== count(array_unique($value)))
        {
            $this->errorType = 'duplicate-keywords';
            return false;
        }

        return is_array($value) && count($value) <= $this->maxKeywords;

    }

    public function message()
    {
        return match ($this->errorType) {
            'not-array' => 'keywords must be array',
            'max-tags' => "max keywords must be {$this->maxKeywords}",
            'duplicate-tags' => 'keywords must be unique'
        };
    }
}
