<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipo extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->model('tipo_model',"TipoModel");		
	}
	
	function index($var = "")
	{
		
		if($this->session->userdata('idUser')!= "")
		{
			$var['tipos'] = $this->TipoModel->getTipos();
			$this->render('tipo.php',$var);
		}
		else
		{
			redirect('login');	
		}
			
	}
	
	function manter(){
		$msn = "";
	
		if($_POST['COD_TIPO_ATENDIMENTO'] == ''){
			$retorno = $this->TipoModel->incluir($_POST);
			if($retorno){
				$var['msn'] = 'msn1';
			}
		}else{
			$retorno = $this->TipoModel->alterar($_POST);
			if($retorno){
				$var['msn'] = 'msn2';
			}
		}
		$this->index($var);
	}
	
	function editar(){
		$COD_TIPO = $this->uri->segment(3);
		$var['tip'] = $this->TipoModel->getTipos($COD_TIPO);
		$this->index($var);
	}
	
}