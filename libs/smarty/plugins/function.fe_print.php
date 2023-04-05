<?php
    function smarty_function_fe_print($params)
    {
        if (isset($params['val'])) {
            return $params['val'] . "-----";
        }
    }
?>