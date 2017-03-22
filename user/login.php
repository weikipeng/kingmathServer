<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/21
 * Time: 17:11
 */
require_once("UserDao.php");
require_once("User.php");
require_once("../model/Resourse.php");

header('Content-Type: application/json; charset=utf-8');
$result = new Resourse();

$tempUser = new User();
$tempUser->userName = $_POST["userName"];
$tempUser->password = $_POST["password"];
$tempUser->sign = $_POST["s"];

$result = $tempUser;

$userDao = new UserDao();
$userDao->init();
$result = $userDao->query($tempUser);

if ($result->errCode != 0) {
    $result->errMsg = "登录失败";
}

$userDao->close();
echo json_encode($result);