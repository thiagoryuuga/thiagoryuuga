<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function checkLogin($email) {
	// Checa se o usuário está logado, caso não esteja devolve pra página de login
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

function sqlToDataHora($data)
{
	$nova_data = explode (" ", $data);
	$hora = $nova_data[1];
	$dataSql = $nova_data[0];
	$dataFormatada = explode ("-", $dataSql);
	return $dataFormatada[2]."/".$dataFormatada[1]."/".$dataFormatada[0].", as ".$hora;
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

function checkSessao($perfil) {
	// Checa se o usuário está logado, caso não esteja devolve pra página de login
	$CI =& get_instance();
	
	$session_login = $CI->session->userdata('login');
	if (!$session_login) {
		$backTo = array('backTo' => uri_string());
		$CI->session->set_userdata($backTo);
		redirect('login', 'location');
	}
	
	$codigos = $CI->session->userdata('codigos');
	if (!$codigos[$perfil]) {
		echo "VocÃª nÃ£o tem permissÃ£o para acessar este conteÃºdo";
		die();
	}
	
}



?>