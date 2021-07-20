<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ImportFaltas extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->library('Spreadsheet_Excel_Reader');
		$this->load->model('importfaltas_model',"ImportFaltasModel");
		//ini_set('default_charset','UTF-8');		
	}
	
	function index($var = "")
	{
		
		if($this->session->userdata('idUser')!= "")
		{
				
			//$var['atendimentos'] = $this->AtendimentoModel->getAtendimentos();
			//$var['tipo'] = $this->AtendimentoModel->tipoAtendimento();
			$this->render('importFaltas.php',$var);
		}
		else
		{
			redirect('login');	
		}
			
	}
	
	function importar(){
		$retorno = $this->ImportFaltasModel->importar($_FILES,$_POST);
		$var = "";
				
		if($retorno == true){
			$var['msn'] = 'msn5';
		}
		$this->index($var);
	}
	
	
	
}