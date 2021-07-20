<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$pat = str_replace('logistica','',getcwd());
require_once($pat.'ws/wsesmaltec.php');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
			
		$this->load->helper(array('form', 'url', 'funcoes'));
		$this->load->library(array('form_validation', 'session'));
		
	}
	
	function index()
	{
		$this->load->view('login');
	}
	
	function validSession()
	{
		
		$senha = $_POST['password'];
		$usuario = $_POST['user'];
		$sistema = $_POST['idSystem'];
		
		
		
		if(isset($usuario) || isset($senha)){
			$var['erro'] = true;
			
			$this->load->view('login', $var);
		} 
		
		$ws = new Wsesmaltec();
	    $result = $ws->autenticacao($usuario, $senha, $sistema);
		
				
		if($result['autenticacaoReturn']['msgErro'] == "" && $result['autenticacaoReturn']['nivel'] != "" ){
				
			$novosdados = array(
				//'idUser'  => $result['autenticacaoReturn']['usuario']['idUser'],
				'idUser' => $result['autenticacaoReturn']['usuario']['idRegister'],
				'idRegister' => $result['autenticacaoReturn']['usuario']['idRegister'],
				'Name' => $result['autenticacaoReturn']['usuario']['name'],
				'idDepartament' => $result['autenticacaoReturn']['usuario']['idDepartament'],
				'idHierarch' => $result['autenticacaoReturn']['usuario']['idHierarch'],
				'isBlocked' => $result['autenticacaoReturn']['blocked'],
				'idLevel' => $result['autenticacaoReturn']['nivel']
			);
			
					if($novosdados['idLevel'][0] == 0){
						$novosdados['idLevel'][0] = "G"; // Geral
					}
					if($novosdados['idLevel'][0] == 1){
						$novosdados['idLevel'][0] = "P"; //Portaria
					}
					if($novosdados['idLevel'][0] == 2){
						$novosdados['idLevel'][0] = "L"; //Logistica
					}
					if($novosdados['idLevel'][0] == 3){
						$novosdados['idLevel'][0] = "E"; //Expedic�o
					}
							
			$this->session->set_userdata($novosdados);
			redirect('inicio#page');
			
		}else{
			$var['msn'] = $result['autenticacaoReturn']['msgErro'];
			$var['erro'] = true;
			$this->load->view('login', $var);		
		}
		
	}
}