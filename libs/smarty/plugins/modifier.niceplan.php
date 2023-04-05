<?php
/*
 * Smarty modifier factor elem
 * @by uncleMaster
*/
function smarty_modifier_niceplan($str)
{
    $result = "";
    $chars = preg_split('/ /', $str, -1, PREG_SPLIT_OFFSET_CAPTURE);
    foreach ($chars as $value) {
        $result .= (stristr($value[0], '.')) ? $value[0]." " : $value[0]."<br>";
    }
    return $result;
}
