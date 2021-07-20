<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Afastamento extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->model('afastamento_model',"AfastamentoModel");
		//ini_set('default_charset','UTF-8');		
	}
	
	function index()
	{
		
		if($this->session->userdata('idUser') != "")
		{	
			$COD_ATEND_SOCIAL = $this->uri->segment(3);
			$var['afastamentos'] = $this->AfastamentoModel->getAfastamentos($COD_ATEND_SOCIAL);
			if($var['afastamentos'] != array()){
				$var['pericias'] = $this->AfastamentoModel->getPericias($var['afastamentos'][0]['COD_AFASTAMENTO']);
			}//print_r($var['afastamentos']);*/
			$var['funcionario'] = $this->AfastamentoModel->getFuncionario($COD_ATEND_SOCIAL);
			$this->render('afastamento.php',$var);
		}
		else
		{
			redirect('login');	
		}
			
	}
	
	function manter(){
		$msn = "";
	
		if($_POST['COD_AFASTAMENTO'] == ''){
			$retorno = $this->AfastamentoModel->incluir($_POST);
			if($retorno){
				$var['msn'] = 'msn1';
			}
		}else{
			$retorno = $this->AfastamentoModel->alterar($_POST);
			if($retorno){
				$var['msn'] = 'msn2';
			}
		}
		
		$var['funcionario'] = $this->AfastamentoModel->getFuncionario($_POST['COD_ATEND_SOCIAL']);
		$var['afastamentos'] = $this->AfastamentoModel->getAfastamentos($_POST['COD_ATEND_SOCIAL']);
		if($var['afastamentos'] != array()){
				$var['pericias'] = $this->AfastamentoModel->getPericias($var['afastamentos'][0]['COD_AFASTAMENTO']);
		}
		$var['COD_ATEND_SOCIAL'] = $_POST['COD_ATEND_SOCIAL'];
		$this->render('afastamento.php',$var);
		
	}
	
	function editar(){
		$COD_ATENDIMENTO = $this->uri->segment(3);
		$var['atend'] = $this->AfastamentoModel->getAfastamentos($COD_ATENDIMENTO);
		$var['funcionario'] = $this->AfastamentoModel->getFuncionario($var['atend'][0]['NUM_MATRICULA']);
		$var['afastamentos'] = $this->AfastamentoModel->getAfastamentos("",$var['atend'][0]['NUM_MATRICULA']);

		$this->render('afastamento.php',$var);
	}
	
	function funcionario(){
		$var['afastamentos'] = $this->AfastamentoModel->getAfastamentos("",$this->uri->segment(3));
		$var['funcionario'] = $this->AfastamentoModel->getFuncionario($this->uri->segment(3));
		$this->render('afastamento.php',$var);
	}
	
}