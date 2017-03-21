<?php
require_once("../util/FOpenLog.php");
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/21
 * Time: 15:34
 */
class BaseDbDao
{
    protected $serverName = "localhost";
    protected $userName = "root";
    protected $password = "root";
    protected $dbName = "kingMath";
    protected $conn;

    public function init()
    {
        // 创建连接
        $this->conn = new mysqli($this->serverName, $this->userName, $this->password);
        // 检测连接
        if ($this->conn->connect_error) {
            die("连接失败: " . $this->conn->connect_error);
        } else {
            FOpenLog::e("连接成功");
        }

        // Make my_db the current database
        //选择数据库
        $db_selected = mysqli_select_db($this->conn, $this->dbName);
        if (!$db_selected) {
            FOpenLog::e("\n选择.....失败 " . $this->dbName);
            $this->initDataBase();
        } else {
            FOpenLog::e("\n选择" . $this->dbName);
        }
    }

    protected function initDataBase()
    {
        // 创建数据库
        $sql = "CREATE DATABASE " . $this->dbName;
        if ($this->conn->query($sql) === TRUE) {
            FOpenLog::e("数据库创建成功");
        } else {
            FOpenLog::e("Error creating database: " . $this->conn->error);
        }
        //选择数据库
        mysqli_select_db($this->dbName, $this->conn);
    }

    public function close()
    {
        $this->conn->close();
    }
}