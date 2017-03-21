<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/19
 * Time: 12:25
 */
require_once("../util/FOpenLog.php");
require_once("LicenseDao.php");
require_once("../math/KingRandom.php");
require_once("License.php");
$NUM_KEY = 4;

$tDbDao = new LicenseDao();
$tDbDao->init();

FOpenLog::e("......" . KingRandom::randKeyString($NUM_KEY));

$mLicense = new License();
$mLicense->licenseCode = KingRandom::randKeyString($NUM_KEY);
if ($tDbDao->insert($mLicense) > 0) {
    FOpenLog::e("插入数据成功");
} else {
    FOpenLog::e("插入数据失败");
}

$tDbDao->close();
?>