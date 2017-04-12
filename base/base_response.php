<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/25
 * Time: 17:53
 */
require_once("../model/Resourse.php");
header('Content-Type: application/json; charset=utf-8');
$responseResult = new Resourse();

function doReturn($result){
    $tResult = json_decode(json_encode($result),true);
    $tResult = array_filter($tResult);
    return json_encode($tResult);
}