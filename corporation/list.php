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

$tCorporationDao = new CorporationDao();
$tCorporationDao->init();
$responseResult = $tCorporationDao->getList();
$tCorporationDao->close();
echo doReturn($responseResult);