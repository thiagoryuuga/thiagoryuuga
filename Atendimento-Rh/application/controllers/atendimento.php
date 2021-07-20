<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Atendimento extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->model('atendimento_model',"AtendimentoModel");
		//ini_set('default_charset','UTF-8');		
	}
	
	function index($var = "")
	{
		
		if($this->session->userdata('idUser')!= "")
		{
				
			$var['atendimentos'] = $this->AtendimentoModel->getAtendimentos();
			$var['tipo'] = $this->AtendimentoModel->tipoAtendimento();
			$this->render('atendimento.php',$var);
		}
		else
		{
			redirect('login');	
		}
			
	}
	
	function manter(){
		$msn = "";
	
		if($_POST['COD_ATENDIMENTO'] == ''){
			$retorno = $this->AtendimentoModel->incluir($_POST);
			if($retorno){
				$var['msn'] = 'msn1';
			}
		}else{
			$retorno = $this->AtendimentoModel->alterar($_POST);
			if($retorno){
				$var['msn'] = 'msn2';
			}
		}
		$this->index($var);
	}
	
	function editar(){
		$COD_ATENDIMENTO = $this->uri->segment(3);
		$var['tipo'] = $this->AtendimentoModel->tipoAtendimento();
		$var['atend'] = $this->AtendimentoModel->getAtendimentos($COD_ATENDIMENTO);
		$var['funcionario'] = $this->AtendimentoModel->getFuncionario($var['atend'][0]['NUM_MATRICULA']);
		$var['atendimentos'] = $this->AtendimentoModel->getAtendimentos("",$var['atend'][0]['NUM_MATRICULA']);

		$this->render('atendimento.php',$var);
	}
	
	function funcionario(){
		$var['atendimentos'] = $this->AtendimentoModel->getAtendimentos("",$this->uri->segment(3));
		$var['tipo'] = $this->AtendimentoModel->tipoAtendimento();
		$var['funcionario'] = $this->AtendimentoModel->getFuncionario($this->uri->segment(3));
		$this->render('atendimento.php',$var);
	}

	function imprimir()
  {
	  	
     $param = $this->uri->segment('3');
	 $array['dados_consulta'] = $this->AtendimentoModel->pesquisa_atendimento($param);
	 $this->load->view('comprovante',$array);
  }
  function grava_impressao()
  {
	 $atendimento = $this->uri->segment('3');
	 $atendente = $this->uri->segment('4');
	 $this->AtendimentoModel->grava_impressao($atendimento,$atendente);
  }
	
}