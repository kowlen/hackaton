<?php

require_once('configs/conf.php');
require_once('tinymvc/myapp/models/db_model.php');

class Gate_model
{
    function __construct(){
        $this->mainDb = new db_model();
    }

    function getPagesList(){
        $sql = <<<SQL
            SELECT 
                    id,
                    name,
                    title,
                    content,
                    description,
                    keywords,
                    is_visible,
                    url 
            FROM PAGES
SQL;

        return $this->mainDb->goResult($sql);
    }

    function getPageVariables($id){
        $sql = <<<SQL
            SELECT 
                    id,
                    name,
                    title,
                    content,
                    description,
                    keywords,
                    is_visible,
                    url 
            FROM PAGES
            WHERE id = $id
SQL;

        return $this->mainDb->goResultOnce($sql);
    }

    function setPageVariables($form){
        $id = $form['id'];
        $name = $form['name'];
        $title = $form['title'];
        $content = $form['content'];
        $description = $form['description'];
        $keywords = $form['keywords'];
        $url = $form['url'];

        if($id){
            $sql = <<<SQL
            UPDATE PAGES SET
                    name = '$name',
                    title = '$title',
                    content = '$content',
                    description = '$description',
                    keywords = '$keywords',
                    url = '$url' 
            WHERE id = $id
SQL;
            echo 'page update';
        }else{
            $sql = <<<SQL
                INSERT INTO PAGES (name,title,content,description,keywords,url)
                VALUES (
                    '$name',
                    '$title',
                    '$content',
                    '$description',
                    '$keywords',
                    '$url'
                )
SQL;
            echo 'page insert';
        }

        return $this->mainDb->query($sql);
    }

    function getNavigationList(){
        $sql = <<<SQL
            SELECT
                   id,
                   name,
                   type,
                   url,
                   icon,
                   is_visible,
                   url 
            FROM NAVIGATION
SQL;

        return $this->mainDb->goResult($sql);
    }

    function getNavigationVariables($id){
        $sql = <<<SQL
            SELECT 
                    id,
                   type,
                   name,
                   url,
                   icon,
                   is_visible,
                   url 
            FROM NAVIGATION
            WHERE id = $id
SQL;
        return $this->mainDb->goResultOnce($sql);
    }

    function setNavigationVariables($form){
        $id = $form['id'];
        $name = $form['name'];
        $url = $form['url'];
        $icon = $form['icon'];
        $type = $form['type'];
//        $is_visible = $form['is_visible'];
        $is_visible = 1 ;

        if($id){
            $sql = <<<SQL
            UPDATE NAVIGATION SET
                    name = '$name',
                    icon = '$icon',
                    is_visible = '$is_visible',
                    type = '$type',
                    url = '$url' 
            WHERE id = $id
SQL;
            echo 'NAVIGATION update';
        }else{
            $sql = <<<SQL
                INSERT INTO NAVIGATION (name,type,icon,is_visible,url)
                VALUES (
                    '$name',
                    '$type',
                    '$icon',
                    '$is_visible',
                    '$url'
                )
SQL;
            echo 'NAVIGATION insert';
        }

        $this->mainDb->query($sql);

        return ;
    }
}