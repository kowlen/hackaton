<?php
require_once('tinymvc/myapp/models/access_model.php');
require_once('tinymvc/myapp/models/utilites_model.php');

class Access_controller extends TinyMVC_Controller
{
    function index(){
        header("location: /admin/pages/");
    }

    function login(){
        $uid = isset($_SESSION['user']['uid']) && !empty($_SESSION['user']['uid']) ? $_SESSION['user']['uid'] : false;
        $dui = isset($_SESSION['user']['diu']) && !empty($_SESSION['user']['diu']) ? $_SESSION['user']['diu'] : false;
        $uid_id = isset($_SESSION['user']['userId']) && !empty($_SESSION['user']['userId']) ? $_SESSION['user']['userId'] : false;
        if ($uid && $dui && $uid_id){
            header('Location:/admin/pages/');
        }else{
            $this->smarty->display('tpl/layouts/layout.auth.html');
        }
    }

    function logout(){
        $this->access = new Access_Model();
        $this->access->cleanSession();
        header('Location: /access/login/');
    }

    function authorization(){
        $this->access = new Access_Model();
        $this->utilites = new Utilites_model();

        $login = isset($_POST['login']) && !empty($_POST['login']) ? $this->utilites->clean_var($_POST['login']) : user_error('[login] is empty');
        $loginUser = 'STAT_'.strtoupper($login);
        $password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : user_error('[password] is empty');
        $passwordHash = md5($password."uncleMasterwasHeRe+secret_key_031016+vazazaza");
        $userHash = hash('whirlpool', $loginUser.'key_ekp_kk31');
        $uipHash = substr(md5($_SERVER['HTTP_USER_AGENT']."".$_SERVER['REMOTE_ADDR']."secret_ekp_keyWERNBV"), 0, 15);

        $form = [
            'login' => $login,
            'passwordHash' => $passwordHash,
            'loginUser' => $loginUser,
            'password' => $password,
            'userHash' => $userHash,
            'uipHash' => $uipHash,
        ];

        $_SESSION['lastLogin'] = $login;
        $login = $this->access->getLogin($form);

        switch ($login){
            case true:
                $_SESSION['user'] = $login;
                echo 'ok';
            break;
            case false:
                echo 'Неверный логин или пароль';
            break;
        }
    }

}
?>