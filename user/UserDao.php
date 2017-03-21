<?php

require_once("User.php");
require_once("../db/BaseDbDao.php");

/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/21
 * Time: 15:30
 */
class UserDao extends BaseDbDao
{
    private static $KEY_LICENSE = "MH25KXFYWR5CSJKN67VKP2H95FRBM2";
    private static $KEY_CELLPHONE = "CK4APBVXAS9WDW34H163TRJDT5PSJK";

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
        } else {
            FOpenLog::e("创建数据表错误: " . $this->conn->error);
        }
    }
}