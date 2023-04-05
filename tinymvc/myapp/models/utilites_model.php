<?php

class Utilites_model extends TinyMVC_Model
{
    public $func, $db_ora;

    public $fileTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpe' => 'image/jpeg',
        'gif' => 'image/gif',
        'png' => 'image/png',
        'bmp' => 'image/bmp',
        'flv' => 'video/x-flv',
        'js' => 'application/x-javascript',
        'json' => 'application/json',
        'tiff' => 'image/tiff',
        'css' => 'text/css',
        'xml' => 'application/xml',
        'doc' => 'application/msword',
        'xls' => 'application/vnd.ms-excel',
        'xlt' => 'application/vnd.ms-excel',
        'xlm' => 'application/vnd.ms-excel',
        'xld' => 'application/vnd.ms-excel',
        'xla' => 'application/vnd.ms-excel',
        'xlc' => 'application/vnd.ms-excel',
        'xlw' => 'application/vnd.ms-excel',
        'xll' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pps' => 'application/vnd.ms-powerpoint',
        'rtf' => 'application/rtf',
        'pdf' => 'application/pdf',
        'html' => 'text/html',
        'htm' => 'text/html',
        'php' => 'text/html',
        'txt' => 'text/plain',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mpe' => 'video/mpeg',
        'mp3' => 'audio/mpeg3',
        'wav' => 'audio/wav',
        'aiff' => 'audio/aiff',
        'aif' => 'audio/aiff',
        'avi' => 'video/msvideo',
        'wmv' => 'video/x-ms-wmv',
        'mov' => 'video/quicktime',
        'zip' => 'application/zip',
        'tar' => 'application/x-tar',
        'swf' => 'application/x-shockwave-flash',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ott' => 'application/vnd.oasis.opendocument.text-template',
        'oth' => 'application/vnd.oasis.opendocument.text-web',
        'odm' => 'application/vnd.oasis.opendocument.text-master',
        'odg' => 'application/vnd.oasis.opendocument.graphics',
        'otg' => 'application/vnd.oasis.opendocument.graphics-template',
        'odp' => 'application/vnd.oasis.opendocument.presentation',
        'otp' => 'application/vnd.oasis.opendocument.presentation-template',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'ots' => 'application/vnd.oasis.opendocument.spreadsheet-template',
        'odc' => 'application/vnd.oasis.opendocument.chart',
        'odf' => 'application/vnd.oasis.opendocument.formula',
        'odb' => 'application/vnd.oasis.opendocument.database',
        'odi' => 'application/vnd.oasis.opendocument.image',
        'oxt' => 'application/vnd.openofficeorg.extension',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
        'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
        'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
        'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
        'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
        'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
        'potm' => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
        'ppam' => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
        'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
        'sldm' => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
        'thmx' => 'application/vnd.ms-officetheme',
        'onetoc' => 'application/onenote',
        'onetoc2' => 'application/onenote',
        'onetmp' => 'application/onenote',
        'onepkg' => 'application/onenote',
        'csv' => 'text/csv',
    ];

    public $months = [
        '1' => 'января' ,
        '01' => 'января' ,
        '2' => 'февраля' ,
        '02' => 'февраля' ,
        '3' => 'марта' ,
        '03' => 'марта' ,
        '4' => 'апреля' ,
        '04' => 'апреля' ,
        '5' => 'мая' ,
        '05' => 'мая' ,
        '6' => 'июня' ,
        '06' => 'июня' ,
        '7' => 'июля' ,
        '07' => 'июля' ,
        '8' => 'августа' ,
        '08' => 'августа' ,
        '9' => 'сентября' ,
        '09' => 'сентября' ,
        '10' => 'октября' ,
        '11' => 'ноября' ,
        '12' => 'декабря',

    ];

    public function __construct(){
//        $this->db_ora= new Db();
    }

    function getFileFromDb($id){
        $par = [
            'PREDPIS_IMAGE_K' => $id
        ];

        $sql = <<<SQL
            SELECT 
                PREDPIS_K,
                PREDPIS_TIP,
                FILENAME,
                IBLOB,
                PREDPIS_IMAGE_K,
                EXTENSION,
                SOTRUD_K
            FROM PREDPIS_IMAGE
            WHERE PREDPIS_IMAGE_K = :PREDPIS_IMAGE_K
SQL;

        $file = $this->db_ora->go_result_once2($sql, $par);
        return $file;
    }

    function downloadImageFromSotrud($sotrud_k){
        $par = [
            'SOTRUD_K' => $sotrud_k
        ];

        $sql = <<<SQL
            SELECT 
                FOTO
            FROM SOTRUD
            WHERE SOTRUD_K = :SOTRUD_K
SQL;

        $image = $this->db_ora->go_result_once2($sql, $par);
        return $image;
    }

    function array_to_xml( $data, &$xml_data ) {
        foreach( $data as $key => $value ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            if( is_array($value) ) {
                $subnode = $xml_data->addChild($key);
                array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }

    function valid_login($data){
        if (!preg_match("/^[A-Za-z0-9][A-Za-z0-9-_.\ ]+[A-Za-z0-9]$/is", $data))
            return "0";
        else
            return "1";
    }

    function valid_sort_rows($data){
        //if (!preg_match("/^[A-Z\,\_]+$/is", $data))
        if (!preg_match("/^[A-Z0-9\,\_]+$/is", $data))
            return "0";
        else
            return "1";
    }

    function time_execute(){
        if (developer == 1)
            printf(function_script_execute, microtime(true) - start);
    }

    function clean_var($data){
        return htmlspecialchars(trim($data));
    }

    function clean_get(){
        if (!empty($_GET)) {
            $new_get = array_filter($_GET);
            if (count($new_get) < count($_GET)) {
                $request_uri = parse_url('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
                header('Location: ' . $request_uri . '?' . http_build_query($new_get));
                exit;
            }
        }
    }

    function data_encode($str){
        $pub = <<<WEBEKPAUTH
-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBALqbHeRLCyOdykC5SDLqI49ArYGYG1mq
aH9/GnWjGavZM02fos4lc2w6tCchcUBNtJvGqKwhC5JEnx3RYoSX2ucCAwEAAQ==
-----END PUBLIC KEY-----
WEBEKPAUTH;
        $pk  = openssl_get_publickey($pub);
        openssl_public_encrypt($str, $encrypted, $pk);
        $data = chunk_split(base64_encode($encrypted));
        return $data;
    }

    function data_decode($hash){
        $key = <<<WEBEKPAUTH
-----BEGIN RSA PRIVATE KEY-----
MIIBPQIBAAJBALqbHeRLCyOdykC5SDLqI49ArYGYG1mqaH9/GnWjGavZM02fos4l
c2w6tCchcUBNtJvGqKwhC5JEnx3RYoSX2ucCAwEAAQJBAKn6O+tFFDt4MtBsNcDz
GDsYDjQbCubNW+yvKbn4PJ0UZoEebwmvH1ouKaUuacJcsiQkKzTHleu4krYGUGO1
mEECIQD0dUhj71vb1rN1pmTOhQOGB9GN1mygcxaIFOWW8znLRwIhAMNqlfLijUs6
rY+h1pJa/3Fh1HTSOCCCCWA0NRFnMANhAiEAwddKGqxPO6goz26s2rHQlHQYr47K
vgPkZu2jDCo7trsCIQC/PSfRsnSkEqCX18GtKPCjfSH10WSsK5YRWAY3KcyLAQIh
AL70wdUu5jMm2ex5cZGkZLRB50yE6rBiHCd5W1WdTFoe
-----END RSA PRIVATE KEY-----
WEBEKPAUTH;
        $pk  = openssl_get_privatekey($key);
        openssl_private_decrypt(base64_decode($hash), $out, $pk);
        return $out;
    }

    function translit($str)
    {
        $tr = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
            "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
            "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"'","э"=>"e","ю"=>"yu","я"=>"ya",
            "."=>"_"," "=>"_","?"=>"_","/"=>"_","\\"=>"_",
            "*"=>"_",":"=>"_","*"=>"_","\""=>"_","<"=>"_",
            ">"=>"_","|"=>"_"
        );
        return strtr($str, $tr);
    }

    function translitnotdot($str)
    {
        $tr = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
            "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
            "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
            "."=>"."," "=>"_","?"=>"_","/"=>"_","\\"=>"_",
            "*"=>"_",":"=>"_","*"=>"_","\""=>"_","<"=>"_",
            ">"=>"_","|"=>"_","\""=>"","'|'"=>""
        );
        return strtr($str, $tr);
    }

    function reverseword($str)
    {
        $tr = array(
            "Й"=>"Q","Ц"=>"W","У"=>"E","К"=>"R",
            "Е"=>"T","Н"=>"Y","Г"=>"U","Ш"=>"I","Щ"=>"O",
            "З"=>"P","Х"=>"{","Ъ"=>"}","Ф"=>"A","Ы"=>"S",
            "В"=>"D","А"=>"F","П"=>"G","Р"=>"H","О"=>"J",
            "Л"=>"K","Д"=>"L","Ж"=>":","Э"=>"\"","Я"=>"Z",
            "Ч"=>"X","С"=>"C","М"=>"V","И"=>"B","Т"=>"N",
            "Ь"=>"M","Б"=>"<","Ю"=>">","й"=>"q","ц"=>"w",
            "у"=>"e","к"=>"r","е"=>"t","н"=>"y","г"=>"u",
            "ш"=>"i","щ"=>"o","з"=>"p","х"=>"[","ъ"=>"]",
            "ф"=>"a","ы"=>"s","в"=>"d","а"=>"f","п"=>"g",
            "р"=>"h","о"=>"j","л"=>"k","д"=>"l","ж"=>";",
            "э"=>"'","я"=>"z","ч"=>"x","с"=>"c","м"=>"v",
            "и"=>"b","т"=>"n","ь"=>"m","б"=>",","ю"=>"."
        );
        return strtr($str, $tr);
    }

    function request_url()
    {
        $result = '';
        /*$default_port = 80;
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {
            $result .= 'https://';
            $default_port = 443;
        } else {
            $result .= 'http://';
        }
        $result .= $_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_PORT'] != $default_port) {
            $result .= ':'.$_SERVER['SERVER_PORT'];
        }*/
        $result .= $_SERVER['REQUEST_URI'];
        return $result;
    }

    function send_order(){
        $sql = <<<SQL
			begin dbms_alert.signal('send_naryad', 'send_naryad'); end;
SQL;
        $par = array();
        if (!$this->db_ora->go_query2($sql, $par)) {echo($this->db_ora->error);}
        return ($this->db_ora->error)?$this->db_ora->error:"ok";
    }

    function end_shift(){
        $sql = <<<SQL
			begin dbms_alert.signal('end_smen', 'end_smen'); end;
SQL;
        $par = array();
        if (!$this->db_ora->go_query2($sql, $par)) {echo($this->db_ora->error);}
        return ($this->db_ora->error)?$this->db_ora->error:"ok";
    }

    function accept_sign(){
        $sql = <<<SQL
			INSERT INTO STAT.NARYAD_ESIGNATURE (SOTRUD_K, UCHAST_K, ISP_SROK, SMEN_K, NARYAD_TIP_K) VALUES (4735, 672, trunc(sysdate), 1, 1)
SQL;
        $par = array();
        if (!$this->db_ora->go_query2($sql, $par)) {echo($this->db_ora->error);}
        $sql = <<<SQL
			begin dbms_alert.signal('refresh_signature', 'refresh_signature'); end;
SQL;
        $par = array();
        if (!$this->db_ora->go_query2($sql, $par)) {echo($this->db_ora->error);}
        return ($this->db_ora->error)?$this->db_ora->error:"ok";
    }

    function generate_checklists(){
        $sql = <<<SQL
		begin GENERATE_CHECKLIST.generate_from_checklist; end;
SQL;
        $par = array();
        if (!$this->db_ora->go_query2($sql, $par)) {echo($this->db_ora->error);}
        return ($this->db_ora->error)?$this->db_ora->error:"ok";
    }

    function add_logs($path, $txt, $type){
        $sql = <<<SQL
		insert into STAT.NN_LOGS (PATH, TXT, TYPE)
		values
		(:path, :txt, :type)
SQL;
        $par = array("path"=>(string)$path, "txt"=>(string)$txt, "type"=>(string)$type);
        //print_r($par);
        if (!$this->db_ora->go_query2($sql, $par)) {echo($this->db_ora->error);}
        return ($this->db_ora->error)?$this->db_ora->error:"ok";
    }

    function add_total($tech_id, $card_id, $dt, $enginehours, $totalworktime, $totalidletime){ //to_date(:dt,  'DD.MM.YYYY HH24:MI:SS')
        $sql = <<<SQL
		insert into STAT.NN_TOTAL (TECH_ID, CARD_ID, ENGINEHOURS, DATETIME, TOTALWORKTIME, TOTALIDLETIME)
		values
		(:tech_id, :card_id, :enginehours, :dt, :totalworktime, :totalidletime)
SQL;
        $par = array("tech_id"=>(string)$tech_id, "card_id"=>(string)$card_id, "enginehours"=>(string)$enginehours, "dt"=>(string)$dt, "totalworktime"=>(string)$totalworktime, "totalidletime"=>(string)$totalidletime);
        //print_r($par);
        if (!$this->db_ora->go_query2($sql, $par)) {echo($this->db_ora->error);}
        return ($this->db_ora->error)?$this->db_ora->error:"ok";
    }

    function add_idles($tech_id, $idle_id, $idle, $idle_begin, $idle_end){
        $sql = <<<SQL
		insert into STAT.NN_IDLES (tech_id, IDLE_ID, IDLE_NAME, IDLE_BEGIN, IDLE_END)
		values
		(:tech_id, :idle_id, :idle, to_date(:idle_begin,  'DD.MM.YYYY HH24:MI:SS'), to_date(:idle_end,  'DD.MM.YYYY HH24:MI:SS'))
SQL;
        $par = array("tech_id"=>(string)$tech_id, "idle_id"=>(string)$idle_id, "idle"=>(string)$idle, "idle_begin"=>(string)$idle_begin, "idle_end"=>(string)$idle_end);
        //print_r($par);
        if (!$this->db_ora->go_query2($sql, $par)) {echo($this->db_ora->error);}
        return ($this->db_ora->error)?$this->db_ora->error:"ok";
    }

    function del_idles($tech_id){
        $sql = <<<SQL
		delete STAT.NN_IDLES where tech_id = :tech_id
SQL;
        $par = array("tech_id"=>(string)$tech_id);
        if (!$this->db_ora->go_query2($sql, $par)) {echo($this->db_ora->error);}
        return ($this->db_ora->error)?$this->db_ora->error:"ok";
    }

    function add_checklist($ShiftState, $tech_id, $card_id, $dt, $enginehours, $totalworktime, $check_id, $check_name, $state, $notes){
        $sql = <<<SQL
		insert into STAT.NN_CHECKLIST (ShiftState, TECH_ID, CARD_ID, DT, ENGINE_HOURS, TOTALWORKTIME, CHECK_ID, CHECK_NAME, STATE, NOTES)
		values
		(:ShiftState, :tech_id, :card_id, to_date(:dt,  'DD.MM.YYYY HH24:MI:SS'), :enginehours, :totalworktime, :check_id, :check_name, :state, :notes)
SQL;
        $par = array("ShiftState"=>(string)$ShiftState, "tech_id"=>(string)$tech_id, "card_id"=>(string)$card_id, "dt"=>(string)$dt, "enginehours"=>(string)$enginehours, "totalworktime"=>(string)$totalworktime, "check_id"=>(string)$check_id, "check_name"=>(string)$check_name, "state"=>(string)$state, "notes"=>(string)$notes);
        //print_r($par);
        if (!$this->db_ora->go_query2($sql, $par)) {echo($this->db_ora->error);}
        return ($this->db_ora->error)?$this->db_ora->error:"ok";
    }

    function del_checklist($tech_id, $card_id, $dt){
        $sql = <<<SQL
		delete STAT.NN_CHECKLIST where CARD_ID = :card_id and TECH_ID = :tech_id and DT = to_date(:dt,  'DD.MM.YYYY HH24:MI:SS')
SQL;
        $par = array("tech_id"=>(string)$tech_id, "card_id"=>(string)$card_id, "dt"=>(string)$dt);
        if (!$this->db_ora->go_query2($sql, $par)) {echo($this->db_ora->error);}
        return ($this->db_ora->error)?$this->db_ora->error:"ok";
    }

    function get_rows($rows){
        $ret = array();
        if ($rows){
            foreach ($rows[0] as $key => $value) {

                $ret[] = $key;
            }
        }
        return $ret;
    }

    function get_rows_sotrud($rows){
        $ret = array();
        foreach ($rows[0] as $key => $value) {
            $ret[] = $key;
        }
        return $ret;
    }

    function make_rows_uniq($rows){
        $ret = array();
        $hash = "";
        foreach ($rows as $key => $value) {
            $hash = "uncleMaster_secret_key";
            foreach ($rows[$key] as $key2 => $value2) {
                $hash .= $value2;
            }
            $rows[$key]['hash'] = md5($hash."uncleMaster_end_key");
        }
        return $rows;
    }

    function printJson($array){
        print_r(json_encode($array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

}