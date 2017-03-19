<?php
require_once("License.php");
require_once("../model/Resourse.php");

/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/19
 * Time: 12:28
 */
class LicenseDao
{
    protected $serverName = "localhost";
    protected $userName = "root";
    protected $password = "root";
    protected $dbName = "kingMath";
    protected $tableName = "License";
    protected $conn;

    public function init()
    {
        // 创建连接
        $this->conn = new mysqli($this->serverName, $this->userName, $this->password);
        // 检测连接
        if ($this->conn->connect_error) {
            die("连接失败: " . $this->conn->connect_error);
        } else {
            echo "连接成功";
        }

        // Make my_db the current database
        //选择数据库
        $db_selected = mysqli_select_db($this->conn, $this->dbName);
        if (!$db_selected) {
            echo "\n选择.....失败 " . $this->dbName;
            $this->initDataBase();
        } else {
            echo "\n选择" . $this->dbName;
        }

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

        printf("结果集：Result set has %d rows.\n", $row_cnt);

        /* close result set */
        $result->close();

        if ($row_cnt > 0) {
            return 0;
        }

        //----
        $sql = "INSERT IGNORE INTO " . $this->tableName
            . " (license) VALUES ('" . $tLicense->licenseCode . "')";
        echo "sql ===>/n $sql";
        return $this->conn->query($sql);
    }

    public function valid(License $data)
    {//----
        $tResult = new Resourse();
        $sql = "SELECT license FROM " . $this->tableName .
            " WHERE license= '$data->licenseCode'";

        $result = mysqli_query($this->conn, $sql);

        /* determine number of rows result set */
        $row_cnt = $result->num_rows;

        printf("结果集：Result set has %d rows.\n", $row_cnt);

        if ($row_cnt <= 0) {
            $tResult->errCode = -1;
            $tResult->errMsg = "注册码不存在";
            return $tResult;
        }
        $tCellPhone = base64_encode($data->cellPhone);

        //----------
        if ($row = mysqli_fetch_array($result)) {
            if (empty($row['cellphone'])) {
                //手机号码没有被绑定
                printf("手机号码没有被绑定");
                $tResult->errCode = -2;
                $tResult->errMsg = "手机号码没有被绑定";
            } else if (strcmp($row['cellphone'], $tCellPhone)) {
                //手机号码已经被绑定,匹配正确
                printf("手机号码已经被绑定,匹配正确");
                $tResult->errCode = 0;
                $tResult->errMsg = "手机号码已经被绑定,匹配正确";
            } else {
                //手机号码已经被绑定,但不匹配
                printf("手机号码已经被绑定,但不匹配");
                $tResult->errCode = -3;
                $tResult->errMsg = "手机号码已经被绑定,但不匹配";
            }
        }

        /* close result set */
        $result->close();

        return $tResult;
    }

    public function query($licenseCode)
    {
        $tResult = new Resourse();
        $sql = "SELECT license FROM " . $this->tableName .
            " WHERE license= '$licenseCode'";

        $result = mysqli_query($this->conn, $sql);

        /* determine number of rows result set */
        $row_cnt = $result->num_rows;

        printf("结果集：Result set has %d rows.\n", $row_cnt);

        if ($row_cnt <= 0) {
            $tResult->errCode = -1;
            $tResult->errMsg = "注册码不存在";
            return $tResult;
        }

        $tCellPhone = base64_encode($data->cellPhone);

        //----------
        if ($row = mysqli_fetch_array($result)) {
            $tResult = $row;
        }

        /* close result set */
        $result->close();

        return $tResult;
    }

    private function connect()
    {
        // 创建连接
        $this->conn = new mysqli($this->serverName, $this->userName, $this->password);
        // 检测连接
        if ($this->conn->connect_error) {
            die("连接失败: " . $this->conn->connect_error);
        }
    }

    protected function initDataBase()
    {
        // 创建数据库
        $sql = "CREATE DATABASE " . $this->dbName;
        if ($this->conn->query($sql) === TRUE) {
            echo "数据库创建成功";
        } else {
            echo "Error creating database: " . $this->conn->error;
        }
        //选择数据库
        mysqli_select_db($this->dbName, $this->conn);
    }

    public function close()
    {
        $this->conn->close();
    }

    private function createTable()
    {
        $db_selected = mysqli_select_db($this->conn, $this->dbName);

        if (!$db_selected) {
            echo "\n选择.....失败 " . $this->dbName;
        } else {
            echo "\n选择" . $this->dbName;
        }

        // 使用 sql 创建数据表
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->tableName . " (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
license VARCHAR(30) NOT NULL UNIQUE,
cellphone VARCHAR(30),
date TIMESTAMP)";

        //IF NOT EXISTS

        if ($this->conn->query($sql) === TRUE) {
            echo "Table " . $this->tableName . "created successfully";
        } else {
            echo "创建数据表错误: " . $this->conn->error;
        }

    }
}