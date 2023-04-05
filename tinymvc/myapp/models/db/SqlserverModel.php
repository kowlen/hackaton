<?php

/*
 * SQL Server Database Class
 *
 *  @var string $instance Instance server.
 *  @var string $server Database instance.
 *  @var string $user Username.
 *  @var string $pass Password.
 *  @var string $db Database name.
 *  @var string $code Error code.
 *  @var string $error Error message.
 *  @var boolean $connect Connect database status.
 * */

class SqlserverModel
{
    private $instance;
    private $server;
    private $db;
    private $user;
    private $pass;
    private $code = "";
    public $error = "";
    private $connect = false;

    function __construct($instance, $server ,$db , $user, $pass){
        $this->instance = $instance;
        $this->server = $server;
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
    }

    function getConnect() {
        global $c;
        $this->code = '';
        $this->error = '';
        $serverName = $this->server."\\".$this->instance;
        $connectionInfo = array( "Database"=> $this->db, "UID"=> $this->user, "PWD"=> $this->pass, "CharacterSet" => "UTF-8");
        if (!$c =  sqlsrv_connect( $serverName, $connectionInfo )) {
            $this->connect = false;
            $this->error = sqlsrv_errors()[0]["message"];
        }else{
            $this->connect = true;
        }
        return $this->connect;
    }

    function closeConnect() {
        global $c;
        sqlsrv_close($c);
        $this->connect = false;
    }

    function goResult($sql_in){
        global $c;
        if (!$this->connect) $this->GetConnect();
        if(!$result = sqlsrv_query($c, $sql_in)) {
            $this->error = sqlsrv_errors()[0]["message"];
            $this->closeConnect();
            return false;
        }else{
            $res = [];
            while( $row = sqlsrv_fetch_array($result ,SQLSRV_FETCH_ASSOC)){
                $res[] = ($row);
            }
            sqlsrv_free_stmt($result);
            $this->closeConnect();
            return $res;
        }
    }

    function goResultOnce($sql_in){
        global $c;
        if (!$this->connect) $this->GetConnect();
        if(!$result = sqlsrv_query($c, $sql_in)) {
            $this->error = sqlsrv_errors()[0]["message"];
            $this->closeConnect();
            return false;
        }else{
            $row = sqlsrv_fetch_array($result ,SQLSRV_FETCH_ASSOC);
            sqlsrv_free_stmt($result);
            $this->closeConnect();
            $res = $row ? $row : [];
            return $res;
        }
    }

    function query($sql_in){
        global $c;
        if (!$this->connect) $this->GetConnect();
        if(!$result = sqlsrv_query($c, $sql_in)) {
            $this->error = sqlsrv_errors()[0]["message"];
            $this->closeConnect();
            return false;
        }else{
            sqlsrv_commit( $c );
            $this->closeConnect();
            return true;
        }
    }

}