<?php

require_once('tinymvc/myapp/models/db_model.php');
require_once('tinymvc/myapp/models/utilites_model.php');

class Access_Model extends TinyMVC_Model
{
    public $db;
    public $func;

    function __construct(){
        $this->db= new db_model();
        $this->utilites= new Utilites_model();
    }

    function getLogin($form){
        $this->utilites= new Utilites_model();

        $login = $form['login'];//example: VASIOLENKOV_...
        $loginUser = $form['loginUser'];//example: STAT_VASIOLENKOV_...
        $passwordHash = $form['passwordHash'];
        $uipHash = $form['uipHash'];

        $sql = <<<SQL
		    select id, name, username, role_id 
		    from users 
		    where username = '$login' 
		    and pwd = '$passwordHash'
SQL;
        if (!$result = $this->db->goResultOnce($sql)) {echo($this->db->error);}
        if ($result){
            $hash = $this->utilites->data_encode($login);
            $hash = substr($hash, 0, 15).$uipHash.substr($hash, 15, strlen($hash));

            $result['diu'] = $hash;
            $result['uid'] = $loginUser;
            $result['userId'] = $result['id'];
            $result['roleId'] = $result['role_id'];
            return $result;
        }else{
            return false;
        }
    }

    function getAdminData($form){
        $par = [
            'USERNAME' => $form['login'],
            'PWD' => $form['passwordHash'],
        ];

        $sql = <<<SQL
		    SELECT 
		         USER_ID,
		         USERNAME
		    FROM WEB_API_USERS t 
		    WHERE USERNAME = :USERNAME 
		    AND PWD = :PWD
SQL;

        if ($result = $this->db->go_result_once2($sql, $par)){
            return $result;
        }else{
            return false;
        }
    }

    function getUserData($userId){
        $result = [];

        $par = [
            'USER_ID' => $userId
        ];

        $sql = <<<SQL
            SELECT 
                SOTRUD_K,
                PREDPR_K,
--                 USER_ID,
                UCHAST_K DIVISION_ID,
                SOTRUD_FAM || ' ' || SOTRUD_IM || ' ' || SOTRUD_OTCH FIO,
                stat.pack.sotrud_fio_sokr(SOTRUD_K) FIO_SOKR,
                stat.pack.DOLJ_NAIM_SOTR(SOTRUD_K) DOLJ_NAIM,
                (SELECT PREDPR_POLN FROM PREDPR WHERE PREDPR.PREDPR_K = SOTRUD.SOTRUD_K) ENTERPRISE_NAME,
                DOLJ_K
            FROM SOTRUD WHERE USER_ID = :USER_ID		
SQL;

        if (!$userData = $this->db->go_result_once2($sql, $par)) {
            echo($this->db->error);
        }

        $result = $userData;

        $par = [
            "PREDPR_K" => $result['PREDPR_K']
        ];

        $sql = <<<SQL
            SELECT 
                UCHAST_TIP_K
            FROM STAT.PREDPR WHERE PREDPR_K = :PREDPR_K 
SQL;

        if (!$utk = $this->db->go_result_once2($sql, $par)['UCHAST_TIP_K']) {
            echo($this->db->error);
        }

        $result['utk'] = $utk;

        return $result;
    }

    function getCountFailAuth($userHash){
        $par = [
            "UHASH" =>$userHash
        ];

        $sql = <<<SQL
            SELECT 
                   (CASE WHEN SYSDATE-MAX(dt) > 0.0007 THEN '1' ELSE '0' END) DIFF, 
                   COUNT(dt) CNT 
            FROM STAT.WEB_GM_FAIL_AUTH
            WHERE ID = :UHASH
SQL;
        if (!$result = $this->db->go_result_once2($sql, $par)) {
            echo($this->db->error);
        }

        //when one min later, clean deny
        if ($result['DIFF'] == 1){
            $result['CNT'] = 0;
            $this->cleanFailAuth($userHash);
        }

        return $result;
    }

    function logEnteredUser($userId, $login){
        $par = [
            'USER_ID' => $userId,
            'LOGIN' => $login,
        ];

        $sql = <<<SQL
            INSERT INTO stat.WEB_LOGS_ENTERED (
                SOTID, 
                USERID, 
                SFIO, 
                POSTID, 
                POST, 
                DAT, 
                LOGIN
            )(
                select 
                    SOTRUD_K, 
                    :USER_ID, 
                    stat.pack.sotrud_fio_poln(SOTRUD_K), 
                    DOLJ_K, 
                    stat.pack.dolj_naim(DOLJ_K), 
                    SYSDATE, 
                    :LOGIN
                FROM SOTRUD 
                WHERE ROWNUM = 1 
                AND USER_ID = :USER_ID
            )
SQL;

        if (!$this->db->go_query2($sql, $par)) {
            echo($this->db->error);
        }
    }

    function logFailAuth($userHash){
        $par = [
            'ID' => $userHash
        ];

        $sql = <<<SQL
            INSERT INTO STAT.WEB_GM_FAIL_AUTH (id) 
            VALUES(:ID)
SQL;
        if (!$this->db->go_query2($sql, $par)) {
            echo($this->db->error);
        }
    }

    function checkAccess(){
        $this->utilites = new Utilites_model();
        $uid = isset($_SESSION['user']['uid']) && !empty($_SESSION['user']['uid']) ? $_SESSION['user']['uid'] : false;
        $dui = isset($_SESSION['user']['diu']) && !empty($_SESSION['user']['diu']) ? $_SESSION['user']['diu'] : false;
        $uid_id = isset($_SESSION['user']['userId']) && !empty($_SESSION['user']['userId']) ? $_SESSION['user']['userId'] : false;

        if ($uid && $dui && $uid_id){
            $my_hash_uip = substr(md5($_SERVER['HTTP_USER_AGENT']."".$_SERVER['REMOTE_ADDR']."secret_ekp_keyWERNBV"), 0, 15);

            if (substr($dui, 15, 15) <> $my_hash_uip){
                $this->cleanSession();
                header("Location: /access/login/?ref=".$this->utilites->request_url());
            }else{
                if ($uid_id != $this->utilites->data_decode(substr($dui, 0, 15).substr($dui, 30, strlen($dui)))) {
                    $this->cleanSession();
                    header("Location: /access/login/?ref=".$this->utilites->request_url());
                }else{
                    return true;
                }
            }
        }else{
//            header("Location: /access/login/");
            header("Location: /access/login/?ref=".$this->utilites->request_url());
        }
    }

    function cleanSession(){
        unset($_SESSION['key']);
        unset($_SESSION['login']);
        unset($_SESSION['user']);
    }

    function cleanFailAuth($userHash){
        $par = [
            "UHASH" =>$userHash
        ];

        $sql = <<<SQL
            DELETE 
            FROM STAT.WEB_GM_FAIL_AUTH 
            WHERE ID = :UHASH
SQL;
        if (!$this->db->go_query2($sql, $par)) {
            echo($this->db->error);
        }
    }

//MANAGEEEEEEEEEEE
    function get_module_menu($user_role, $module){
        $sql = <<<SQL
		    SELECT 
		         *
		    FROM WEB_MENU t 
		    WHERE module = :module 
		    AND show = 'Y'
		    order by sort, name
SQL;
        $par = ([
            //'user_role' => $user_role,
            'module' => $module
        ]) ;
        if ($result = $this->db->go_result2($sql, $par)){
            return $result;
        }else{
            return false;
        }
    }

}
?>