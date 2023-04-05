<?php

require_once('/tcpdf_barcodes_2d.php');
$data = (isset($_GET['data']))? $_GET['data'] : substr(0, 10, md5('uncleTest'));
$barcodeobj = new TCPDF2DBarcode($data, 'PDF417');
echo $barcodeobj->getBarcodePngData(1, 1, array(0, 0, 0));
//echo $barcodeobj->getBarcodeHTML(1, 1, 'black');

?>