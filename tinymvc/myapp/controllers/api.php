<?php

require_once('/configs/conf.php');
require_once('tinymvc/myapp/models/gate_model.php');
require_once('tinymvc/myapp/models/utilites_model.php');
require_once('tinymvc/myapp/models/access_model.php');

class Api_Controller extends TinyMVC_Controller
{

    function index(){
        echo 'Контроллер API';
    }

    function test(){
        $this->access = new Access_Model();
        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : user_error('[action] is empty');

        switch ($action) {
            default:
                echo 'action name - '.$action;
                break;
        }
    }

    function pages(){
        $this->gate = new Gate_model();
        $this->utilites = new Utilites_model();
        $this->access = new Access_Model();

//        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : 'action is null';

        switch ($action){
            case 'getPages':
                $pages = $this->gate->getPagesList();
                $this->utilites->printJson($pages);
            break;
            case 'getPageVariables':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : $this->utilites->printJson(['error' => 'id is null']);
                $page = $this->gate->getPageVariables($id);
                $this->utilites->printJson($page);
            break;
            case 'setPageVariables':
                $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : false;
                $name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : '';
                $title = isset($_POST['title']) && !empty($_POST['title']) ? $_POST['title'] : '';
                $content = isset($_POST['content']) && !empty($_POST['content']) ? $_POST['content'] : '';
                $description = isset($_POST['description']) && !empty($_POST['description']) ? $_POST['description'] : '';
                $keywords = isset($_POST['keywords']) && !empty($_POST['keywords']) ? $_POST['keywords'] : '';
                $url = isset($_POST['url']) && !empty($_POST['url']) ? $_POST['url'] : '';

                $form = [
                    'id' => $id ,
                    'name' => $name ,
                    'title' => $title ,
                    'content' => $content ,
                    'description' => $description ,
                    'keywords' => $keywords ,
                    'url' => $url
                ];

                $this->gate->setPageVariables($form);
            break;
        }
    }

    function navigation(){
        $this->gate = new Gate_model();
        $this->utilites = new Utilites_model();
        $this->access = new Access_Model();

//        $this->access->checkAccess();

        $action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : 'action is null';

        switch ($action){
            case 'getList':
                $pages = $this->gate->getNavigationList();
                $this->utilites->printJson($pages);
            break;
            case 'getVariables':
                $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : $this->utilites->printJson(['error' => 'id is null']);
                $page = $this->gate->getNavigationVariables($id);
                $this->utilites->printJson($page);
            break;
            case 'setVariables':
                $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : false;
                $name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : '';
                $icon = isset($_POST['icon']) && !empty($_POST['icon']) ? $_POST['icon'] : '';
                $type = isset($_POST['type']) && !empty($_POST['type']) ? $_POST['type'] : '';
                $url = isset($_POST['url']) && !empty($_POST['url']) ? $_POST['url'] : '';

                $form = [
                    'id' => $id ,
                    'name' => $name ,
                    'icon' => $icon ,
                    'type' => $type ,
                    'url' => $url ,
                ];


                $this->gate->setNavigationVariables($form);
            break;
        }
    }

    function getTranslitUrl(){
        $this->utilites = new Utilites_model();
        $text = isset($_POST['text']) && !empty($_POST['text']) ? $_POST['text'] : '';
        $translit = $this->utilites->translitnotdot($text);
        print_r('/pages/?page_name='.strtolower($translit));
    }

}