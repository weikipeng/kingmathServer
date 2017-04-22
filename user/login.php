<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/21
 * Time: 17:11
 */
require_once("../base/base_response.php");
require_once("UserDao.php");
require_once("User.php");
require_once("../model/Resourse.php");
require_once("../math/KingRandom.php");
require_once("../util/Se.php");

$tempUser = new User();
$tempUser->userName = $_POST["userName"];
$tempUser->password = $_POST["password"];
$tempUser->sign = $_POST["s"];
$tempUser->ipAddress = Se::getClientIP();


$userDao = new UserDao();
$userDao->init();

//-----
if (!$userDao->checkSign($tempUser)) {
    $responseResult->errCode = -1;
    $responseResult->errMsg = "登录失败";
    die(doReturn($responseResult));
}


$queryResult = $userDao->query($tempUser);

if ($queryResult instanceof Resourse && $queryResult->errCode != 0) {
    $queryResult->errMsg = "登录失败";
    $responseResult = $queryResult;
} else {
    $res = [];

    $res["userName"] = $queryResult["userName"];
    $res["key"] = $queryResult["authtication"];
    if (empty($res["key"])) {
        $res["key"] = KingRandom::randKeyString(64);
        $userDao->updateAuthorization($res["userName"], $res["key"]);
    }
//    $res["ip"] = $queryResult["ipAddress"];
    $res["ip"] = $tempUser->ipAddress;

    //---
//    $keyJson = [];
//    $keyJson["userName"] = $res["userName"];
//    $keyJson["Authorization"] = $res["key"];
//    $keyJson["ip"] = $res["ip"];
//    $res["key"] = base64_encode(json_encode($keyJson));

    //=----
//    $res["keyRaw"] = json_encode($keyJson);
//
//    $responseResult->res = array_filter($res);

    $responseResult->res = base64_encode(json_encode($res));
}


$userDao->close();
echo doReturn($responseResult);