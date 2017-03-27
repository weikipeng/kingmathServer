<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/27
 * Time: 09:44
 */

require_once("../base/base_authorization_header.php");
require_once("Corporation.php");
require_once("CorporationDao.php");

$tCorporation = new Corporation();
$tCorporation->name = $_POST["name"];
$tCorporation->id = $_POST["id"];
if (empty($_POST["name"]) || empty("id")) {
    $responseResult->errCode = -1;
    $responseResult->errCode = "请求参数错误";
    echo json_encode($responseResult);
    die;
}

$tCorporationDao = new CorporationDao();
$tCorporationDao->init();
echo json_encode($tCorporationDao->update($tCorporation));
$tCorporationDao->close();
