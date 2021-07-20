<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AcompFaltas extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->model('acompfaltas_model',"AcompFaltasModel");
		$this->load->model('atestado_model',"AtestadoModel");
		//ini_set('default_charset','UTF-8');		
	}
	
	function index($var = "")
	{
		
		if($this->session->userdata('idUser')!= "")
		{
				
			$acompFaltas = $this->AcompFaltasModel->getAcompFaltas();
			$medidaMatArray = array();
			foreach($acompFaltas as $row){
				$mat = $row['NUM_MATRICULA'];
				$medidaMatArray[$mat] = array();
				$qtdMedida = $this->AcompFaltasModel->historicoQtdMedidas(date('Y'),$mat);
				$medidaMatArray[$mat]['ADVERTENCIA'] = $medidaMatArray[$mat]['SUSPENSAO'] = 0;
				if(is_array($qtdMedida)){
					if(count($qtdMedida) > 0){
						if($_SERVER['REMOTE_ADDR'] == '192.168.194.140'){
							//Debug::dump($mat);
							//Debug::dump($qtdMedida);
							//echo '<hr>';
						}
						foreach($qtdMedida as $qtdMedidaRow){
							if($qtdMedidaRow['OQUE'] == 'A'){
								$medidaMatArray[$mat]['ADVERTENCIA'] = $qtdMedidaRow['TOTAL_FALTAS']; //$qtdMedida[0]['TOTAL_FALTAS']
							}else if($qtdMedidaRow['OQUE'] == 'S'){
								$medidaMatArray[$mat]['SUSPENSAO'] = $qtdMedidaRow['TOTAL_FALTAS']; //$qtdMedida[1]['TOTAL_FALTAS']
							}
						}
					}
				}
			}
			
			$var['acompFaltas'] = $acompFaltas;
			$var['medidaMatArray'] = $medidaMatArray;
			$this->render('acompFaltas.php',$var);
		}
		else
		{
			redirect('login');	
		}
			
	}
	
	function manter(){
		$msn = "";
	
		if($_POST['COD_ACOMP_FALTAS'] == ''){
			$retorno = $this->AcompFaltasModel->incluir($_POST);
			if($retorno){
				$var['msn'] = 'msn1';
			}
		}else{
			$retorno = $this->AcompFaltasModel->alterar($_POST);
			if($retorno){
				$var['msn'] = 'msn2';
			}
		}
		$this->index($var);
	}
	
	function editar(){
		$COD_ACOMP_FALTAS = $this->uri->segment(3);
		$var['acomp'] = $this->AcompFaltasModel->getAcompFaltas($COD_ACOMP_FALTAS);
		$var['historicoQtdFaltas'] = $this->AcompFaltasModel->historicoQtdFaltas($this->uri->segment(4),$var['acomp'][0]['NUM_MATRICULA']);
		$var['historicoDatasFaltas'] = $this->AcompFaltasModel->historicoDatasFaltas($this->uri->segment(4),$var['acomp'][0]['NUM_MATRICULA']);
		$var['historicoMedidas'] = $this->AcompFaltasModel->historicoMedidas($this->uri->segment(4),$var['acomp'][0]['NUM_MATRICULA']);
		$var['funcionario'] = $this->AcompFaltasModel->getFuncionario($this->uri->segment(4),$var['acomp'][0]['NUM_MATRICULA']);
		$var['faltas'] = $this->AcompFaltasModel->getFaltas($this->uri->segment(4), $var['acomp'][0]['NUM_MATRICULA']);
		$var['acompFaltas'] = $this->AcompFaltasModel->getAcompFaltas("",$var['acomp'][0]['NUM_MATRICULA']);
		$var['historicoAtestado'] = $this->AtestadoModel->getAtestado($var['acomp'][0]['NUM_MATRICULA']);
		
		$var['historicoQtdMedidas'] = $this->AcompFaltasModel->historicoQtdMedidas($this->uri->segment(4),$var['acomp'][0]['NUM_MATRICULA']);
		
		$mat = $var['acomp'][0]['NUM_MATRICULA'];
		$medidaMatArray[$mat] = array();
		$qtdMedida = $this->AcompFaltasModel->historicoQtdMedidas(date('Y'),$mat);
		$medidaMatArray[$mat]['ADVERTENCIA'] = $medidaMatArray[$mat]['SUSPENSAO'] = 0;
		if(is_array($qtdMedida)){
			if(count($qtdMedida) > 0){
				if($_SERVER['REMOTE_ADDR'] == '192.168.194.140'){
					//Debug::dump($mat);
					//Debug::dump($qtdMedida);
					//echo '<hr>';
				}
				foreach($qtdMedida as $qtdMedidaRow){
					if($qtdMedidaRow['OQUE'] == 'A'){
						$medidaMatArray[$mat]['ADVERTENCIA'] = $qtdMedidaRow['TOTAL_FALTAS']; //$qtdMedida[0]['TOTAL_FALTAS']
					}else if($qtdMedidaRow['OQUE'] == 'S'){
						$medidaMatArray[$mat]['SUSPENSAO'] = $qtdMedidaRow['TOTAL_FALTAS']; //$qtdMedida[1]['TOTAL_FALTAS']
					}
				}
			}
		}
		$var['medidaMatArray'] = $medidaMatArray;
		$this->render('acompFaltas.php',$var);
	}
	
	function deletar(){
		$COD_ACOMP_FALTAS = $this->uri->segment(3);
		$retorno = $this->AcompFaltasModel->deletar($COD_ACOMP_FALTAS);
		if($retorno){
				$var['msn'] = 'msn3';
		}
		$this->index($var);
	}
	
	function funcionario(){
		
		//$periodo = $this->uri->segment(4);
		//print_r($periodo);
		//die();
		$verificFaltas = $this->AcompFaltasModel->verificFaltas($this->uri->segment(3),$this->uri->segment(4));
		if($verificFaltas->num_rows() > 0){
			$var['msn'] = "msn8";
		}
		
		$var['acompFaltas'] = $this->AcompFaltasModel->getAcompFaltas("",$this->uri->segment(3));
		
		$var['faltas'] = $this->AcompFaltasModel->getFaltas($this->uri->segment(4), $this->uri->segment(3));
		$var['tot_faltas'] = $this->AcompFaltasModel->getFaltas("", $this->uri->segment(3));
		$var['historicoQtdFaltas'] = $this->AcompFaltasModel->historicoQtdFaltas($this->uri->segment(4),$this->uri->segment(3));
		$var['historicoDatasFaltas'] = $this->AcompFaltasModel->historicoDatasFaltas($this->uri->segment(4),$this->uri->segment(3));
		$var['historicoMedidas'] = $this->AcompFaltasModel->historicoMedidas($this->uri->segment(4),$this->uri->segment(3));
		$var['funcionario'] = $this->AcompFaltasModel->getFuncionario(utf8_decode($this->uri->segment(4)),$this->uri->segment(3));
		$var['historicoAtestado'] = $this->AtestadoModel->getAtestado($this->uri->segment(3));
		
		$var['historicoQtdMedidas'] = $this->AcompFaltasModel->historicoQtdMedidas($this->uri->segment(4),$this->uri->segment(3));
		
		$mat = $this->uri->segment(3);
		$medidaMatArray[$mat] = array();
		$qtdMedida = $this->AcompFaltasModel->historicoQtdMedidas(date('Y'),$mat);
		$medidaMatArray[$mat]['ADVERTENCIA'] = $medidaMatArray[$mat]['SUSPENSAO'] = 0;
		if(is_array($qtdMedida)){
			if(count($qtdMedida) > 0){
				if($_SERVER['REMOTE_ADDR'] == '192.168.194.140'){
					//Debug::dump($mat);
					//Debug::dump($qtdMedida);
					//echo '<hr>';
				}
				foreach($qtdMedida as $qtdMedidaRow){
					if($qtdMedidaRow['OQUE'] == 'A'){
						$medidaMatArray[$mat]['ADVERTENCIA'] = $qtdMedidaRow['TOTAL_FALTAS']; //$qtdMedida[0]['TOTAL_FALTAS']
					}else if($qtdMedidaRow['OQUE'] == 'S'){
						$medidaMatArray[$mat]['SUSPENSAO'] = $qtdMedidaRow['TOTAL_FALTAS']; //$qtdMedida[1]['TOTAL_FALTAS']
					}
				}
			}
		}
		$var['medidaMatArray'] = $medidaMatArray;
		
		$this->render('acompFaltas.php',$var);
		
	}
	
	function supervisor(){
		$super = $_POST['su'];
		$supervisor = $this->AcompFaltasModel->getSupervisor($super);
		$arr = array();
		foreach($supervisor as $row)
		{
			$arr[] = $row['Name']." - ".$row['idRegister'];
		}
		//$arr1 = array($term, 'item1', 'item2', 'item3');
        echo json_encode($arr);
		//echo json_encode($super);
	}
	
	function lider(){
		$lid = $_POST['lid'];
		$lider = $this->AcompFaltasModel->getLider($lid);
		$arr = array();
		foreach($lider as $row)
		{
			$arr[] = trim($row['NOM_COMPLETO'])." - ".$row['NUM_MATRICULA'];
		}
		//$arr1 = array($term, 'item1', 'item2', 'item3');
        echo json_encode($arr);
	}
	
	function verificaEmailsPendentes(){
		$periodo = $_POST['PERIODO_FAL'];
		echo $this->AcompFaltasModel->numEntrevistasPendentes($periodo);
	}
	
	function enviarEmail(){
		$periodo = $_POST['PERIODO_FAL'];
		$this->AcompFaltasModel->enviarEmail($periodo);
		echo "Email enviado com Sucesso!";
	}
	
	function email(){
		$this->load->view('css_js.php');
		$this->load->view('email.php');
	}
	
}