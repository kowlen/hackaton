<?php
    function smarty_modifier_format_phone($string){
        $sPhone = preg_replace("!/^(\+?7|8)([0-9]){10}$/!",'',$string);
         
            $sCode = substr($sPhone, 0,2);  
            $sArea = substr($sPhone, 2,3); 
            $sPrefix = substr($sPhone,5,3); 
            $sNumber = substr($sPhone,8,4); 
            $sPhone = $sCode."(".$sArea.")".$sPrefix."-".$sNumber;
             
                return $sPhone; 
    }
?>