<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class Cep extends CI_Controller {

	//php 5 constructor
	function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}
	
	public function getCidades($id) {
		
		$this->load->model('cidades_model');
		
		$cidades = $this->cidades_model->getCidades($id);
		//echo $this->db->last_query();
		if( empty ( $cidades ) ) 
			return '{ "NOME": "Nenhuma cidade encontrada" }';
			
		$arr_cidade = array();

		foreach ($cidades as $cidade) {
			
			$arr_cidade[] = '{"ID":' . $cidade->ID . ',"NOME":"' . $cidade->NOME . '"}';
				
		}
		
		echo '[ ' . implode(",",$arr_cidade) . ']';
		
		return;
		
	}
	
	function index() {
		
		$this->load->model('cidades_model');
		$dados['title']   = 'Exemplo de Combos Depe';
		$dados['estados'] = $this->cidades_model->getEstados();
		$this->load->view('teste_cep', $dados);
		
	}

}