<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/19
 * Time: 12:25
 */
require_once("../base/base_header.php");
require_once("../util/FOpenLog.php");
require_once("LicenseDao.php");
require_once("../math/KingRandom.php");
require_once("License.php");
require_once("../user/UserDao.php");
$NUM_KEY = 4;

$responseResult = new Resourse();

if (empty($headers["Authorization"]) || !isset($headers["Authorization"])) {
    $responseResult->errCode = -1;
    $responseResult->errMsg = "没有登录";
    echo json_encode($responseResult);
    return;
}

$nParam = [];
$nParam["corporation"] = $_POST["c"];
$nParam["num"] = $_POST["num"];
$nParam["sign"] = $_POST["sign"];
$nParam["Authorization"] = $headers["Authorization"];

//-检查参数
if ($nParam["num"] <= 0) {
    $responseResult->errCode = -1;
    $responseResult->errMsg = "请输入正确的数字";
    echo json_encode($responseResult);
    return;
}

//-检查参数
if (empty($nParam["corporation"])) {
    $responseResult->errCode = -1;
    $responseResult->errMsg = "请输入公司名字";
    echo json_encode($responseResult);
    return;
}


//-检查用户身份

//echo json_encode($nParam);

$tUserDao = new UserDao();
$tUserDao->init();
if (!$tUserDao->verify($nParam["Authorization"])) {
    $responseResult->errCode = -2;
    $responseResult->errMsg = "用户无效";
    echo json_encode($responseResult);
    $tUserDao->close();
    return;
}
$tUserDao->close();


$tDbDao = new LicenseDao();
$tDbDao->init();

FOpenLog::e("......" . KingRandom::randKeyString($NUM_KEY));

for ($x = 0; $x <= $nParam["num"]; $x++) {
    $mLicense = new License();
    $mLicense->licenseCode = KingRandom::randKeyString($NUM_KEY);
    if ($tDbDao->insert($mLicense) > 0) {
        FOpenLog::e("插入数据成功");
    } else {
        FOpenLog::e("插入数据失败");
    }
}

//$createArray = $tDbDao->queryAll();

$responseResult = $tDbDao->getList();

$tDbDao->close();

echo json_encode($responseResult)
?>