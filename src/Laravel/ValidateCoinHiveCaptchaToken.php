<?php

namespace KDuma\CoinHive\Laravel;

use Illuminate\Contracts\Validation\Rule;
use KDuma\CoinHive\CoinHiveApi;

class ValidateCoinHiveCaptchaToken implements Rule
{
    /**
     * @var null
     */
    private $required_hashes;

    /**
     * Create a new rule instance.
     *
     * @param null $required_hashes
     */
    public function __construct($required_hashes = null)
    {
        $this->required_hashes = $required_hashes ?: config('services.coinhive.default_hashes');
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
        return resolve(CoinHiveApi::class)->verifyToken($value, $this->required_hashes)['success'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('coinhive::messages.invalid_captcha');
    }
}
