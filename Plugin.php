<?php namespace Samubra\Train;

use System\Classes\PluginBase;
use Illuminate\Support\Facades\Validator;
use App;
use Illuminate\Foundation\AliasLoader;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        Validator::extend('identity', function($attribute, $value, $parameters, $validator) {
            return preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', $value);
        });
        Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/',$value);
        });
    }
}
