<?php 	
require_once('wsesmaltec2.php');
$ws = new Wsesmaltec();
	
$paramRelat = array('ano:2012', 'desMes:Junho', 'periodoFalta:Junho/2012', 'periodo:06/2012');
	
$result = $ws->gerarRelatorio('oracle','teste','LGXPRD','logix','logix','historico_faltas','pdf',$paramRelat);

//header("Content-type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");     
//header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");  
header("Content-Disposition: attachment; filename=arquivo1.pdf");
//header("Content-Disposition: attachment; filename=arquivo1.xlsx");
//header("Content-Disposition: attachment; filename=arquivo1.docx");
header("Content-Transfer-Encoding: binary");
header('Expires: 0'); 	
header('Pragma: no-cache'); 

echo base64_decode($result);
	
?>