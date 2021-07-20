<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$pat = str_replace('atendimento','',getcwd());
require_once($pat.'ws/wsesmaltec.php');
class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->database();

	}
	
	function index()
	{
		$this->load->view('css_js');
		$this->load->view('login');
		
	}
	
	function validSession()
	{

		$senha = $_POST['password'];
		$usuario = $_POST['user'];
		$sistema = $_POST['idSystem'];
		
		$ws = new Wsesmaltec();
	    $result = $ws->autenticacao($usuario, $senha, $sistema);
		
				
		if($result['autenticacaoReturn']['msgErro'] == ""){
				
			$novosdados = array(
				'idUser'  => $result['autenticacaoReturn']['usuario']['idUser'],
				'idRegister' => $result['autenticacaoReturn']['usuario']['idRegister'],
				'Name' => $result['autenticacaoReturn']['usuario']['name'],
				'idDepartament' => $result['autenticacaoReturn']['usuario']['idDepartament'],
				'idHierarch' => $result['autenticacaoReturn']['usuario']['idHierarch'],
				'isBlocked' => $result['autenticacaoReturn']['blocked'],
				'idLevel' => $result['autenticacaoReturn']['nivel']
			);
		
			$this->session->set_userdata($novosdados);
			ini_set("session.gc_max.lifetime", "2000");
			//echo 'oi';
			redirect('inicio');
			
		}else{
			$var['msn'] = $result['autenticacaoReturn']['msgErro'];
			$this->load->view('login',$var);			
		}
	}
	
	function plataforma()
	{
		
		$frase = strtolower($_SERVER['HTTP_USER_AGENT']);
		$palavras = array ("mobile");
		$resultado = 0;
			  
			  foreach($palavras as $key => $value) {
				  $pos = strpos($frase, $value);
					  if ($pos !== false) { 
						$resultado = 1; 
						break; 
					  }
			  }
		
		return $resultado;

	}
	
	function destroy()
	{
		$this->session->sess_destroy();
		redirect('login/index');
	}
}