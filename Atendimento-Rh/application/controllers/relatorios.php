<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');
$pat = str_replace('atendimento', '', getcwd());
require_once($pat . 'ws/wsesmaltec.php');

class Relatorios extends MY_Controller {

  function __construct() {
    parent::__construct();

    $this->load->database();
    $this->load->model('relatorios_model', "RelatoriosModel");
    $this->load->model('atestado_model', "AtestadoModel");
    //ini_set('default_charset','UTF-8');		
  }

  function index() {

    if ($this->session->userdata('idUser') != "") {
      $var['gerencia'] = $this->RelatoriosModel->getGerencia();
      $this->render('relatorios.php', $var);
    } else {
      redirect('login');
    }
  }

  function atestado() {
    try {
      if (!isset($_POST["num_matricula"])) {
        $matricula = "";
      } else {
        $matricula = $_POST["num_matricula"];
      }




      $periodo = isset($_POST["PERIODO"]) ? $_POST["PERIODO"] : "";


      //$funcionario = $this->AtestadoModel->getFuncionario($matricula);

      if ($periodo == "") {
        throw new Exception('Funcion�rio n�o encontrado!');
      }
      $historicoAtestado = $this->AtestadoModel->getAtestadoPorPeriodo($periodo);
      if (!is_array($historicoAtestado)) {
        throw new Exception($historicoAtestado);
      }
      if (count($historicoAtestado) == 0) {
        throw new Exception('N�o foi encontrado nenhum funcion�rio com atestado neste per�odo');
      }

      $nomeArquivo = 'relat-atestado-' . $periodo . '.xls';
      $pagina = header("Content-type: application/msexcel;");
      $pagina .= header("Content-Disposition: attachment; filename=$nomeArquivo");
      $pagina .= utf8_decode("
						<h3>Relat�rio de Atestado</h3>
						<table border='1'>
							<tr style='font-weight:bold;'>
								<td bgcolor='#999999'>Matricula</td>
								<td bgcolor='#999999'>Funcionario</td>
								<td bgcolor='#999999'>Setor</td>
								<td bgcolor='#999999'>Ger�ncia</td>
								<td bgcolor='#999999'>Janeiro</td>
								<td bgcolor='#999999'>Fevereiro</td>
								<td bgcolor='#999999'>Mar�o</td>
								<td bgcolor='#999999'>Abril</td>
								<td bgcolor='#999999'>Maio</td>
								<td bgcolor='#999999'>Junho</td>
								<td bgcolor='#999999'>Julho</td>
								<td bgcolor='#999999'>Agosto</td>
								<td bgcolor='#999999'>Setembro</td>
								<td bgcolor='#999999'>Outubro</td>
								<td bgcolor='#999999'>Novembro</td>
								<td bgcolor='#999999'>Dezembro</td>
								<td bgcolor='#999999'>Total</td>
							</tr>");


      foreach ($historicoAtestado as $historicoAtestadoKey => $row) {
        $objeto = $row['objeto'][0];
        $historico = $row['atestado'];

        $array = array();
        $total = 0;
        for ($i = 1; $i <= 12; $i++) {
          $array[$i] = 0;
        }
        foreach ($historico as $row1) {
          $mes = $row1['MES'];
          $qtd = $row1['QTD'];
          $array[$mes] = $qtd;
          $total += $qtd;
        }

        $pagina .= "<tr>
								<td>" . $objeto['NUM_MATRICULA'] . "</td>
								<td>" . $objeto['NOM_COMPLETO'] . "</td>
								<td>" . $objeto['DEN_UNI_FUNCIO'] . "</td>
								<td>" . $objeto['DEN_ESTR_LINPROD'] . "</td>";
        $j = 0;
        for ($i = 1; $i <= 12; $i++) {
          $pagina .= "<td>" . $array[$i] . "</td>";
        }
        $pagina .= "<td>" . $total . "</td></tr>";
      }
      $pagina .= "</table>";
      echo $pagina;
    } catch (Exception $ex) {
      $var['erro'] = $ex->getMessage();
      echo '<script>window.alert("' . $var["erro"] . '")</script>';
      $this->render('relatorios.php', $var);
    }
  }

  function atendimento() {
    $atendiemntos = $this->RelatoriosModel->atendimento($_POST);
    $pagina = header("Content-type: application/msexcel;");
    $pagina .= header("Content-Disposition: attachment; filename=Atendimento.xls");
    $pagina .= utf8_decode("<table border='1'>
						<tr style='font-weight:bold;'>
							<td bgcolor='#999999'>Matricula</td>
							<td bgcolor='#999999'>Funcionario</td>
							<td bgcolor='#999999'>Setor</td>
							<td bgcolor='#999999'>Cargo</td>
							<td bgcolor='#999999'>Data Inicio</td>
							<td bgcolor='#999999'>Tipo</td>
							<td bgcolor='#999999'>Assunto</td>
							<td bgcolor='#999999'>Observa�ões</td>
							<td bgcolor='#999999'>Status</td>
							<td bgcolor='#999999'>Data Final</td>
							<td bgcolor='#999999'>Atendente</td>
						</tr>");


    foreach ($atendiemntos as $row) {
      $pagina .= "<tr>
							<td>" . $row['MATRICULA'] . "</td>
							<td>" . $row['FUNCIONARIO'] . "</td>
							<td>" . $row['SETOR'] . "</td>
							<td>" . $row['CARGO'] . "</td>
							<td>" . $row['DATA_INICIO'] . "</td>
							<td>" . $row['TIPO'] . "</td>
							<td>" . getClob($row['ASSUNTO']) . "</td>
							<td>" . getClob($row['OBSERVACOES']) . "</td>
							<td>" . utf8_decode($row['STATUS']) . "</td>
							<td>" . $row['DATA_FINAL'] . "</td>
							<td>" . $row['ATENDENTE'] . "</td>
						</tr>";
    }
    $pagina .= "</table>";
    echo $pagina;
  }

  /* function visRelatAbsen(){
    echo $this->load->view('css_js.php');
    echo $this->load->view('relatAbsent.php');
    } */

  function absenteismo() {

    $periodo = $_POST['PERIODO'];

    $per = explode('/', $periodo);

    $ws = new Wsesmaltec();
    $paramRelat = array('ano:' . $per[1] . '', 'desMes:' . $per[0] . '', 'periodoFalta:' . $periodo . '', 'periodo:' . monthNumber($per[0]) . '/' . $per[1] . '');
    $ws->gerarRelatorio('oracle', 'prod', 'LGXPRD', 'logix', 'logix', 'historico_faltas', 'pdf', $paramRelat);
  }

  function faltas() {

    $periodo = $_POST['PERIODO'];
    $per = explode('/', $periodo);

    $dini = "";
    $dfim = "";

    if ($_POST['tipo'] == "A") {
      $periodo = $per[1];
      $dini = "11/12/" . ($per[1] - 1);
      $dfim = "10/" . monthNumber($per[0]) . "/" . $per[1];
    } else if ($_POST['tipo'] == "P") {
      $mes = '12';
      $ano = ($per[1] - 1);

      if (monthNumber($per[0]) > 1) {
        $mes = str_pad((monthNumber($per[0]) - 1), 2, "0", STR_PAD_LEFT);
        $ano = $per[1];
      }
      $dini = "11/" . $mes . "/" . $ano;
      $dfim = "10/" . monthNumber($per[0]) . "/" . $per[1];
    }

    //print_r($_POST['gerencia']);
    //die();


    $ws = new Wsesmaltec();
    $paramRelat = array('dtInicial:' . $dini . '', 'dtFinal:' . $dfim . '', 'periodo:' . $periodo . '', 'tipoRel:' . $_POST['tipo'] . '', 'gerencia:' . trim($_POST['gerencia']) . '');
    print_r($ws->gerarRelatorio('oracle', 'prod', 'LGXPRD', 'logix', 'logix', 'detalhamento_faltas_nao_justificadas', 'xls', $paramRelat));
  }

  function gerarGrafico() {

    $periodo = $_POST['PERIODO'];

    $ws = new Wsesmaltec();
    $paramRelat = array('periodo:' . $periodo . '');
    print_r($ws->gerarRelatorio('oracle', 'prod', 'LGXPRD', 'logix', 'logix', 'grafico_motivo_faltas', 'pdf', $paramRelat));
  }
  
  function atestado2() {
    try {
      
      
      $data_inicio = isset($_POST["data_inicio"]) ? $_POST["data_inicio"] : "01/01/2014";
      $data_fim = isset($_POST["data_fim"]) ? $_POST["data_fim"] : date('d/m/Y');
      
      //echo '<script>window.alert("' . $data_inicio . '")</script>';
      //echo '<script>window.alert("' . $data_fim . '")</script>';
     
      $demitido = isset($_POST["demitido"]) ? in_array($_POST["demitido"], array('sim','nao')) ? $_POST["demitido"] : 'sim': 'sim';
      $afastamento = isset($_POST["afastamento"]) ? in_array($_POST["afastamento"], array('0','20', '21', '24')) ? $_POST["afastamento"] : '0': '0';

      $data_inicio_objeto = DateTime::createFromFormat('d/m/Y', $data_inicio);
      $data_fim_objeto = DateTime::createFromFormat('d/m/Y', $data_fim);
      
      $historicoAtestado = $this->AtestadoModel->getAtestado2($data_inicio, $data_fim, $demitido, $afastamento);
      if (!is_array($historicoAtestado)) {
        throw new Exception($historicoAtestado);
      }
      if (count($historicoAtestado) == 0) {
        throw new Exception('N�o foi encontrado nenhum funcion�rio com atestado neste per�odo');
      }
      $nomeX = str_replace('/', '-', $data_inicio).'-a-'.str_replace('/', '-', $data_fim);
      $paleg_nome = '';
      switch($afastamento){
          case '20': $paleg_nome = 'ACIDENTE DE TRABALHO'; break;
          case '21': $paleg_nome = 'AFASTAMENTO DOEN�A'; break;
          case '24': $paleg_nome = 'LICEN�A MATERNIDADE'; break;
          case '0': $paleg_nome = 'TODOS'; break;
        }

      $nomeArquivo = 'relat-atestado-' . $nomeX . '.xls';
      $pagina = header("Content-type: application/msexcel;");
      $pagina .= header("Content-Disposition: attachment; filename=$nomeArquivo");
      $pagina .= utf8_decode("<h3>Relat�rio de Atestado</h3>
                                                <h4>Per�odo: $data_inicio a $data_fim</h4>
                                                <h4>Demetido: $demitido</h4>
                                                <h4>Afastamento: $paleg_nome</h4>
						<table border='1'>
							<tr style='font-weight:bold;'>
								<td bgcolor='#999999'>Matricula</td>
								<td bgcolor='#999999'>Funcionario</td>
								<td bgcolor='#999999'>Cargo</td>
                                                                <td bgcolor='#999999'>Setor</td>
								<td bgcolor='#999999'>Ger�ncia</td>
								<td bgcolor='#999999'>Per�odo</td>
								<td bgcolor='#999999'>Sa�da</td>
								<td bgcolor='#999999'>Retorno</td>
								<td bgcolor='#999999'>Qtd Dias</td>
								<td bgcolor='#999999'>Afastamento</td>
								<td bgcolor='#999999'>CID</td>
								<td bgcolor='#999999'>Descri��o</td>
                                                                <td bgcolor='#999999'>Cap�tulo</td>
								<td bgcolor='#999999'>CRM</td>
								<td bgcolor='#999999'>M�dico</td>
							</tr>");


      foreach ($historicoAtestado as $historicoAtestadoKey => $row) {
        
        

        $pagina .= "<tr><td>" . $row['matricula'] . "</td>"
                . "<td>" . $row['nome'] . "</td>"
                . "<td>" . $row['cargo'] . "</td>"
                . "<td>" . $row['setor'] . "</td>"
                . "<td>" . $row['gerencia'] . "</td>"
                . "<td>" . $row['periodo'] . "</td>"
                . "<td>" . $row['saida'] . "</td>"
                . "<td>" . $row['retorno'] . "</td>"
                . "<td>" . $row['qtd_dia'] . "</td>"
                . "<td>" . $row['afastamento'] . "</td>"
                . "<td>" . $row['cid'] . "</td>"
                . "<td>" . $row['cid_descricao'] . "</td>"
                . "<td>" . $row['capitulo_descricao'] . "</td>"
                . "<td>" . $row['crm'] . "</td>"
                . "<td>" . $row['medico'] . "</td></tr>";
        
        
      }
      $pagina .= "</table>";
      echo $pagina;
    } catch (Exception $ex) {
      $var['erro'] = $ex->getMessage();
      echo '<script>window.alert("' . $var["erro"] . '")</script>';
      $this->render('relatorios.php', $var);
    }
  }

}
