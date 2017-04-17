<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/27
 * Time: 09:46
 */
require_once("../base/base_authorization_header.php");
require_once("Corporation.php");
require_once("CorporationDao.php");
require_once("../license/LicenseDao.php");
require_once("../license/License.php");

$id = $_POST["id"];
if (empty($id)) {
    $responseResult->errCode = -1;
    $responseResult->errMsg = "请输入公司id";
    die(doReturn($responseResult));
}


$tLicenseDao = new LicenseDao();
$tLicenseDao->init();
$responseResult = $tLicenseDao->getListForCorporation($id);
$tLicenseDao->close();

//$tCorporationDao = new CorporationDao();
//$tCorporationDao->init();
//$responseResult = $tCorporationDao->getList();
//$tCorporationDao->close();
echo doReturn($responseResult);