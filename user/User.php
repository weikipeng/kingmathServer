<?php

/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/21
 * Time: 15:30
 */
class User
{
    public $userName;
    /**密码*/
    public $password;
    /**密码摘要*/
    public $passwordDigest;

    public $role;

    public $authtication;

    public $ipAddress;

    public $createDate;

    public $updateDate;

    public $sign;
}