<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ImportAgenda extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->library(array('session'));
		$this->load->library('Spreadsheet_Excel_Reader');
		$this->load->model('importagenda_model',"ImportAgendaModel");
		$this->load->model('agendamentomodel', "AgendamentoModel");
		//ini_set('default_charset','UTF-8');		
	}
	
	function index($var = "")
	{
		
		//echo $this->session->userdata('idUser');
		//die();
		$session_idUsuario = $this->session->userdata('idUser');
		if($session_idUsuario != "")
		{
				
			//$var['cargasAgendamento']= $this->AgendamentoModel->cargasAgendamento($id_carga = false);
			//$var['cargasAgendamentoDistribuicao']= $this->AgendamentoModel->cargasAgendamentoDistribuicao();
			//$this->load->view('agendamento_tabs',$var);
			redirect('agendamento');
		}
		else
		{
			redirect('login');	
		}
			
	}
	
	function importar(){
		
		
		
		$retorno = $this->ImportAgendaModel->importar($_FILES,$_POST);
		
		//echo $retorno;
		// $$retorno = true; somente para testes sem inserir dados no DB
		//$retorno = true;
		//die();
		$var = "";
				
		if($retorno == true){
			$var['msn'] = 'msn5';
		} else {
			$var['msn'] = 'msn6';
		}
		//$this->load->view('agendamento_tabs',$var);
		$this->index($var);
	}
	
	
	
}