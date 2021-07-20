<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ImportProduto extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->library(array('session'));
		$this->load->library('Spreadsheet_Excel_Reader');
		$this->load->library('Excel');
		$this->load->model('importProduto_model',"ImportProdutoModel");
		
		//ini_set('default_charset','UTF-8');		
	}
	
	function index($var = "")
	{
		
	
		$session_idUsuario = $this->session->userdata('idUser');
		if($session_idUsuario != "")
		{
				
			
			redirect('produto');
		}
		else
		{
			redirect('login');	
		}
			
	}
	
	function importar(){
		
		
		
		$retorno = $this->ImportProdutoModel->importar($_FILES,$_POST);
		
						
		if($retorno == true){
			$var['msn'] = 'msn5';
		} else {
			$var['msn'] = 'msn6';
		}
		
		$this->index($var);
	}
	
	
	
}