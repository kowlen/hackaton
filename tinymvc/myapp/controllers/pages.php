<?php

require_once 'tinymvc/myapp/controllers/access.php';
require_once 'tinymvc/myapp/models/gate_model.php';

class Pages_Controller extends TinyMVC_Controller
{
    function displayMainLayout(){
        $this->gate = new Gate_model();
        $navigation = $this->gate->getNavigationList();
        $this->smarty->assign('navigation', $navigation);
        $this->smarty->assign('title', $this->title);
        $this->smarty->assign('content', $this->content);
        $this->smarty->display('tpl/layouts/layout.main.html');
    }


    function index(){
        header('Location:/pages/home/');
    }

    function home(){
        $this->title = 'Главная страница';
        $this->content = 'tpl/fotosalon/fotosalon.html';

        $this->displayMainLayout();
    }

    function about(){
        $this->title = 'О нас';
        $this->content = 'tpl/fotosalon/about.html';

        $this->displayMainLayout();
    }

    function delivery(){
        $this->title = 'tpl/fotosalon/deliveryandpay.html';
        $this->content = 'tpl/fotosalon/about.html';

        $this->displayMainLayout();
    }

    function where(){
        $this->title = 'where';
        $this->content = 'tpl/fotosalon/where.html';

        $this->displayMainLayout();
    }

    function photographyservices(){
        $this->title = 'Печать фото';
        $this->content = 'tpl/fotosalon/photographyservices.html';

        $this->displayMainLayout();
    }

    function photodocuments(){
        $this->title = 'Фото на документы';
        $this->content = 'tpl/fotosalon/photodocuments.html';

        $this->displayMainLayout();
    }

    function photoprint(){
        $this->title = 'Печать фотографий';
        $this->content = 'tpl/fotosalon/photoprint.html';

        $this->displayMainLayout();
    }

    function souvenirproducts(){
        $this->title = 'Сувенирная продукция';
        $this->content = 'tpl/fotosalon/souvenirproducts.html';

        $this->displayMainLayout();
    }

    function cups(){
        $this->title = 'Кружки';
        $this->content = 'tpl/fotosalon/cups.html';

        $this->displayMainLayout();
    }

}