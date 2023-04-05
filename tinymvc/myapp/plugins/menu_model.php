<?php
class Menu_Model extends TinyMVC_Model
{
    function index(){
        //Смотри http://tinymvc.com/documentation/index.php/Documentation:Models#Using_the_PDO_database_layer
        //return $this->db->query_one('select * from members where id=?',array($id)); - вернуть всего одну запись
        //return $this->db->query_all('select * from members where date=?',array($date)); //Возвращает все записи
		return "hi".$_SESSION['admin'];
    }	
	
	function lalka(){
		return "fdsfds";
    }	
	
	
}
?>