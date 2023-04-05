<?php

/*
 * PostgresSQL Database Class
 *
 *  @var string $server Database server.
 *  @var string $user Username.
 *  @var string $pass Password.
 *  @var string $db Database name.
 *  @var string $code Error code.
 *  @var string $error Error message.
 *  @var boolean $connect Connect database status.
 * */

class PostgresModel
{
    private $server;
    private $db;
    private $user;
    private $pass;
    private $code = "";
    public $error = "";
    private $connect = false;

    function __construct($server ,$db , $user, $pass){
        $this->server = $server;
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
    }

    function getConnect() {
        global $c;
        $this->code = '';
        $this->error = '';
        $connectionString = 'host='.$this->server.' dbname='.$this->db.' user='.$this->user.' password='.$this->pass.'';
        if (!$c = pg_connect($connectionString)) {
            $this->connect = false;
            $this->error = "Ошибка: Невозможно подключиться к PostgresSQL " . pg_last_error();
        }else{
            $this->connect = true;
        }
        return $this->connect;
    }

    function closeConnect() {
        global $c;
        pg_close($c);
        $this->connect = false;
    }

    function goResult($sql_in){
        global $c;
        if (!$this->connect) $this->GetConnect();
        if(!$result = pg_query($sql_in)) {
            $this->error = 'PostgresSQL error ['.pg_last_error().']';
            $this->closeConnect();
            return false;
        }else{
            $res = [];
            while( $row = pg_fetch_array($result ,null, PGSQL_ASSOC)){
                $res[] = ($row);
            }
            pg_free_result($result);
            $this->closeConnect();
            return $res;
        }
    }

    function goResultOnce($sql_in){
        global $c;
        if (!$this->connect) $this->GetConnect();
        if(!$result = pg_query($sql_in)) {
            $this->error = 'PostgresSQL error ['.pg_last_error($c).']';
            $this->closeConnect();
            return false;
        }else{
            $row = pg_fetch_array($result ,null, PGSQL_ASSOC);
            $res = $row;
            $this->closeConnect();
            return $res;
        }
    }

    function query($sql_in){
        global $c;
        if (!$this->connect) $this->GetConnect();
        if(!$result = pg_query($sql_in)) {
            $this->error = 'PostgresSQL error ['.pg_last_error($c).']';
            $this->closeConnect();
            return false;
        }else{
            return true;
        }
    }

}