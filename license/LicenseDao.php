<?php
require_once("License.php");
require_once("../model/Resourse.php");
require_once("../base/base_response.php");
require_once("../util/FOpenLog.php");
require_once("../db/BaseDbDao.php");

/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/19
 * Time: 12:28
 */
define("KEY_LICENSE", "MH25KXFYWR5CSJKN67VKP2H95FRBM2");

class LicenseDao extends BaseDbDao
{
    protected $tableName = "License";

    public function init()
    {
        parent::init();
        $this->createTable();
    }

    public function insert(License $tLicense)
    {
        //----
        $sql = "SELECT license FROM " . $this->tableName .
            " WHERE license= '$tLicense->licenseCode'";

        $result = mysqli_query($this->conn, $sql);

        /* determine number of rows result set */
        $row_cnt = $result->num_rows;

        FOpenLog::e("结果集：Result set has %d rows.\n", $row_cnt);

        /* close result set */
        $result->close();

        if ($row_cnt > 0) {
            return 0;
        }

        //----
        $sql = "INSERT INTO " . $this->tableName
            . " (license,corporationId,channel) VALUES ('$tLicense->licenseCode','$tLicense->corporationId','$tLicense->channel')";

//        if (!empty($tLicense->corporationId)) {
//            $sql = $sql . "";
//        }
//
        FOpenLog::e("sql ===>/n " . $sql);
        return $this->conn->query($sql);
    }

    public function valid(License $data)
    {
        //----
        $tResult = new Resourse();

//        $tCellPhone = base64_encode($data->cellPhone);
        $tCellPhone = $data->cellPhone;

        $row = $this->query($data);

        if ($row instanceof Resourse) {
            return $row;
        }

        //----------
        if (empty($row['cellphone'])) {
//            //手机号码没有被绑定
//            FOpenLog::e("手机号码没有被绑定");
//            $tResult->errCode = -2;
//            $tResult->errMsg = "手机号码没有被绑定";
            $tResult = $this->update($data);
        } else if (strcmp($row['cellphone'], $tCellPhone) == 0) {
            $tResult = new License();
            $tResult->updateQueryValue($row);

            $tResult = get_object_vars($tResult);
            //手机号码已经被绑定,匹配正确
            FOpenLog::e("手机号码已经被绑定,匹配正确");
            $tResult["errCode"] = 0;
            $tResult["errMsg"] = "手机号码已经被绑定,匹配正确";

//            $tResult->errCode = 0;
//            $tResult->errMsg = "手机号码已经被绑定,匹配正确";
        } else {
            //手机号码已经被绑定,但不匹配
            FOpenLog::e("手机号码已经被绑定,但不匹配");
            $tResult->errCode = -3;
            $tResult->errMsg = "手机号码已经被绑定,但不匹配";
        }

        return $tResult;
    }

    public function query(License $data)
    {
        $licenseParam = $data->licenseCode;
        $channelParam = $data->channel;

        $tResult = new Resourse();
        $sql = "SELECT * FROM " . $this->tableName .
            " WHERE license= '$licenseParam' and channel= '$channelParam'";

        $result = mysqli_query($this->conn, $sql);

        /* determine number of rows result set */
        $row_cnt = $result->num_rows;

        FOpenLog::e("结果集：Result set has %d rows.\n", $row_cnt);

        if ($row_cnt <= 0) {
            $tResult->errCode = -1;
            $tResult->errMsg = "注册码不存在";
            return $tResult;
        }

        //----------
        if ($row = mysqli_fetch_array($result)) {
            $tResult = $row;
        }

        /* close result set */
        $result->close();

        return $tResult;
    }

    public function update(License $data)
    {
//        $cellPhone = base64_decode($data->cellPhone, true);
        $cellPhone = $data->cellPhone;

        $sql = "update " . $this->tableName .
            " set cellphone = '$cellPhone' WHERE license= '$data->licenseCode'";

        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            $result = $this->query($data);
            if (!($result instanceof Resourse)) {
                $result = License::fromResult($result);
            }
        } else {
            $result = new Resourse();
            $result->errCode = -1;
            $result->errMsg = "绑定手机失败";
        }

        return $result;
    }

    protected function createTable()
    {
        $db_selected = mysqli_select_db($this->conn, $this->dbName);

        if (!$db_selected) {
            FOpenLog::e("\n选择.....失败 " . $this->dbName);
        } else {
            FOpenLog::e("\n选择" . $this->dbName);
        }

        // 使用 sql 创建数据表
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->tableName . " (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
corporationId INT(6) UNSIGNED,
license VARCHAR(30) NOT NULL UNIQUE,
channel VARCHAR(6) NOT NULL,
cellphone TEXT,
date TIMESTAMP)";

        //IF NOT EXISTS

        if ($this->conn->query($sql) === TRUE) {
            FOpenLog::e("Table " . $this->tableName . "created successfully");
        } else {
            FOpenLog::e("创建数据表错误: " . $this->conn->error);
        }

    }

    public function getList()
    {
        $tResult = new Resourse();
        $resultArray = [];

        $sql = "SELECT * FROM " . $this->tableName;

        $queryResult = mysqli_query($this->conn, $sql);

        /* determine number of rows result set */
        $row_cnt = $queryResult->num_rows;
        $tResult->count = $row_cnt;

        FOpenLog::e("查询序列号结果集：Result set has %d rows.\n", $row_cnt);

        if ($row_cnt <= 0) {
            return $tResult;
        }

        //----------
        while ($row = mysqli_fetch_array($queryResult)) {
            $item = new License();
            $item->updateQueryValue($row);
//            $item["key"] = $row["license"];
//            $item["corporationId"] = $row["corporationId"];
            array_push($resultArray, arrayFilterNull($item));
        }

        /* close result set */
        $queryResult->close();

        $tResult->res = array_filter($resultArray);

        return $tResult;
    }

    public function getInsertedList()
    {
        $tResult = new Resourse();
        $resultArray = [];

        $idList = join(",", $this->insertIds);

        $sql = "SELECT * FROM " . $this->tableName . " where id in ($idList)";

        $queryResult = mysqli_query($this->conn, $sql);

        /* determine number of rows result set */
        $row_cnt = $queryResult->num_rows;
        $tResult->count = $row_cnt;
        FOpenLog::e("查询序列号结果集：Result set has %d rows.\n", $row_cnt);

        if ($row_cnt <= 0) {
            return $tResult;
        }

        //----------
        while ($row = mysqli_fetch_array($queryResult)) {
            $item = new License();
            $item->updateQueryValue($row);
//            $item["key"] = $row["license"];
//            $item["corporationId"] = $row["corporationId"];
            array_push($resultArray, arrayFilterNull($item));
        }

        /* close result set */
        $queryResult->close();

        $tResult->res = array_filter($resultArray);

        return $tResult;
    }

    public function getListForCorporation($id)
    {
        $tResult = new Resourse();
        $resultArray = [];

        $sql = "SELECT * FROM " . $this->tableName . " Where corporationId='$id'";

        $queryResult = mysqli_query($this->conn, $sql);

        /* determine number of rows result set */
        $row_cnt = $queryResult->num_rows;

        FOpenLog::e("查询序列号结果集：Result set has %d rows.\n", $row_cnt);

        if ($row_cnt <= 0) {
            return $tResult;
        }

        //----------
        while ($row = mysqli_fetch_array($queryResult)) {
            $item = new License();
//            $item["id"] = $row["id"];
//            $item["key"] = $row["license"];
//            $item["corporationId"] = $row["corporationId"];
//            $item["isBind"] = empty($row["cellphone"]);
            $item->updateQueryValue($row);
            array_push($resultArray, arrayFilterNull($item));
        }

        /* close result set */
        $queryResult->close();

        $tResult->res = array_filter($resultArray);

        return $tResult;
    }

    public function queryAll()
    {
    }

    public function autoVerify(License $license)
    {
        $paramString = "";

        $patternLicense = '/.+￥(.+)￥.+/';
        if (preg_match($patternLicense, $license->licenseCode, $matches)) {

            if (count($matches) == 2) {
                $paramString = $matches[1];
            }
        }

        if (empty($paramString)) {
            $tResult = new Resourse();
            $tResult->errCode = -1;
            $tResult->errMsg = "验证激活码失败";
            return $tResult;
        }

        //解密参数
        $paramString = base64_decode($paramString);
        $tParamArray = json_decode($paramString, true);

        $license->licenseCode = $tParamArray["license"];
        //----
//      $license->channel = $tParamArray["channel"];
        if (strcmp($license->channel, $tParamArray["channel"]) != 0) {
            $tResult = new Resourse();
            $tResult->errCode = -1;
            $tResult->errMsg = "验证激活码失败";
            return $tResult;
        }

        //自动验证激活
        $tResult = $this->valid($license);

        //如果验证失败
        if ($tResult instanceof Resourse) {
            if ($tResult->errCode != 0) {
                return $this->autoVerifyOther($license);
            }
        } else {
            return $tResult;
        }
    }

    //自动激活其他注册码
    public function autoVerifyOther(License $license)
    {
        $tResult = new Resourse();
        $resultArray = [];

        //--查询没有被注册的该渠道的验证码
        $channelParam = $license->channel;
        $phoneParam = $license->cellPhone;
        $sql = "SELECT * FROM " . $this->tableName
            . " where channel='$channelParam' and (cellphone ='' or cellphone is null or cellphone='$phoneParam')";

        $queryResult = mysqli_query($this->conn, $sql);

        /* determine number of rows result set */
        $row_cnt = $queryResult->num_rows;

        FOpenLog::e("查询序列号结果集：Result set has %d rows.\n", $row_cnt);

        if ($row_cnt <= 0) {
            $tResult->errCode = -1;
            $tResult->errMsg = "绑定手机失败";
            return $tResult;
        }

        //----------
        if ($row = mysqli_fetch_array($queryResult)) {
            $tResult = $row;
        }

        $otherLicense = License::fromResult($tResult);

        $license->licenseCode = $otherLicense->licenseCode;

        /* close result set */
        $queryResult->close();

        return $this->valid($license);
    }

    public function checkSign(License $license)
    {
        if (!isset($license)) {
            return false;
        }

        if (empty($license->sign)) {
            return false;
        }

        $nowDate = date("Ymd");

        $cellPhone64 = base64_encode($license->cellPhone);
        $license64 = base64_encode($license->licenseCode);

        $sign = KEY_LICENSE . $nowDate . $cellPhone64 . $license64 . $license->channel;
        $sign = md5($sign);

        if (strcmp($license->sign, $sign) == 0) {
            return true;
        }

        return false;
    }

//protected String getSign(String userName, String license, String channel)
//{
//String result = "MH25KXFYWR5CSJKN67VKP2H95FRBM2";
//String date = DateTool.getYMD();
//String name = "";
//try
//{
//number = Base64.encodeToString(userName.getBytes("utf-8"), Base64.NO_WRAP);
//}
//
//catch
//(UnsupportedEncodingException e) {
//    e . printStackTrace();
//}
//
//            result = result + date + number + license + channel;
//            result = MD5 . md5(result);
//            return result;
//        }
}