<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto extends CI_Controller {

	function __construct(){
	
		parent::__construct();
		
		$this->load->helper(array('form', 'url', 'funcoes', 'download'));		
		$this->load->library(array('form_validation','session'));		
		$this->load->model('produtomodel', "ProdutoModel");
		
			
	}
	
	public function index($msg = false) //$paraments = false
	{
					
		$var['cadastrados']=$this->ProdutoModel->ProdutosCadastrados();
		$this->load->view('produto_tabs',$var);
		
	}
	
	// Funções para o sistema de Agendamento/Programação

	
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */