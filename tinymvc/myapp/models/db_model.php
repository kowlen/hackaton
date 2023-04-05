<?php

require_once('configs/conf.php');
require_once('tinymvc/myapp/models/db/MysqlModel.php');

class db_model extends MysqlModel
{
    private $server = db_server;
    private $db = db_db;
    private $user =  db_user;
    private $pass = db_pass;

    function __construct(){
        parent::__construct($this->server, $this->db, $this->user, $this->pass);
    }

    function goResult($sql_in){
        if (!$result = parent::goResult($sql_in)){
            if($this->error){
                die('db_model' . $this->error);
            }
        }
        return $result;
    }

    function goResultOnce($sql_in){
        if (!$result = parent::goResultOnce($sql_in)){
            if($this->error){
                die('db_model' . $this->error);
            }
        }
        return $result;
    }

    function query($sql_in){
        if(!parent::query($sql_in)){
            if($this->error){
                die('db_model' . $this->error);
            }
        }
    }
}