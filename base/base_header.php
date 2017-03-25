<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/25
 * Time: 11:45
 */

$headers = apache_request_headers();
header('Content-Type: application/json; charset=utf-8');

//foreach ($headers as $header => $value) {
//    FOpenLog::e("$header: $value <br />\n");
//}
