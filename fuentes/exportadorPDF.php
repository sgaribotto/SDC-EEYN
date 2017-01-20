<?php
	require '../libs/tcpdf/tcpdf.php';
	
	include 'modeloprograma.php';
	
	$html = "<h1>itle</h1>";
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->writeHTML($html, true, false, true, false, '');
?>
