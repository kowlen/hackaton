<?php

require_once 'tinymvc/myapp/controllers/access.php';

class Main_Controller extends TinyMVC_Controller
{
    function index(){
        header('Location:/pages/');
    }

}