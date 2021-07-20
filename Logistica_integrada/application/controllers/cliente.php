<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente extends CI_Controller {

	
	 
	function __construct(){
	
		parent::__construct();
		
		$this->load->helper(array('form', 'url', 'funcoes'));	
		$this->load->library(array('form_validation','session'));			
		$this->load->model('clientemodel',"ClienteModel");

		
	}
	
	
	public function index()
	{
	
		$var['clientes'] = $this->ClienteModel->clienteTodos();
		
		$this->load->view('tab_cliente', $var);
	
	}
	
	public function cadastrar (){
		
		$this->form_validation->set_rules('CGCCPF_CLIENTE', 'CNPJ/CPF', 'required');
		$this->form_validation->set_rules('NOME_CLIENTE', 'Nome do Cliente', 'required');
		
		
		$clientePost['row'] = $_POST;
		$clientePost['clientes'] = $this->ClienteModel->clienteTodos();
		
		if ($this->form_validation->run() == FALSE){
			$this->load->view('tab_cliente', $clientePost);
		} else {
				
			$cliente = array(
					'CGCCPF_CLIENTE' => $this->input->post("CGCCPF_CLIENTE"),				
					'NOME_CLIENTE' => $this->input->post("NOME_CLIENTE"),				
					//'USUARIO_PORTARIA' => $this->session->userdata('idUser'),				
			);
			
			
			$idcliente = $this->input->post('ID_CLIENTE');
			if($idcliente){
				$this->ClienteModel->update($idcliente, $cliente);	
				$var['msgOK'] = "Cliente alterado com sucesso!";
			} else {
				$this->ClienteModel->insert($cliente);
				$var['msgOK'] = "Cliente cadastrado com sucesso!";
			}
			
			$var['clientes'] = $this->ClienteModel->clienteTodos();
			
			$this->load->view('tab_cliente', $var);	
		}
	
	}	
	
	public function detalhar($idcliente)
	{		
	
		if($idcliente){							
			$var['row'] = $this->ClienteModel->detalhar($idcliente);
		} 	
		$var['clientes']= $this->ClienteModel->clienteTodos();
		$this->load->view('tab_cliente', $var);
			
	}
	
	
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */