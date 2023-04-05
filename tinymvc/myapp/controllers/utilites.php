<?php

require_once('tinymvc/myapp/models/utilites_model.php');

class Utilites_controller extends TinyMVC_Controller
{

    function downloadFileFromDb(){
        $this->utilites = new Utilites_model();

        $id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : user_error("not need vars [id]");

        $file = $this->utilites->getFileFromDb($id);
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="'.$this->utilites->translitnotdot($file['FILENAME']).'"');
        header("Content-Type: ".$this->utilites->fileTypes[$file['EXTENSION']]."");
        echo $file['IBLOB']->load();
    }

    function downloadImageFromSotrud(){
        $this->utilites = new Utilites_model();

        $sotrud_k = (isset($_GET['sotrud_k']) && !empty($_GET['sotrud_k'])) ? $_GET['sotrud_k'] : user_error("not need vars [sotrud_k]");

        $image = $this->utilites->downloadImageFromSotrud($sotrud_k);
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="'.$sotrud_k.'".png');
        header("Content-Type: image/png");
        echo $image['FOTO']->load();
    }

    // /utilites/getQRcode/?s=http://localhost:55/
    function getQRcode(){
        $this->utilites = new Utilites_model();

        require_once('inc_libs/qrcode/qrlib.php');
        $source = (isset($_GET['s']) && !empty($_GET['s'])) ? $_GET['s'] : user_error("not need vars [s]");
        QRcode::png($source, 'inc_libs/qrcode/temp/qr.png', 'H', 6, 2);

        $im = imagecreatefrompng('inc_libs/qrcode/temp/qr.png');

        $width = imagesx($im);
        $height = imagesy($im);

        $dst = imagecreatetruecolor($width, $height);
        imagecopy($dst, $im, 0, 0, 0, 0, $width, $height);
        imagedestroy($im);

        $logo = imagecreatefrompng('tpl/static/128x128.png');
        //$logo = imagecreatefrompng('tpl/static/logo_black.png');
        $logo_width = imagesx($logo);
        $logo_height = imagesy($logo);

        $new_width = $width / 3;
        $new_height = $logo_height / ($logo_width / $new_width);

        $x = ceil(($width - $new_width) / 2);
        $y = ceil(($height - $new_height) / 2);

        imagecopyresampled($dst, $logo, $x, $y, 0, 0, $new_width, $new_height, $logo_width, $logo_height);

        header('Content-Type: image/x-png');
        imagepng($dst);
    }

    //test me anything
    function test(){
//        if(isset($_GET['sort']['brigad_k']) && !empty($_GET['sort']['brigad_k'])){
//            var_dump($_GET['sort']['brigad_k']);
//        }else{
//            var_dump($_GET['sort']);
//        }
//        var_dump($_GET);

        $this->db_ora = new Db_ora_Model();

//        $sql = <<<SQL
//            begin
//            INSERT INTO DOLJNOST (TEXT,  ITR, LEVEL_PK) VALUES ('test', 'Y', '3');
//            end;
//            begin
//                SELECT * FROM DOLJNOST WHERE KOD = (SELECT max(KOD) FROM DOLJNOST);
//end;
//SQL;

        if (!$result = $this->db_ora->go_result($sql)){echo($this->db_ora->error);}
        return $result;
    }


}
?>