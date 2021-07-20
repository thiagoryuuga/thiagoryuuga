<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acidente extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->model('acidente_model',"AcidenteModel");
		//ini_set('default_charset','UTF-8');		
	}
	
	function index($var = "")
	{
		
		if($this->session->userdata('idUser')!= "")
		{
				
			$var['acidentes'] = $this->AcidenteModel->getacidentes();
			$this->render('acidente.php',$var);
		}
		else
		{
			redirect('login');	
		}
			
	}
	
	function manter(){
		$msn = "";
		//print_r($_POST);
	    //die();
		
		if($_POST['COD_ATEND_SOCIAL'] == ''){
			$retorno = $this->AcidenteModel->incluir($_POST);
			if($retorno){
				$var['msn'] = 'msn1';
			}
		}else{
			$retorno = $this->AcidenteModel->alterar($_POST);
			if($retorno){
				$var['msn'] = 'msn2';
			}
		}
		$this->index($var);
	}
	
	function editar(){
		$COD_ATEND_SOCIAL = $this->uri->segment(3);
		$var['aciden'] = $this->AcidenteModel->getacidentes($COD_ATEND_SOCIAL);
		$var['acompanhamentos'] = $this->AcidenteModel->getAcompanhamentos($COD_ATEND_SOCIAL);
		$var['funcionario'] = $this->AcidenteModel->getFuncionario($var['aciden'][0]['NUM_MATRICULA']);
		$var['acidentes'] = $this->AcidenteModel->getacidentes("",$var['aciden'][0]['NUM_MATRICULA']);

		$this->render('acidente.php',$var);
	}
	
	function editar_acomp(){
		$COD_ACOMPANHAMENTO = $this->uri->segment(3);
		$var['acom'] = $this->AcidenteModel->getAcompanhamentos("",$COD_ACOMPANHAMENTO,"");
		$var['aciden'] = $this->AcidenteModel->getacidentes($var['acom'][0]['COD_ATEND_SOCIAL']);
		$var['funcionario'] = $this->AcidenteModel->getFuncionario($var['acom'][0]['NUM_MATRICULA']);
		$var['acidentes'] = $this->AcidenteModel->getacidentes("",$var['acom'][0]['NUM_MATRICULA']);
		$var['acompanhamentos'] = $this->AcidenteModel->getAcompanhamentos($var['acom'][0]['COD_ATEND_SOCIAL']);
		$var['medicamentos'] = $this->AcidenteModel->getMedicamentos($COD_ACOMPANHAMENTO);
		$this->render('acidente.php',$var);
	}
	
	function funcionario(){
		$var['acidentes'] = $this->AcidenteModel->getacidentes("",$this->uri->segment(3));
		$var['funcionario'] = $this->AcidenteModel->getFuncionario($this->uri->segment(3));
		$this->render('acidente.php',$var);
	}
	
}