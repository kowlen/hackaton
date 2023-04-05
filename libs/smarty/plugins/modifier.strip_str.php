<?php
    function smarty_modifier_strip_str($string, $lenght=60){
        if(strlen($string) > $lenght){
            $string = substr($string, 0, $lenght);
            $string = rtrim($string, "!,.-");
            $string = substr($string, 0, strrpos($string, ' '));
            $string = $string."… ";
        }
        
        return $string;

    }
?>