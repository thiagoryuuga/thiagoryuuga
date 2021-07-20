<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Funcionario extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->model('funcionario_model',"FuncionarioModel");
		//ini_set('default_charset','UTF-8');		
	}
	
	function index($var = "")
	{
		if($this->session->userdata('idUser')!= "")
		{
			$this->load->view('css_js.php');
			$this->load->view('funcionario.php',$var);
		}
		else
		{
			redirect('login');	
		}
			
	}
	

	function pesquisar(){
		$var['funcionario'] = $this->FuncionarioModel->getFuncionario($_POST);
		$var['tela'] = $_POST['tela'];
		$var['periodo'] = $_POST['periodo'];
		$this->load->view('css_js.php');
		$this->load->view('funcionario.php',$var);
	}
	
	
}