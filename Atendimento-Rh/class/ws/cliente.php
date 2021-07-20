<?php 	
require_once('wsesmaltec.php');
$ws = new Wsesmaltec();
$paramRelat = array('ano:2012', 'desMes:Junho', 'periodoFalta:Junho/2012', 'periodo:06/2012');
$ws->gerarRelatorio('oracle','teste','LGXPRD','logix','logix','historico_faltas','pdf',$paramRelat);
/*
$retorno = $ws->autenticacao('933004432', 'esmaltec', '46');
print_r($retorno);*/
?>