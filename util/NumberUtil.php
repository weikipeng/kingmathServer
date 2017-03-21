<?php

/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/19
 * Time: 17:31
 */
class NumberUtil
{

    /**
     * 验证手机号是否正确
     * @author honfei
     * @param number $mobile
     *
     * 移动：134、135、136、137、138、139、150、151、152、157、158、159、182、183、184、187、188、178(4G)、147(上网卡)；
     *
     * 联通：130、131、132、155、156、185、186、176(4G)、145(上网卡)；
     *
     * 电信：133、153、180、181、189 、177(4G)；
     *
     * 卫星通信：1349
     *
     * 虚拟运营商：170
     */
    public static function isMobile($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }

    public static function isIMSI($text)
    {
        if (empty($text)) {
            return false;
        }

        return preg_match('#\S{13,}#', $text) ? true : false;
    }
}