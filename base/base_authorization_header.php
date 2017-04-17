<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/25
 * Time: 11:45
 */
require_once("base_response.php");
require_once("../util/FOpenLog.php");
require_once("../user/UserDao.php");

$headers = apache_request_headers();
if (empty($headers["Authorization"]) || !isset($headers["Authorization"])) {
    $responseResult->errCode = -1;
    $responseResult->errMsg = "没有登录";
    die(doReturn($responseResult));

}

//-检查用户身份

//echo json_encode($nParam);

$tUserDao = new UserDao();
$tUserDao->init();
if (!$tUserDao->verify($headers["Authorization"])) {
    $responseResult->errCode = -2;
    $responseResult->errMsg = "用户无效";
    $tUserDao->close();
    die(doReturn($responseResult));
}
$tUserDao->close();


//foreach ($headers as $header => $value) {
//    FOpenLog::e("$header: $value <br />\n");
//}
