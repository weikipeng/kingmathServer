<?php

require_once("User.php");
require_once("../db/BaseDbDao.php");

/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/21
 * Time: 15:30
 */
define("KEY_USER_DAO", "CK4APBVXAS9WDW34H163TRJDT5PSJK");
class UserDao extends BaseDbDao
{
//    static $KEY_LICENSE = "MH25KXFYWR5CSJKN67VKP2H95FRBM2";
//    const KEY_CELLPHONE = "CK4APBVXAS9WDW34H163TRJDT5PSJK";

    protected $tableName = "Users";
    protected $conn;


    public function init()
    {
        parent::init();
        $this->createTable();
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
userName VARCHAR(30) NOT NULL UNIQUE,
password TEXT,
authtication TEXT,
ipAddress VARCHAR(30),
createDate TIMESTAMP,
updateDate TIMESTAMP)";

        //IF NOT EXISTS

        if ($this->conn->query($sql) === TRUE) {
            FOpenLog::e("Table " . $this->tableName . "created successfully");
            $this->initAdmin();
        } else {
            FOpenLog::e("创建数据表错误: " . $this->conn->error);
        }
    }

    protected function initAdmin()
    {
        $password = md5('admin');
        //----
        $sql = "INSERT IGNORE INTO " . $this->tableName
            . " (userName,password,createDate) VALUES ('admin','$password',now())";
        FOpenLog::e("sql ===>/n $sql");
        return $this->conn->query($sql);
    }

    public function query(User $user)
    {
        $tResult = new Resourse();

        $tpassword = md5($user->password);

        $sql = "SELECT * FROM " . $this->tableName .
            " WHERE userName= '$user->userName' and password = '$tpassword'";

        $result = mysqli_query($this->conn, $sql);

        /* determine number of rows result set */
        $row_cnt = $result->num_rows;

        FOpenLog::e("结果集：Result set has , $row_cnt rows.\n");

        if ($row_cnt <= 0) {
            $tResult->errCode = -1;
            $tResult->errMsg = "用户不存在";
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

    public function updateAuthorization($userName, $key)
    {
        $tResult = new Resourse();

        $sql = "update " . $this->tableName .
            " set authtication= '$key' where userName = '$userName'";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            $tResult->errCode = -1;
            $tResult->errMsg = "更新authtication失败";
            return $tResult;
        }

        return $tResult;
    }

    public function verify($data)
    {
        if (empty($data)) {
            return false;
        }

        $tData = json_decode(base64_decode($data), true);
        if (empty($tData["key"])) {
            return false;
        }

        $tData = $tData["key"];

        $sql = "SELECT * FROM " . $this->tableName .
            " WHERE authtication= '$tData'";

        $result = mysqli_query($this->conn, $sql);

        $row_cnt = $result->num_rows;

        if ($row_cnt <= 0) {
            return false;
        }

        $result->close();

        return true;
    }

    public function checkSign(User $userPostData)
    {
        if (!isset($userPostData)) {
            return false;
        }

        if (empty($userPostData->sign)) {
            return false;
        }

        $nowDate = date("Ymd");
        $baseUserName = base64_encode($userPostData->userName);
        $sign = KEY_USER_DAO . $nowDate . $baseUserName . $userPostData->password;
        $sign = md5($sign);

        if (strcmp($userPostData->sign, $sign) == 0) {
            return true;
        }

        return false;
    }

}