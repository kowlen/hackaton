<?php
/*require_once('tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('uncle');
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, ' ', 'Web EKP'); 
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setFontSubsetting(true);

$pdf->SetFont('dejavusans', '', 10, '', true);

$pdf->AddPage();
// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
// Set some content to print
$css = 'td{padding: 10px;text-align: center;}';//file_get_contents("http://localhost:666/tpl/styles/style_single.css");
$html = $_POST['data'];
$pdf->writeHTML($html, 1, true, 1, true, '');
// reset pointer to the last page
$pdf->lastPage();
$_POST['nm'] = str_replace(' ', '_', $_POST['nm']);
$pdf->Output($_POST['nm'].'ekp_report.pdf', 'I');*/
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="generated.docx"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    $styles = '<style>
    .rep_tab{
      border: 1px solid black;
      border-collapse: collapse;  
    }
    .rep_tab td, .rep_tab th{
      border: 1px solid black;
      padding: 10px;
    }
    .table_header{
      background: #f2f4f8;
    }
    .rep_tab .ftd{
      background: #fafafa;
    }
    .rep_tab .std{
      background: #feffff;
    }
    </style>';
    $body = iconv('utf-8', 'cp1251', str_replace("'", "", $_POST['data']));
    //$body = str_replace("'", "", $_POST['dt']);
    //print_r($styles."<table border='1' class='rep_tab'>".$body."</table>");
    print_r($styles."<table border='0' class='rep_tab'>".$body."</table>");
?>