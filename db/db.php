<?php
/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/19
 * Time: 12:01
 */
require_once("../util/FOpenLog.php");
$servername = "localhost";
$username = "root";
$password = "root";
$dbName = "kingMath";
$tableName = "kingMath";
// 创建连接
$conn = new mysqli($servername, $username, $password);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 创建数据库
$sql = "CREATE DATABASE " . $dbName;
if ($conn->query($sql) === TRUE) {
    FOpenLog::e("数据库创建成功");
} else {
    FOpenLog::e("Error creating database: " . $conn->error);
}


//---
// 使用 sql 创建数据表
$sql = "CREATE TABLE " . $tableName . " (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
license VARCHAR(30) NOT NULL,
cellphone VARCHAR(30) NOT NULL,
date TIMESTAMP,
)";

if ($conn->query($sql) === TRUE) {
    FOpenLog::e("Table MyGuests created successfully");
} else {
    FOpenLog::e("创建数据表错误: " . $conn->error);
}

$conn->close();
?>