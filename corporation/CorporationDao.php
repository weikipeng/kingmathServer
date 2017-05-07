<?php

require_once("../db/BaseDbDao.php");

/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/26
 * Time: 17:24
 */
class CorporationDao extends BaseDbDao
{
    protected $tableName = "Corporation";

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
name VARCHAR(30),
channel VARCHAR(10),
createDate TIMESTAMP,
updateDate TIMESTAMP)";

        //IF NOT EXISTS

        if ($this->conn->query($sql) === TRUE) {
            FOpenLog::e("Table " . $this->tableName . "created successfully");
        } else {
            FOpenLog::e("创建数据表错误: " . $this->conn->error);
        }

    }

    public function add($name)
    {
//----
        $tResult = new Resourse();
        if ($this->isExist($name)) {
            $tResult->errCode = -1;
            $tResult->errMsg = "已经存在，不能重复添加";
            return $tResult;
        }
        //----
        $sql = "INSERT IGNORE INTO " . $this->tableName
            . " (name) VALUES ('" . $name . "')";
        FOpenLog::e("sql ===>/n $sql");
        $sqlResult = $this->conn->query($sql);
        if ($sqlResult) {
            $sqlResult = $this->queryByName($name);

            if ($sqlResult) {
                $res = new Corporation();
                $res->updateBySqlResult($sqlResult);
                $res->channel = "b0" . "$res->id";

                $this->update($res);

                $tResult = $res;
            } else {
                $tResult->errCode = -1;
                $tResult->errMsg = "执行添加失败";
            }

        }
        return $tResult;
    }

    public function update(Corporation $corporation)
    {
        $sql = "update " . $this->tableName .
            " set name = '$corporation->name', channel='$corporation->channel' WHERE id= '$corporation->id'";
//        $sql = "update " . $this->tableName .
//            " set (name,channel) values ('$corporation->name','$corporation->channel') WHERE id= '$corporation->id'";

        FOpenLog::e("update sql ===>/n $sql");

        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            $result = new Resourse();
        } else {
            $result = new Resourse();
            $result->errCode = -1;
            $result->errMsg = "更新失败";
        }

        return $result;
    }

    protected function query($id)
    {
        $sql = "SELECT * FROM " . $this->tableName .
            " WHERE id= '$id'";

        $result = mysqli_query($this->conn, $sql);

        /* close result set */
        $result->close();

        return $result;
    }

    protected function queryByName($name)
    {
        $sql = "SELECT * FROM " . $this->tableName .
            " WHERE name= '$name'";

        $result = mysqli_query($this->conn, $sql);

        $row = mysqli_fetch_array($result);

        /* close result set */
        $result->close();

        return $row;
    }

    protected function isExist($name)
    {
        $sql = "SELECT * FROM " . $this->tableName .
            " WHERE name= '$name'";

        $result = mysqli_query($this->conn, $sql);

        /* determine number of rows result set */
        $row_cnt = $result->num_rows;

        FOpenLog::e("结果集：Result set has %d rows.\n", $row_cnt);

        /* close result set */
        $result->close();

        if ($row_cnt > 0) {
            return true;
        }

        return false;
    }

    public function getList()
    {
        $returnRes = new Resourse();
        $sql = "SELECT * FROM " . $this->tableName;

        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            //----------
            $resultArray = [];
            while ($row = mysqli_fetch_array($result)) {
                $item = new Corporation();
                $item->updateBySqlResult($row);
                array_push($resultArray, $item);
            }

            $returnRes->res = $resultArray;
        } else {
            $returnRes->errCode = -1;
            $returnRes->errMsg = "获取客户列表失败";
        }

        /* close result set */
        $result->close();

        return $returnRes;
    }
}