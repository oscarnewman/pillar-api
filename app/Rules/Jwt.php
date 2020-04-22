<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Validator;

class Jwt implements Rule
{

    private function decodeJwtIndex(string $jwt, int $index)
    {
        return json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $jwt)[$index]))));
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
        //
        try {
            $decoded_token = [
                $this->decodeJwtIndex($value, 0),
                $this->decodeJwtIndex($value, 1),
            ];
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a Base64 JWT token.';
    }
}
