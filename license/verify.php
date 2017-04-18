<?php
require_once("../base/base_response.php");
require_once("LicenseDao.php");
require_once("../util/Se.php");
require_once("../util/NumberUtil.php");
$tempLicense = new License();
$tempLicense->cellPhone = $_POST["c"];
$tempLicense->licenseCode = $_POST["l"];
$tempLicense->sign = $_POST["s"];
$tempLicense->ipAddress = Se::getClientIP();

//echo json_encode($tempLicense);

$result = new Resourse();
if (!NumberUtil::isIMSI($tempLicense->cellPhone)) {
    $result->errCode = -1;
    $result->errMsg = "手机身份不正确";
    echo doReturn($result);
    return;
}

//---
$tLicenseDao = new LicenseDao();
$tLicenseDao->init();
$result = $tLicenseDao->valid($tempLicense);

$tLicenseDao->close();


//---
echo doReturn($result);
?>