<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Visita extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->model('visita_model',"VisitaModel");
		//ini_set('default_charset','UTF-8');		
	}
	
	function index($var = "")
	{
		$NUM_MATRICULA = "";
		if(isset($var['NUM_MATRICULA'])){
			$NUM_MATRICULA = $var['NUM_MATRICULA'];
		}
		if(isset($var['funcionario'])){
			$NUM_MATRICULA = $var['funcionario'][0]['NUM_MATRICULA'];
		}

		if($this->session->userdata('idUser')!= "")
		{
				
			$var['visitas'] = $this->VisitaModel->getVisitas("",$NUM_MATRICULA);
			$this->render('visita.php',$var);
		}
		else
		{
			redirect('login');	
		}
			
	}
	
	function manter(){
		$msn = "";
	
		if($_POST['COD_VISITA'] == ''){
			$retorno = $this->VisitaModel->incluir($_POST);
			if($retorno){
				$var['msn'] = 'msn1';
			}
		}else{
			$retorno = $this->VisitaModel->alterar($_POST);
			if($retorno){
				$var['msn'] = 'msn2';
			}
		}
		$this->index($var);
	}
	
	function editar(){
		$COD_VISITA = $this->uri->segment(3);
		$var['vis'] = $this->VisitaModel->getVisitas($COD_VISITA);
		$var['funcionario'] = $this->VisitaModel->getFuncionario($var['vis'][0]['NUM_MATRICULA']);
		$var['NUM_MATRICULA'] = $var['vis'][0]['NUM_MATRICULA'];
		$this->index($var);
	}
	
	function funcionario(){
		$var['funcionario'] = $this->VisitaModel->getFuncionario($this->uri->segment(3));
		$this->index($var);
	}
	
}