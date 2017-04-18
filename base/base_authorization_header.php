<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/25
 * Time: 11:45
 */
require_once("base_response.php");
require_once("../util/FOpenLog.php");
require_once("../user/UserDao.php");

//if( !function_exists('apache_request_headers') ) {
/////
//    function apache_request_headers() {
//        $arh = array();
//        $rx_http = '/\AHTTP_/';
//        foreach($_SERVER as $key => $val) {
//            if( preg_match($rx_http, $key) ) {
//                $arh_key = preg_replace($rx_http, '', $key);
//                $rx_matches = array();
//                // do some nasty string manipulations to restore the original letter case
//                // this should work in most cases
//                $rx_matches = explode('_', $arh_key);
//                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
//                    foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
//                    $arh_key = implode('-', $rx_matches);
//                }
//                $arh[$arh_key] = $val;
//            }
//        }
//        return( $arh );
//    }
/////
//}

//$headers = apache_request_headers();
$headers = getallheaders();

if (empty($headers["Authorization"]) || !isset($headers["Authorization"])) {
    $responseResult->errCode = -1;
    $responseResult->errMsg = "没有登录";
    die(doReturn($responseResult));
}

//-检查用户身份

//echo json_encode($nParam);

$tUserDao = new UserDao();
$tUserDao->init();
if (!$tUserDao->verify($headers["Authorization"])) {
    $responseResult->errCode = -2;
    $responseResult->errMsg = "用户无效";
    $tUserDao->close();
    die(doReturn($responseResult));
}
$tUserDao->close();


//foreach ($headers as $header => $value) {
//    FOpenLog::e("$header: $value <br />\n");
//}
