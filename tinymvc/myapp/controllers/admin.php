<?php

require_once('tinymvc/myapp/models/access_model.php');

class Admin_Controller extends TinyMVC_Controller
{
    function index(){
        header('Location: /admin/pages/');
    }

    function pages(){
        $this->access = new Access_Model();
//        $this->access->checkAccess();
        $this->smarty->assign('content','tpl/admin/pages.html');
        $this->smarty->display('tpl/layouts/layout.admin.html');
    }

    function navigation(){
        $this->access = new Access_Model();
//        $this->access->checkAccess();
        $this->smarty->assign('content','tpl/admin/navigation.html');
        $this->smarty->display('tpl/layouts/layout.admin.html');
    }

}