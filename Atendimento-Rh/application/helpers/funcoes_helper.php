<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function checkLogin($email) {
	// Checa se o usu�rio est� logado, caso n�o esteja devolve pra p�gina de login
	$CI =& get_instance();
	
	if (!$email) {
		$backTo = array('backTo' => uri_string());
		$CI->session->set_userdata($backTo);
		redirect('login', 'location');
	}
}

function sqlToDate($data)
{
	$nova_data = implode(preg_match("~\/~", $data) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data) == 0 ? "-" : "/", $data)));
	return $nova_data;
}

function toDate($data)
{
	if(strrpos($data, '-'))
	{
		$data2 = explode("-",$data);
		$data = $data2[2]."/".$data2[1]."/".$data2[0];
	}
	return $data;
}

function toInt($string){
	$int = $string * 1;
	return $int;
}

function getClob($clob) 
{ 
	$str ="";
   if($clob != NULL){
   	$str = $clob->read($clob->size()); 
   }
   return $str; 
} 

function monthName($mes){
	$meses = array('01' => 'Janeiro', 
				   '02' => 'Fevereiro', 
				   '03' => 'Marco', 
				   '04' => 'Abril', 
				   '05' => 'Maio', 
				   '06' => 'Junho', 
				   '07' => 'Julho',
				   '08' => 'Agosto',
				   '09' => 'Setembro',
				   '10' => 'Outubro',
				   '11' => 'Novembro',
				   '12' => 'Dezembro');
	return $meses[$mes];	
}

function monthNumber($mes){
	$meses = array('Janeiro' 	=> '01', 
				   'Fevereiro'  => '02', 
				   'Marco' 	  => '03', 
				   'Abril' 	  => '04', 
				   'Maio' 	   => '05', 
				   'Junho' 	  => '06', 
				   'Julho' 	  => '07',
				   'Agosto' 	 => '08',
				   'Setembro'   => '09',
				   'Outubro' 	=> '10',
				   'Novembro'   => '11',
				   'Dezembro'   => '12');
	return $meses[$mes];	
}

function calculaData($data,$operacao, $dias = 0, $meses = 0, $ano = 0) {  

//informe a data no formato dd/mm/yyyy   

	$data = explode("/", $data);   

	if ($operacao == '+') {

		$novaData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses, $data[0] + $dias, $data[2] + $ano) );

	} else {

	$novaData = date("d/m/Y", mktime(0, 0, 0, $data[1] - $meses,$data[0] - $dias, $data[2] - $ano) );

	}   
	return $novaData;

}

function mesAno($data){
	$data = explode("/", $data);
	$mes = monthName($data[1]);
	$ano = $data[2];
	return $mes.'/'.$ano;
}

 

function checkSessao($perfil) {
	// Checa se o usu�rio est� logado, caso n�o esteja devolve pra p�gina de login
	$CI =& get_instance();
	
	$session_login = $CI->session->userdata('login');
	if (!$session_login) {
		$backTo = array('backTo' => uri_string());
		$CI->session->set_userdata($backTo);
		redirect('login', 'location');
	}
	
	$codigos = $CI->session->userdata('codigos');
	if (!$codigos[$perfil]) {
		echo "Voc� n�o tem permiss�o para acessar este conte�do";
		die();
	}
	
}



?>