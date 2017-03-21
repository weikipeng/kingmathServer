<?php

/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/21
 * Time: 10:55
 */
class FOpenLog
{
    public static function e($message){
        error_log($message);
    }
}