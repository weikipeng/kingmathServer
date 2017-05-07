<?php

/**
 * Created by PhpStorm.
 * User: wiki
 * Date: 2017/3/26
 * Time: 17:24
 */
class Corporation
{
    public $id;
    public $name;
    public $channel;
    public $createDate;
    public $updateDate;


    public function updateBySqlResult($row){
        $this->id = $row["id"];
        $this->name = $row["name"];
        $this->channel = $row["channel"];
        $this->createDate = $row["createDate"];
        $this->updateDate = $row["updateDate"];
    }
}