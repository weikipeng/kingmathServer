<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/23
 * Time: 14:13
 */

require_once("../base/base_authorization_header.php");
require_once("LicenseDao.php");

$number = $_POST["corporationId"];
$tLicenseDao = new LicenseDao();
$tLicenseDao->init();
$responseResult = $tLicenseDao->getList();

$tLicenseDao->close();

echo json_encode($responseResult);