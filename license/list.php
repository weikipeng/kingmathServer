<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/23
 * Time: 14:13
 */

require_once("../base/base_header.php");
require_once("LicenseDao.php");

$responseResult = new Resourse();

$number = $_POST["groupId"];
$tLicenseDao = new LicenseDao();
$tLicenseDao->init();
$tLicenseDao->getList();

$tLicenseDao->close();

//$headers = apache_request_headers();
//
//foreach ($headers as $header => $value) {
//    FOpenLog::e("$header: $value <br />\n");
//}

echo json_encode($responseResult);