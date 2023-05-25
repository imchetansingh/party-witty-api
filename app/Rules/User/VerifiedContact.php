<?php

namespace App\Rules\User;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;

class VerifiedContact implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::where('contact', $value)->first();
        if($user->status == '0'){
            $fail('The ' . $attribute . ' is not verified.');
        }
    }
}
