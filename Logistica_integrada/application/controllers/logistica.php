<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logistica extends CI_Controller {

	function __construct(){
	
		parent::__construct();
		
		$this->load->helper(array('form', 'url', 'funcoes', 'download'));		
		$this->load->library(array('form_validation','session'));		
		$this->load->model('transportadoramodel',"TransportadoraModel");
		$this->load->model('clientemodel',"ClienteModel");
		$this->load->model('cargamodel',"CargaModel");
			
	}
	
	function getCidades($id) {
		
		$this->load->model('cidades_model');
		
		$cidades = $this->cidades_model->getCidades($id);
		
		if( empty ( $cidades ) ) 
			return '{ "nome": "Nenhuma cidade encontrada" }';
			
		$arr_cidade = array();

		foreach ($cidades as $cidade) {
			
			$arr_cidade[] = '{"ID":' . $cidade->ID . ',"NOME":"' . $cidade->NOME . '"}';
				
		}
		
		echo '[ ' . implode(",",$arr_cidade) . ']';
		
		return;
		
	}
	
	public function index($msg = false) //$paraments = false
	{
			
		$this->load->model('cidades_model');
		$var['estados'] = $this->cidades_model->getEstados();			
		$var['trasportadoras']= $this->TransportadoraModel->transportadoraTodas();
		$var['clientes']= $this->ClienteModel->clienteTodos();
		$status = "LOG";
		$var['cargasLogisticaDist']= $this->CargaModel->cargasLogisticaDist();
		$var['cargasLogistica'] = $this->CargaModel->cargasLogisticaSemDist($id_carga = false);
		$var['cargasLiberadas'] = $this->CargaModel->cargasLiberadas();
		$var['cargasPendentes'] = $this->CargaModel->cargasPendentes();
		$var['cargasPendentesComplementos'] = $this->CargaModel->cargasPendentesComplementos();				
		$var['msg'] = $msg;					
		$this->load->view('logistica', $var);
		
	}
	
	public function ver($id_carga)
	{	
		
		if ($id_carga) {
			$var['carga'] = $this->CargaModel->cargasLogisticaSemDist($id_carga);			
		}		
				
		$this->load->model('cidades_model');
		$var['estados'] = $this->cidades_model->getEstados();
		$var['cidades'] = $this->cidades_model->getCidades($var['carga'][0]['ID_UF']);		
		$var['trasportadoras']= $this->TransportadoraModel->transportadoraTodas();
		$var['tran']= $this->TransportadoraModel->tran($id_carga);		
		$var['clientes']= $this->ClienteModel->clienteTodos();		
		$var['cargasPendentes']= $this->CargaModel->cargasPendentes();
		$var['cargasPendentesComplementos']= $this->CargaModel->cargasPendentesComplementos();
		$var['ExpedicaoOk'] = false;
		$var['id_carga'] = $id_carga;			
		$this->load->view('logistica_edit', $var);
	}
	
	public function conferi($id_carga)
	{	
		
		if ($id_carga) {
			$var['carga'] = $this->CargaModel->cargasConferi($id_carga);			
		}				
		$this->load->view('logistica_conferi', $var);
	}
	
	public function pesquisar()
	{	
		$data1 = explode('/', $_POST['data_pesquisa1']);
		$dataSql1 = $data1[2]."-".$data1[1]."-".$data1[0];
		
		$dados_pesquisa = array(
				'data_pesquisa1' => $dataSql1,				
				'status' => $this->input->post('status')									
		);
		
		if(!empty($_POST['data_pesquisa2'])){	
			@$data2 = explode('/', $_POST['data_pesquisa2']);
			@$dataSql2 = $data2[2]."-".$data2[1]."-".$data2[0];
		
			$dados_pesquisa['data_pesquisa2'] = $dataSql2;
		}		
	
		if ($dados_pesquisa) {
			$var['cargasLogistica'] = $this->CargaModel->pesquisarCargas($id_carga = false, $dados_pesquisa);			
		}			
		
		if(!empty($_POST['xls'])){		
			$this->gerar_excel($var['cargasLogistica']);
		}
		
		$this->load->view('logistica', $var);
		
	}
	
	
	function clientes(){		
		$term = $_POST['term'];
		$lider = $this->ClienteModel->get_cliente($term);
		$arr = array();
		foreach($lider as $row)
		{
			$arr[] = $row['cgccpf_cliente']." - ".$row['nome_cliente']." - ".$row['id_cliente'];
		}
		
        echo json_encode($arr);
		 
	}
	
	// Baseado no ID_CLIENTE é possível pegar o CPF do Motorista
	public function recebedor_motorista ($cod_recebedor){		
		$a = $this->ClienteModel->detalhar($cod_recebedor);		
		//return $a['CGCCPF_CLIENTE'];		
	}	
	
	public function verificDistrib(){
		$idcarga = $_POST['ID_CARGA'];
		$distrib = $this->CargaModel->verificDistrib($idcarga);
		if($distrib > 0){
			echo "true";
		}else{
			echo "false";
		}
	}
	
	
	public function editar()
	{
									
		if($_POST['DATA_SAIDA']){		
			$data1 = explode('/', $_POST['DATA_SAIDA']);
			$dataSql = $data1[2]."-".$data1[1]."-".$data1[0];
						
		} else {
			$dataSql = "";
		}
		$hora_saida_logistica = date("Y-m-d H:i:s");
		
		// Caso o recebedor NÃO SER o motorista, preenche o campo CPF do Recebedor
		if($_POST['CPF_RECEBEDOR']){
			$cpf_recebedor = $_POST['CPF_RECEBEDOR'];
			
		} else if($_POST['COD_RECEBEDOR']) { // Se o recebedor for o motorista, pego o CPF dele e cadastro como CPF_RECEBEDOR			
			$cpf_recebedor = $this->recebedor_motorista($_POST['COD_RECEBEDOR']);
			
		}
		else{
			$cpf_recebedor ='00000000000';
		    }
		
		if($_POST['ACAO'] == "ENC"){			
			$status = "EXP";
			$msg = "Encaminhado com sucesso para Expedição!";

			$msg_update = 'VEICULO ENCAMINHADO PARA EXPEDICAO';
			$usuario_operacao = $this->session->userdata['idUser'];
		}
		if($_POST['ACAO'] == "EXP"){			
			$status = "LOG";
			$msg = "Informações atualizadas com sucesso!";

			$msg_update = 'VEICULO ENCAMINHADO PARA LOGISTICA';
			$usuario_operacao = $this->session->userdata['idUser'];
		}

		////////////////////////////////////////////////////////////////////////////

		if($_POST['ACAO']=="SAI")
		{
			$status = "SAI";
			$msg = "Autorização enviada com sucesso!";
			$INFORMACOES_FISCAIS = "MOTORISTA NÃO RECEBEU OS DOCUMENTOS";

			$msg_update = 'VEICULO RECEBEU AUTORIZACAO DE SAIDA';
			$usuario_operacao = $this->session->userdata['idUser'];
		}
		 if($_POST['ACAO'] == "AUT"){			
			$status = "AUT";
			$msg = "Entrada autorizada com sucesso!";
			$INFORMACOES_FISCAIS = "";

			$msg_update = 'VEICULO RECEBEU AUTORIZACAO DE ENTRADA';
			$usuario_operacao = $this->session->userdata['idUser'];
		} 
		 if($_POST['ACAO'] == "LOG"){			
			$status = "LOG";
			$msg = "Enviado para a logistica!";
			$INFORMACOES_FISCAIS = "";
		} 
		 if($_POST['ACAO'] == "CAR"){			
			$status = "CAR";
			$msg = "Veículo iniciou o carregamento!";
			$INFORMACOES_FISCAIS = "";

			$msg_update = 'VEICULO ENCAMINHADO PARA CARREGAMENTO';
			$usuario_operacao = $this->session->userdata['idUser'];
		}
		
		 if($_POST['ACAO'] == "ROM"){			
			$status = "ROM";
			$msg = "Enviado para a etapa de romaneio!";

			if($_POST['INFORMACOES_FISCAIS'] == "" )
			{
				$INFORMACOES_FISCAIS ="VEICULO COM ROMANEIO PENDENTE";
				
				$msg_update = 'VEICULO ENCAMINHADO PARA EMISSAO DE ROMANEIO';
				$usuario_operacao = $this->session->userdata['idUser'];
			}
			else
			{
				$INFORMACOES_FISCAIS = $_POST['INFORMACOES_FISCAIS'];
			}
		}
		 if($_POST['ACAO'] == "FIS"){			
			$status = "FIS";
			$msg = "Enviado para a liberação fiscal!";

			$msg_update = 'VEICULO ENCAMINHADO PARA LIBERACAO FISCAL';
			$usuario_operacao = $this->session->userdata['idUser'];

			if($_POST['INFORMACOES_FISCAIS'] == "" || $_POST['INFORMACOES_FISCAIS'] != "" || $_POST['INFORMACOES_FISCAIS'] =="VEICULO COM ROMANEIO PENDENTE")
			{
				$INFORMACOES_FISCAIS ="MOTORISTA NAO APRESENTOU ROMANEIO";
			}
			else
			{
				$INFORMACOES_FISCAIS = $_POST['INFORMACOES_FISCAIS'];
			}
		}

		if($_POST['ACAO'] == "EXP"){			
			$status = "EXP";
			
			$msg = "Enviado para a expedição!";
		}
		 if($_POST['ACAO'] == "ATU"){			
			$status = $_POST['STATUS_CARGA'];
			
			$msg = "Dados Atualizados!";
		}
		
		$post = $_POST;
		
				
		$carga = array(
			'ID_CLIENTE' => $this->input->post('ID_CLIENTE'),			
			'ID_TRASPORTADORA' => $this->input->post('id_trasportadora'),
			'ESTADO' => $this->input->post('estado'),
			'CIDADE' => $this->input->post('cidade'),
			'VEICULO' => $this->input->post('VEICULO'),
			'PLACA_VEICULO' => $this->input->post('PLACA_VEICULO'),			
			'TIPO_DOCUMENTO' => $this->input->post('TIPO_DOCUMENTO'),	
			'DISTRIBUICAO' => $this->input->post('DISTRIBUICAO'),
			'DOCUMENTOS' => $this->input->post('DOCUMENTOS'),							
			'OBSERVACAO' => $this->input->post('OBSERVACAO'),			
			'DATA_SAIDA' => "TO_DATE('".$dataSql."','YYYY-MM-DD')",
			'SENHA_AGENDAMENTO' => $this->input->post('SENHA_AGENDAMENTO'),			
			'LOCAL_CARREGAMENTO' => $this->input->post('LOCAL_CARREGAMENTO'),
			'DOCA' => $this->input->post('DOCA'),
			'CONFERENTE' => $this->input->post('CONFERENTE'),
			'CONFERENTE_2' => $this->input->post('CONFERENTE_2'),
			'CONFERENTE_3' => $this->input->post('CONFERENTE_3'),
			'CAPATAZIA' => $this->input->post('CAPATAZIA'),
			'TAMANHO_VEICULO' => $this->input->post('TAMANHO_VEICULO'),
			'TIPO_OPERACAO' => $this->input->post('TIPO_OPERACAO'),			
			'STATUS' => $status,
			'HORA_SAIDA_LOGISTICA' =>  "TO_DATE('".$hora_saida_logistica."','YYYY-MM-DD HH24:MI:SS')",
			'USUARIO_LOGISTICA' => $this->session->userdata('idUser'),
			'CPF_RECEBEDOR' => $cpf_recebedor,
			'TIPO_RECEBEDOR' => $this->input->post('TIPO_RECEBEDOR')
		);
				
		$idcarga = $this->input->post('ID_CARGA');
		if($idcarga){							
			$this->CargaModel->update($idcarga, $carga);

			$this->CargaModel->gravaOperacao($idcarga,$msg_update,$usuario_operacao);			
		} 
		
		$this->load->model('cidades_model');
		$var['estados'] = $this->cidades_model->getEstados();	
		$var['transportadoras']= $this->TransportadoraModel->transportadoraTodas();
		$var['clientes']= $this->ClienteModel->clienteTodos();
		
		// Cargas Autorizada
		$var['cargasLogistica']= $this->CargaModel->cargasAutorizadas();
		
		// Cargas Pendentes de Autorização		
		$var['LogisticaOk']= true;
		
		if($_POST['STATUS_CARGA']=='REC')
		{
			redirect('/inicio#tab-4', 'location');
		}
		else {		
			$this->index($msg);
		}
		
	}	
	public function gerar_excel($var){
		
			// arquivo para download logo apos gerado
  			header('Content-Type: text/csv; charset=utf-8');
  			header('Content-Disposition: attachment; filename=relatorio.csv');

		    // Cria um registro para receber os dados e gerar o arquivo
           $saida = fopen('php://output', 'w');	

		 $data = array("Chegada na Fabrica"     , "Autorizacao de Entrada", "Confirmacao de Entrada" , "Inicio de Carregamento", 
		 			   "Fim de Carregamento"    , "Inicio Geracao Romaneio", "Fim Geracao Romaneio"  , "Inicio Liberacao Fiscal", "Fim Liberacao Fiscal"  , 
					   "Inicio Dif. Peso"       , "Fim Dif. Peso"          , "Confirmacao de Saida"  , "Transportadora"         , "Placa veiculo"         , 
					   "Placa cavalo"           , "Tipo de Operacao"       , "Cliente"               , "Tamanho do Veiculo"     , "Tipo de Documento"     , 
					   "Distribuicao"           , "Observacoes"            , "Documento(s)"          , "Agendamento"            , "Senha do Agendamento"  , 
					   "Tipo veiculo"           , "Local de carregamento"  , "1o Conferente"         , "2o Conferente"          , "3o Conferente"         , 
					   "Capatazia"              , "UF"                     , "Cidade"                , "Status"                 , "Complementar");	

		//adiciona o array no arquivo e separa por ';'
        fputcsv($saida,$data,';');	
		ob_start();
		foreach ($var as $row) {
			$info =array();	
			
				array_push($info,sqlToDate($row['DATA_ATUAL'])." ".$row['HORA_CHEGADA']);
				array_push($info,$row['HORA_SAIDA_LOGISTICA']);
				array_push($info,$row['HORA_ENTRADA']);
				array_push($info,$row['HORA_CARREGAMENTO_INI']);
				array_push($info,$row['HORA_CARREGAMENTO_FIM']);
				array_push($info,$row['HORA_CRIACAO_ROMANEIO_INI']);
				array_push($info,$row['HORA_CRIACAO_ROMANEIO_FIM']);
				array_push($info,$row['HORA_LIB_FISCAL_INI']);
				array_push($info,$row['HORA_LIB_FISCAL_FIM']);
				array_push($info,$row['HORA_DIF_INI']);
				array_push($info,$row['HORA_DIF_FIM']);
				array_push($info,$row['HORA_SAIDA']);
				array_push($info,$row['NOME_TRANSPORTADORA']);
				array_push($info,$row['PLACA_VEICULO']);	
				array_push($info,$row['PLACA_CAVALO']);
				array_push($info,$row['DESC_OPERACAO']);
				array_push($info,$row['NOME_CLIENTE']);
				array_push($info,$row['TAMANHO_VEICULO']);
				array_push($info,$row['TIPO_DOCUMENTO']);
				array_push($info,$row['DISTRIBUICAO']);
				array_push($info,utf8_decode(str_replace('/n', ' ', $row['OBSERVACAO'])));
				array_push($info,utf8_decode($row['DOCUMENTOS']));
				array_push($info,substr($row['DATA_SAIDA'], 0, -8));
				array_push($info,$row['SENHA_AGENDAMENTO']);
				array_push($info,$row['VEICULO']);
				array_push($info,$row['LOCAL_CARREGAMENTO']);
				array_push($info,$row['CONFERENTE']);
				array_push($info,$row['CONFERENTE_2']);
				array_push($info,$row['CONFERENTE_3']);
				array_push($info,$row['CAPATAZIA']);
				array_push($info,$row['NOME_ESTADO']);
				array_push($info,$row['NOME_CIDADE']);
				array_push($info,$row['STATUS']);
				array_push($info,$row['POSSUI_COMPLEMENTO']);
				fputcsv($saida,$info,";");	
		}
		$data = ob_get_contents();
					
		
		$name = 'relatorio.csv';
		force_download($name, $data);
		ob_flush();
		
	}

	// Cancelar uma carga (Não excluir, faz apenas não mostrar mais na listagem de carga. Muda somente o STATUS)
	public function cancelar ($id_carga){
		
		if ($id_carga) {									
			$status = array('STATUS' => "CAN");			
	    	$var['cargasCancelar'] = $this->CargaModel->cancelar($id_carga, $status);				
		}
		redirect('/logistica#page', 'location');
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */