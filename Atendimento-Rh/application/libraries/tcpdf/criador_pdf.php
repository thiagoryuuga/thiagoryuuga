<?php
include('TCPDF.php');

$dados = $_POST['valor'];


 	 $pdf = new TCPDF();
	 $pdf->SetFont('helvetica', '', 8, '', true);
	 $pdf->AddPage();
	 $pdf->writeHTML("<table><tr align='center'><td>MATRICULA</td><td>NOME</td><td>SETOR</td><td>C. CUSTO</td></tr>
	 						 <tr align='center'><td>588101442</td><td>THIAGO DARLAN O. DOS SANTOS</td><td>TEC. DA INFORMACAO</td><td>13300011</td></tr>
						</table>
	 				  <br><br><br>
					  <p style='width:100%; margin-left:200px;'> HISTORICO DE ATESTADOS </p><br>".$dados);
	 $pdf->Output('teste.pdf', 'D');
	 header('Content-Type: application/pdf');
	header('Content-Disposition: attachment; filename=example.pdf');
	header('Pragma: no-cache');
	 