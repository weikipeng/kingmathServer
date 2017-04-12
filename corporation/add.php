<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/26
 * Time: 21:55
 */
require_once("../base/base_response.php");
require_once("Corporation.php");
require_once("CorporationDao.php");

$tCorporation = new Corporation();
$tCorporation->name = $_POST["name"];
if (empty($_POST["name"])) {
    $responseResult->errCode = -1;
    $responseResult->errCode = "请输入公司名字";
    echo json_encode($responseResult);
    return;
}

$tCorporationDao = new CorporationDao();
$tCorporationDao->init();
echo doReturn($tCorporationDao->add($tCorporation->name));
$tCorporationDao->close();
