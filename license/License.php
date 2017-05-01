<?php

/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/19
 * Time: 11:35
 */
class License
{
    public $id;
    /* 序列号 */
    public $licenseCode;
    /* 手机号 */
    public $cellPhone;
    /* 签名 */
    public $sign;

    public $ipAddress;

    public $corporationId;

    public $isBind;

    public $channel;

    public static function fromResult($dbResult)
    {
        $result = new License();

        $result->licenseCode = $dbResult["license"];
        $result->cellPhone = $dbResult["cellphone"];
        $result->channel = $dbResult["channel"];

        return $result;
    }

    public function updateQueryValue($row)
    {
        $this->id = $row["id"];
        $this->licenseCode = $row["license"];
        $this->corporationId = $row["corporationId"];
        $this->isBind = !empty($row["cellphone"]);
    }


}