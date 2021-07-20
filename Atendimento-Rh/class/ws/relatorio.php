<?php 

header("Content-type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");     
//header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");  
header("Content-Disposition: attachment; filename=arquivo1.pdf");
//header("Content-Disposition: attachment; filename=arquivo1.xlsx");
//header("Content-Disposition: attachment; filename=arquivo1.docx");
header("Content-Transfer-Encoding: binary");
header('Expires: 0'); 	
header('Pragma: no-cache');

print_r($result);

?>

