<?php

/*
 * Oracle Database Class
 *
 *  @var string $user Username.
 *  @var string $pass Password.
 *  @var string $db Database name.
 *  @var string $code Error code.
 *  @var string $error Error message.
 *  @var boolean $connect Connect database status.
 * */

class OracleModel
{
    private $user;
    private $pass;
    private $db;
    private $code = "";
    public $error = "";
    private $connect = false;

    function __construct($db, $user, $pass){
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
    }

    function getConnect() {
        global $c;
        $this->code = '';
        $this->error = '';
        if (!@$c = OCILogon($this->user, $this->pass, $this->db, 'AL32UTF8')) {
            $err = OCIError();
            $this->connect = false;
            $this->error = "Oracle Connect Error [".$err['message']."]";
        }else{
            $this->connect = true;
        }
        return $this->connect;
    }

    function closeConnect() {
        global $c, $s;
        oci_close($c);
        $this->connect = false;
    }

    function goResult($sql_in, $par = []){
        global $c;
        if (!$this->connect) $this->getConnect();
        $s = OCI_Parse($c, $sql_in);
        if (count($par)>0 && $par){
            foreach ($par as $key=>$val) {
                oci_bind_by_name($s, $key, $par[$key]);
            }
        }
        if (!OCI_Execute($s, OCI_DEFAULT)) {
            $e = oci_error($s);
            $this->error = $e['message'];
        }
        $out = Array();
        $i = 0;
        while ($res = oci_fetch_array($s, OCI_ASSOC+OCI_RETURN_NULLS)) {
            foreach ($res as $key=>$item) {
                $out[$i][$key] = ($item !== null ? $item : "");
            }
            $i++;
        }
        $this->closeConnect();
        return $out;
    }

    function goResultOnce($sql_in, $par = []){
        global $c;
        if (!$this->connect) $this->getConnect();
        $s = OCI_Parse($c, $sql_in);
        if (count($par)>0 && $par){
            foreach ($par as $key=>$val) {
                oci_bind_by_name($s, $key, $par[$key]);
            }
        }
        if (!OCI_Execute($s, OCI_DEFAULT)) {
            $e = oci_error($s);
            $this->error = "Oracle Error [".$e['message']."]";
        }
        $out = Array();
        if ($res = oci_fetch_array($s, OCI_ASSOC+OCI_RETURN_NULLS)){
            foreach ($res as $key=>$item) {
                $out[$key] = ($item !== null ? $item : "");
            }
        }
        $this->closeConnect();
        return $out;
    }

    function query($sql_in, $par = []){
        global $c;
        if (!$this->connect) $this->getConnect();
        $s = OCIParse($c, $sql_in);
        if (count($par)>0 && $par){
            foreach ($par as $key=>$val) {
                oci_bind_by_name($s, $key, $par[$key]);
            }
        }
        if (!OCIExecute($s, OCI_DEFAULT)) {
            $e = oci_error($s);
            $this->error = "Oracle Error [".$e['message']."]";
            $this->closeConnect();
            return false;
        }else{
            OCI_Commit($c);
            $this->closeConnect();
            return true;
        }
    }

}