<?php
require_once("License.php");
require_once("../model/Resourse.php");
require_once("../util/FOpenLog.php");
require_once("../db/BaseDbDao.php");

/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/19
 * Time: 12:28
 */
class LicenseDao extends BaseDbDao
{
    private static $KEY_LICENSE = "MH25KXFYWR5CSJKN67VKP2H95FRBM2";
    private static $KEY_CELLPHONE = "CK4APBVXAS9WDW34H163TRJDT5PSJK";
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
        $sql = "INSERT IGNORE INTO " . $this->tableName
            . " (license) VALUES ('" . $tLicense->licenseCode . "')";
        FOpenLog::e("sql ===>/n $sql");
        return $this->conn->query($sql);
    }

    public function valid(License $data)
    {
        //----
        $tResult = new Resourse();

//        $tCellPhone = base64_encode($data->cellPhone);
        $tCellPhone = $data->cellPhone;

        $row = $this->query($data->licenseCode);

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
            //手机号码已经被绑定,匹配正确
            FOpenLog::e("手机号码已经被绑定,匹配正确");
            $tResult->errCode = 0;
            $tResult->errMsg = "手机号码已经被绑定,匹配正确";
        } else {
            //手机号码已经被绑定,但不匹配
            FOpenLog::e("手机号码已经被绑定,但不匹配");
            $tResult->errCode = -3;
            $tResult->errMsg = "手机号码已经被绑定,但不匹配";
        }

        return $tResult;
    }

    public function query($licenseCode)
    {
        $tResult = new Resourse();
        $sql = "SELECT * FROM " . $this->tableName .
            " WHERE license= '$licenseCode'";

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
            $result = $this->query($data->licenseCode);
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
cellphone VARCHAR(30),
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

        FOpenLog::e("查询序列号结果集：Result set has %d rows.\n", $row_cnt);

        if ($row_cnt <= 0) {
            return $tResult;
        }

        //----------
        while ($row = mysqli_fetch_array($queryResult)) {
            $item = [];
            $item["key"] = $row["license"];
            $item["corporationId"] = $row["corporationId"];
            array_push($resultArray, $item);
        }

        /* close result set */
        $queryResult->close();

        $tResult->res = array_filter($resultArray);

        return $tResult;
    }

    public function queryAll()
    {
    }
}