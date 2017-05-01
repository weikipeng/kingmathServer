<?php
require_once("../base/base_response.php");
require_once("LicenseDao.php");
require_once("../util/Se.php");
require_once("../util/NumberUtil.php");

$tempLicense = new License();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // The request is using the POST method

    $tempLicense->cellPhone = $_POST["c"];
    $tempLicense->licenseCode = $_POST["l"];
    $tempLicense->sign = $_POST["s"];
    $tempLicense->channel = $_POST["channel"];
    $tempLicense->ipAddress = Se::getClientIP();

//    $tempLicense->handleLicencePattern();
    $licenseDao = new LicenseDao();

    //-----
    if (!$licenseDao->checkSign($tempLicense)) {
        $responseResult->errCode = -1;
        $responseResult->errMsg = "激活失败";
        die(doReturn($responseResult));
    }

    $licenseDao->init();

    $responseResult = $licenseDao->autoVerify($tempLicense);
    echo doReturn($responseResult);
}


//$handleString = "【来自 好未来 激活金算师】，复制这条信息￥2017-04-22 15:33:02￥后打开👉金算师👈";
//
//$patternLicense = '/.+￥(.+)￥.+/';
//$tLicenseString = preg_match($patternLicense, $handleString, $matches);
//
//$testResult=[];
//$testResult["key"] = "key";
//$testResult["result"] = $matches;
//
//echo doReturn($testResult);


//---------
//$mode = '/a=(\d+)b=(\d+)c=(\d+)/';
//
//$str='**a=4b=98c=56**';
//
//$res=preg_match($mode,$str,$match);
//
//echo json_encode($match);


//$tempLicense->licenseCode = $tempLicense->licenseCode.substr()
