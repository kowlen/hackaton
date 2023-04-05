<?php
/*
 * Smarty modifier factor elem
 * @by uncleMaster
*/
function smarty_modifier_nicenum($val)
{
    $plus=(stristr($val, '+'))?"+":"";
    $val = (empty($val))?0:$val;
    if (is_numeric($val)&&$val!=0)
        return $plus.number_format($val, 2, ',', ' ');
    else
        return $plus.$val;
}
