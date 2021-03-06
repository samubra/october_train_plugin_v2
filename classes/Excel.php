<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-12
 * Time: 下午1:59
 */

namespace Samubra\Train\Classes;


class Excel
{
    protected static $instance;
    protected $excel;
    private function __construct()
    {
        \App::register('\Maatwebsite\Excel\ExcelServiceProvider');
        $facade = \Illuminate\Foundation\AliasLoader::getInstance();
        $facade->alias('Excel', '\Maatwebsite\Excel\Facades\Excel');
        $this->excel = \App::make('excel');
    }
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            return static::$instance = new static();
        }
        return static::$instance;
    }

    public static function excel()
    {
        return self::getInstance()->excel;
    }
}