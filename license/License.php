<?php

/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/19
 * Time: 11:35
 */
class License
{
    /* 序列号 */
    public $licenseCode = "111111";
    /* 手机号 */
    public $cellPhone = "18911001100";
    /* 签名 */
    public $sign;

    public static function fromResult($dbResult)
    {
        $result = new License();

        $result->licenseCode = $dbResult["license"];
        $result->cellPhone= $dbResult["cellphone"];

        return $result;
    }
}