<?php namespace Samubra\Train\Classes;

use Illuminate\Contracts\Validation\Rule;

class Phone implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/',$value);
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '电话号码格式错误。';
    }
}