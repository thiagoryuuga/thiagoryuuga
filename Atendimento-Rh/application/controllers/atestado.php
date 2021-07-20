<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Atestado extends MY_Controller {

  function __construct() {
    parent::__construct();

    $this->load->database();
    $this->load->model('atestado_model', "AtestadoModel");
	
    //ini_set('default_charset','UTF-8');		
  }

  function index($var = "") {

    if ($this->session->userdata('idUser') != "") {

      //$var['acompFaltas'] = $this->AcompFaltasModel->getAcompFaltas();
      $this->render('atestado.php', $var);
    } else {
      redirect('login');
    }
  }

  function funcionario() {

    try {
      if (!isset($_POST["NUM_MATRICULA"])) {
        throw new Exception('Campo da matrícula está inválido!');
      }
      if (empty($_POST["NUM_MATRICULA"])) {
        throw new Exception('Matrícula não pode estar vazio!');
      }
      $matricula = $_POST["NUM_MATRICULA"];
      if (substr($matricula, 0, 3) == '588') {
        $matricula = substr_replace($matricula, '31', 0, 3);
      }
      $funcionario = $this->AtestadoModel->getFuncionario($matricula);

      if (count($funcionario) == 0) {
        throw new Exception('Funcionário não encontrado!');
      }
      $historicoAtestado = $this->AtestadoModel->getAtestado($matricula);
      $faltas = $this->AtestadoModel->getFaltas($matricula);
      $var['tot_faltas'] = $this->AtestadoModel->getFaltas($matricula);
      $var['historicoQtdFaltas'] = $this->AtestadoModel->historicoQtdFaltas($matricula);
      $var['historicoDatasFaltas'] = $this->AtestadoModel->historicoDatasFaltas($matricula);
      $var['historicoMedidas'] = $this->AtestadoModel->historicoMedidas($matricula);
      $var['funcionario'] = $funcionario;
      $var['historicoAtestado'] = $historicoAtestado;
      $var['faltas'] = $faltas;
    } catch (Exception $ex) {
      $var['erro'] = $ex->getMessage();
    }



    $this->render('atestado.php', $var);
  }

  function supervisor() {
    $super = $_POST['su'];
    $supervisor = $this->AcompFaltasModel->getSupervisor($super);
    $arr = array();
    foreach ($supervisor as $row) {
      $arr[] = $row['Name'] . " - " . $row['idRegister'];
    }
    //$arr1 = array($term, 'item1', 'item2', 'item3');
    echo json_encode($arr);
    //echo json_encode($super);
  }

  function lider() {
    $lid = $_POST['lid'];
    $lider = $this->AcompFaltasModel->getLider($lid);
    $arr = array();
    foreach ($lider as $row) {
      $arr[] = trim($row['NOM_COMPLETO']) . " - " . $row['NUM_MATRICULA'];
    }
    //$arr1 = array($term, 'item1', 'item2', 'item3');
    echo json_encode($arr);
  }

  function verificaEmailsPendentes() {
    $periodo = $_POST['PERIODO_FAL'];
    echo $this->AcompFaltasModel->numEntrevistasPendentes($periodo);
  }

  function enviarEmail() {
    $periodo = $_POST['PERIODO_FAL'];
    $this->AcompFaltasModel->enviarEmail($periodo);
    echo "Email enviado com Sucesso!";
  }

  function email() {
    $this->load->view('css_js.php');
    $this->load->view('email.php');
  }
  
  function gerarPDF()
  {
	  
	 
	  //$this->load->helper('pdf');;
	  //$dados = $_POST['valor'];
	  //print_r($dados);
	  //$this->AtestadoModel->gerarPDF($dados);
	  
  }

}
