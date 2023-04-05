<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.to_date.php
 * Type:     function
 * Name:     to_date
 * Purpose:  формат даты
 * Author:   Борисов Николай Сергеевич (uncleMaster)
 * E mail: coolphp@mail.ru
 * -------------------------------------------------------------
 */

function smarty_function_to_date($params, $template)
{
   // $date_ = date("d.m.Y", strtotime($params['date']));  
    $date_ = $params['date'];//(empty($params['date'])) ? "" : date("d.m.Y", strtotime($params['date']));  
        return $date_;
}
?>