<?php
require('fpdf.php');

/*$dados = $_POST['valor'];

 	 $pdf = new TCPDF();
	 $pdf->SetFont('helvetica', '', 14, '', true);
	 $pdf->AddPage();
	 $pdf->writeHTML($dados);
	 $pdf->Output('teste.pdf', 'I');

	exit();*/
	
	$pdf=new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('helvetica','B',16);
	$pdf->Write($dados);
	$pdf->Output();
	//$pdf->Cell(40,10,'Hello World!');
	
	//header('Content-type: application/pdf');
    //header('Content-Disposition: attachment; filename="file.pdf"');
	

	
	
	
?>