<?php
/*
 * Smarty modifier factor elem
 * @by uncleMaster
*/
function smarty_modifier_fe_print($val)
{
    $retval = '#fff';
    if (!empty($val)) {
        if ($val <= 0.6) {
            $retval = '#fbcaca';
        }elseif (($val > 0.6) and ($val < 0.9) ) {
            $retval = '#ffffc5';
        }elseif ($val >= 0.9) {
            $retval = '#d3ffd3';
        }else{
            $retval = 'red';
        }
        /*if ($val <= 0.25) {
            $retval = '#d3ffd3';
        }elseif (($val > 0.25) and ($val <= 0.5) ) {
            $retval = '#ffffc5';
        }elseif (($val > 0.5) and ($val <= 0.75) ) {
            $retval = '#fbcaca';
        }elseif ($val > 0.75) {
            $retval = '#b7b7b7';
        }else{
            $retval = '#fff';
        }*/
    }
    return $retval;
}
