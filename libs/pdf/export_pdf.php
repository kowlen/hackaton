<?php
require_once('tcpdf_include.php');
header('Content-Type: text/html; charset=utf-8');
// create new PDF document
////PDF_PAGE_ORIENTATION
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('uncle');
//$pdf->SetTitle('TCPDF Example 001');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
$search  = array('h5', '/h5');
//$search2  = 'div class="minitext" style="font-size: 12px"';
$search3  = 'id="table-head" style="width: 100%;font-size: 16px;"';
$search4  = '<tr class="removed-element-tr"></tr>';
$replace = array('div style="font-size: 8px;margin-bottom: 20px;" ', '/div');
//$replace2 = 'div class="minitext" style="font-size:5px"';
$replace3 = 'id="table-head" style="width: 100%;font-size:8px;"';
$replace3 = 'id="table-head" style="width: 100%;font-size:8px;"';
$replace4 = '<br pagebreak="true">';
$_POST['data'] = str_replace($search, $replace, $_POST['data']);
//$_POST['data'] = str_replace($search2, $replace2, $_POST['data']);
$_POST['data'] = str_replace($search3, $replace3, $_POST['data']);
$_POST['data'] = str_replace($search4, $replace4, $_POST['data']);


// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, ' ', 'Web - '.mb_convert_encoding($_POST['nm'], 'UTF-8'));
//$pdf->SetHeaderData(PDF_HEADER_LOGO, 4, '', 'павпавпа');
$pdf->setFooterData(array(0,0,0), array(255,255,255));

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 0));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

/*
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
*/
// set some language-dependent strings (optional)
//if (@file_exists(dirname(__FILE__).'/lrequest_executorsang/eng.php')) {
//	require_once(dirname(__FILE__).'/lang/eng.php');
	//$pdf->setLanguageArray($l);
//}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$size = (isset($_POST['bigsize'])) ? 8 : 7;
$pdf->SetFont('dejavusans', '', $size, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.

//$pdf->SetY(-1);
// Set font
//$pdf->SetFont('helvetica', 'I', 8);
// Page number
//$pdf->Cell(50, 10, $pdf->getAliasNumPage(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(true);
$pdf->AddPage();

//$pdf->Cell(0, 10,$pdf->getAliasNumPage());
// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$css = 'td{padding: 10px;text-align: center;}';//file_get_contents("http://localhost:666/tpl/styles/style_single.css");
$html = "<style>".$css."</style>".$_POST['data'];
//html = str_replace("class=\"info\"", "style=\"background: #d9edf7;\"", $html);
//$html = str_replace("%<table%", "<table border=1", $html);

// Print text using writeHTMLCell()
//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
//$pdf->writeHTML('Отчет сформирован в ЕКП и ФСН.'.$html, 0, true, 0, true, '');

$pdf->writeHTML($html, 0, true, 0, true, '');

// reset pointer to the last page
$pdf->lastPage();
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$_POST['nm'] = str_replace(' ', '_', $_POST['nm']);
$pdf->Output($_POST['nm'].'altan_export.pdf', 'I');