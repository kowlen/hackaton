<?php
require_once('tcpdf_include.php');
header('Content-Type: text/html; charset=utf-8');
// create new PDF document
////PDF_PAGE_ORIENTATION
$pdf = new TCPDF('A4', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('uncle');


$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 0));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$size = (isset($_POST['bigsize'])) ? 8 : 8;
$pdf->SetFont('dejavusans', '', $size, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(true);

$pdf->AddPage();

// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = $_POST['data'];
//var_dump($_POST);
$pdf->writeHTML($html, 0, true, 0, true, '');

// reset pointer to the last page
$pdf->lastPage();
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$_POST['nm'] = str_replace(' ', '_', $_POST['nm']);
$pdf->Output($_POST['name'].'altan_export.pdf', 'I');