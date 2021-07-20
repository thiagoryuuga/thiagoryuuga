<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expedicao extends CI_Controller {
	 
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
			
			$arr_cidade[] = '{"id":' . $cidade->id . ',"nome":"' . $cidade->nome . '"}';
				
		}
		
		echo '[ ' . implode(",",$arr_cidade) . ']';
		
		return;
		
	}
	
	public function index($msg = false) //$paraments = false
	{
	
		$this->load->model('cidades_model');
		$var['estados'] = $this->cidades_model->getEstados();		
		$var['cargasExpedicao'] = $this->CargaModel->cargasExpedicao();	
		$var['cargasLiberadas']= $this->CargaModel->cargasLiberadas();
		$var['veiculosCarregando']= $this->CargaModel->veiculosCarregando();
		$var['veiculosRecusados']= $this->CargaModel->veiculosRecusados();
		$var['veiculosRomaneio']= $this->CargaModel->veiculosRomaneio();
		$var['veiculosLibFiscal']= $this->CargaModel->veiculosLibFiscal();
		//$var['veiculosDiferencaPeso']= $this->CargaModel->veiculosDiferencaPeso();
		$var['cargasPendentesComplementos']= $this->CargaModel->cargasPendentesComplementos();
		$var['trasportadoras']= $this->TransportadoraModel->transportadoraTodas();
		$var['clientes']= $this->ClienteModel->clienteTodos();
		$var['historicoCargas'] = $this->CargaModel->buscaHistorico();
		$var['msg'] = $msg;	
				
		$this->load->view('expedicao', $var);
	
	}

	

	
	public function ver($id_carga, $par = false)
	{
		$var['id_carga'] = $id_carga;

		if ($id_carga) {
			$var['carga'] = $this->CargaModel->cargasLogistica($id_carga);			
		}
		
		$this->load->model('cidades_model');
		$var['estados'] = $this->cidades_model->getEstados();
		$var['cidades'] = $this->cidades_model->getCidades();		
	
		$var['trasportadoras']= $this->TransportadoraModel->transportadoraTodas();
		$var['tran']= $this->TransportadoraModel->tran($id_carga);
		$var['clientes']= $this->ClienteModel->clienteTodos();
		$var['cargasPendentes']= $this->CargaModel->cargasPendentes();
		$var['cargasPendentesComplementos']= $this->CargaModel->cargasPendentesComplementos();
		$var['ExpedicaoOk'] = true;
		$var['par'] = $par;
		
		//$var['par'] = "ENC";
				
		$this->load->view('logistica_edit', $var);
	}
	
	
	
	// Baseado no ID_CLIENTE é possível pegar o CPF do Motorista
	public function recebedor_motorista ($cod_recebedor){		
		$a = $this->ClienteModel->detalhar($cod_recebedor);		
		return @$a['CGCCPF_CLIENTE'];		
	}
	
	public function editar()
	{
			
		
		$INFORMACOES_FISCAIS="";
			
		if($_POST['DATA_SAIDA']){		
			$data1 = explode('/', $_POST['DATA_SAIDA']);
			$dataSql = $data1[2]."-".$data1[1]."-".$data1[0];
						
		} else {
			$dataSql = "";
		}	
		$hora_saida_expedicao = date("Y-m-d H:i:s");
		
		if(isset($carga['CPF_RECEBEDOR']))
		{
			
			$cpf_num = "CPF_RECEBEDOR = '".$carga['CPF_RECEBEDOR']."',";
		}
		else
		{
			$cpf_num =''; 
		}

		// Caso o recebedor NÃO SER o motorista, preenche o campo CPF do Recebedor
		/*if($_POST['CPF_RECEBEDOR']){
			$cpf_recebedor = $_POST['CPF_RECEBEDOR'];
			//echo $cpf_recebedor;
		} else if($_POST['COD_RECEBEDOR']) { // Se o recebedor for o motorista, pego o CPF dele e cadastro como CPF_RECEBEDOR			
			$cpf_recebedor = $this->recebedor_motorista($_POST['COD_RECEBEDOR']);
			//echo $cpf_recebedor;
		}*/
		
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

			$msg_update = 'VEICULO ENCAMINHADO PARA LOGISTICA';
			$usuario_operacao = $this->session->userdata['idUser'];
		} 
		 if($_POST['ACAO'] == "CAR"){			
			$status = "CAR";
			$msg = "Veículo iniciou o carregamento!";
			$INFORMACOES_FISCAIS = "";

			$msg_update = 'VEICULO INICIOU O CARREGAMENTO';
			$usuario_operacao = $this->session->userdata['idUser'];
		}
		
		 if($_POST['ACAO'] == "DSC"){			
			$status = "DSC";
			$msg = "Veículo iniciou o carregamento!";
			$INFORMACOES_FISCAIS = "";
		}

		 if($_POST['ACAO'] == "ROM"){			
			$status = "ROM";
			$msg = "Enviado para a etapa de romaneio!";

			$msg_update = 'VEICULO ENCAMINHADO PARA EMISSAO DE ROMANEIO';
			$usuario_operacao = $this->session->userdata['idUser'];

			if($_POST['INFORMACOES_FISCAIS'] == "" )
			{
				$INFORMACOES_FISCAIS ="VEICULO COM ROMANEIO PENDENTE";
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

			$msg_update = 'VEICULO ENCAMINHADO PARA EXPEDICAO';
			$usuario_operacao = $this->session->userdata['idUser'];
		}
		 if($_POST['ACAO'] == "ATU"){			
			$status = $_POST['STATUS_CARGA'];
			
			$msg = "Dados Atualizados!";

			$msg_update = 'VEICULO RECEBEU ATUALIZACAO DE DADOS';
			$usuario_operacao = $this->session->userdata['idUser'];
		}

		if($_POST['ACAO'] == "COM"){			
			$status = $_POST['STATUS_CARGA'];
			
			$msg = "Complemento Adicionado!";

			$msg_update = 'VEICULO RECEBEU COMPLEMENTO DE CARGA';
			$usuario_operacao = $this->session->userdata['idUser'];
		}

		//buscando o status atual do veículo no banco para  conferencia
		
		/*$sqlstatus ="SELECT STATUS FROM ESMALTEC.ESM_LOGISTICA_CARGA WHERE ID_CARGA = '".$_POST['ID_CARGA']."'";

		
				$status_atual = $this->db->query($sqlstatus)->result_array();
				
		// definindo status atual e proximo status possível
			$valid='';
			$prox_status = '';
			if($status_atual[0]['STATUS'] =='LOG')
			{
				$prox_status = 'EXP';
			}
			if($status_atual[0]['STATUS'] =='EXP')
			{
				$prox_status = 'AUT';
			}
			if($status_atual[0]['STATUS'] =='AUT')
			{
				$prox_status = 'LIB';
			}
			if($status_atual[0]['STATUS'] =='LIB')
			{
				$prox_status = 'CAR';
			}
			if($status_atual[0]['STATUS'] =='CAR')
			{
				$prox_status = 'ROM';
			}
			if($status_atual[0]['STATUS'] =='ROM')
			{
				$prox_status = 'FIS';
			}
			if($status_atual[0]['STATUS'] =='FIS')
			{
				$prox_status = 'SAI';
			}
			if($status_atual[0]['STATUS'] =='SAI')
			{
				$prox_status = 'TRA';
			}
			if($status_atual[0]['STATUS'] =='TRA')
			{
				$prox_status = 'ENT';
			}
			if($status_atual[0]['STATUS'] =='REC' && $_POST['ACAO'] == 'LOG')
			{
				$prox_status = 'LOG';
			}
			if($status_atual[0]['STATUS'] =='REC' && $_POST['ACAO'] == 'EXP')
			{
				$prox_status = 'EXP';
			}
			//se as validações estuverem OK ou se forem status trocados por links diretos da portaria
			if(trim($_POST['ACAO']) == trim($prox_status) || ($_POST['ACAO'] == 'ATU' || $_POST['ACAO'] == 'DIF'))
			{
				$valid = 'TRUE';
			}
			else
			{
				$valid = 'FALSE';
			}*/
		
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
			'TIPO_OPERACAO' => $this->input->post('TIPO_OPERACAO'),
			'CAPATAZIA' => $this->input->post('CAPATAZIA'),
			'TAMANHO_VEICULO' => $this->input->post('TAMANHO_VEICULO'),
			'INFORMACOES_FISCAIS' =>$INFORMACOES_FISCAIS,		
			'STATUS' => $status,
			'HORA_SAIDA_EXPEDICAO' =>  "TO_DATE('".$hora_saida_expedicao."','YYYY-MM-DD HH24:MI:SS')",

			
			$cpf_num,
			'TIPO_RECEBEDOR' => $this->input->post('TIPO_RECEBEDOR'),
			'USUARIO_EXPEDICAO' => $this->session->userdata('idUser'),
			'LOCAL_COMPLEMENTO' => $this->input->post('LOCAL_COMPLEMENTO')
		);
		
				
		$idcarga = $this->input->post('ID_CARGA');
		//if($idcarga){
		//valida todas as condições acima e a carga não precisa de complementos
		if($idcarga && $_POST['ACAO'] != "COM"){							
			
			$this->CargaModel->update_expedicao($idcarga, $carga);

			$this->CargaModel->gravaOperacao($idcarga,$msg_update,$usuario_operacao);
		} 

		//valida todas as condições acima e a carga vai precisar de complementos
		if($idcarga && $_POST['ACAO'] == "COM"){							
			
			$this->CargaModel->insere_complementos($idcarga, $carga);

			$this->CargaModel->gravaOperacao($idcarga,$msg_update,$usuario_operacao);

		} 
		$this->load->model('cidades_model');
		$var['estados'] = $this->cidades_model->getEstados();	
		$var['transportadoras']= $this->TransportadoraModel->transportadoraTodas();
		$var['clientes']= $this->ClienteModel->clienteTodos();
		
		// Cargas Autorizada
		$var['cargasExpedicao']= $this->CargaModel->cargasExpedicao();
		
		
		$var['ExpedicaoOk']= true;

		$idLevel = ($this->session->userdata('idLevel'));
		
						
		if($_POST['STATUS_CARGA']=='REC' && $idLevel =="P")
		{
			//$this->session->set_flashdata('flashSucesso', 'Veiculo encaminhado com sucesso!');
			redirect('/inicio#tab-4', 'refresh');
		}
		if($_POST['STATUS_CARGA']=='REC' && $idLevel =="E")
		{
			//$this->session->set_flashdata('flashSucesso', 'Veiculo encaminhado com sucesso!');
			redirect('/expedicao#tab-3', 'refresh');
		}
		if($_POST['STATUS_CARGA']=='REC' && $idLevel =="G")
		{
		//	$this->session->set_flashdata('flashSucesso', 'Veiculo encaminhado com sucesso!');
			redirect('/inicio#tab-4', 'refresh');
		}

		else {
				
			$this->index($msg);
		}
		//$this->load->view('expedicao', $var);
			
			
			
	}

	public function recusar_entrada(){
		//print_r($_POST);
		$param['id_carga'] = $_POST['carga_recusa'];
		$param['motivo_recusa'] = $_POST['motivo_recusa'];
		$param['status_carga'] = $_POST['status_carga'];

		$var['recusaEntrada'] = $this->CargaModel->recusaEntrada($param);
		

		//$this->session->set_flashdata('flashSucesso', 'Entrada recusada com sucesso!');
			redirect('inicio#tab-3');

	}

	public function abreRecusaCarga()
	{
		$var['id_carga'] = $this->uri->segment(3);
		$this->load->view('recusa_carga',$var);
	}

	public function processaRecusaCarga()
	{
		$param['id_carga'] = $_POST['id_carga'];
		$param['motivo_recusa'] = $_POST['motivo_recusa'];
		$param['status_carga'] = 'CAN';

		$resultado = $this->CargaModel->recusaEntrada($param);

		$idcarga = $param['id_carga'];
		$msg_update = "VEICULO RECUSADO";
		$usuario_operacao = $this->session->userdata['idUser'];
		$this->CargaModel->gravaOperacao($idcarga,$msg_update,$usuario_operacao);
		redirect("expedicao#tab-4");		
	}
	
	// Cancelar uma carga (Não excluir, faz apenas não mostrar mais na listagem de carga. Muda somente o STATUS)
	public function cancelar ($id_carga){
		
		if ($id_carga) {									
			$status = array('STATUS' => "CAN");			
	    	$var['cargasCancelar'] = $this->CargaModel->cancelar($id_carga, $status);

			$idcarga = $id_carga;
			$msg_update = "VEICULO CANCELADO";
			$usuario_operacao = $this->session->userdata['idUser'];
			$this->CargaModel->gravaOperacao($idcarga,$msg_update,$usuario_operacao);	
					
		}
		redirect('/expedicao#page', 'location');
		
	}


	public function listaCarrosRomaneio()
	{
		$cargasRomaneio = $this->CargaModel->veiculosRomaneio();
		$tyle ="";
		foreach ($cargasRomaneio as $rowPendentes) {
			if($rowPendentes['TEMPO'] < 30)
			{
				$tyle="background-color:#FFF;";
			}
			if($rowPendentes['TEMPO'] > 30 && $rowPendentes['TEMPO'] < 60)
			{
				$tyle="background-color:#BD0";
			}
			if($rowPendentes['TEMPO'] >60 )
			{
				$tyle="background-color:#A00;color:#FFF;";
			}
			$link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";

			echo "<tr><td style=".$tyle.">".($rowPendentes['HORA_CRIACAO_ROMANEIO_INI'])."</td>
				<td style=".$tyle.">".$rowPendentes['NOME_TRANSPORTADORA']."</td>
				<td style=".$tyle.">".$rowPendentes['VEICULO']."</td>
				<td style=".$tyle.">".$rowPendentes['PLACA_VEICULO']."</td>
				<td style=".$tyle.">".utf8_encode($rowPendentes['NOME_ESTADO'])."</td>
				<td style=".$tyle.">".utf8_encode($rowPendentes['NOME_CIDADE'])."</td>
				<td style=".$tyle.">".$rowPendentes['STATUS']."</td>
				<td style=".$tyle.">".$rowPendentes['LOCAL_CARREGAMENTO']."</td>
				<td style=".$tyle.">".$rowPendentes['DOCA']."</td>
				<td style=".$tyle.">".$rowPendentes['CONFERENTE']."</td>
				<td style=".$tyle.">".$rowPendentes['CAPATAZIA']."</td>

				<td style=".$tyle." class='center'>"."<a class='thickbox' href='".$link."'>[Informações]</a></td>
				<td style=".$tyle."><div class='actions'>
						<ul>
							<li><a class='action1 thickbox' href='expedicao/ver/$rowPendentes[ID_CARGA]?height=580&width=1100' title='visualizar'></a></li>
						</ul></div>
				</td>
				</tr>";

			
		}
	} 

	public function listaLiberacaoFiscal()
	{
		$tyle ="";
		$cargasLibFiscal = $this->CargaModel->veiculosLibFiscal();

		foreach ($cargasLibFiscal as $rowPendentes) {
			if($rowPendentes['TEMPO'] < 30)
			{
				$tyle="background-color:#FFF;";
			}
			if($rowPendentes['TEMPO'] > 30 && $rowPendentes['TEMPO'] < 60)
			{
				$tyle="background-color:#BD0";
			}
			if($rowPendentes['TEMPO'] >60 )
			{
				$tyle="background-color:#A00;color:#FFF;";
			}
			$link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";

			echo "<tr><td style=".$tyle.">".($rowPendentes['HORA_LIB_FISCAL_INI'])."</td>
				<td style=".$tyle.">".$rowPendentes['NOME_TRANSPORTADORA']."</td>
				<td style=".$tyle.">".$rowPendentes['VEICULO']."</td>
				<td style=".$tyle.">".$rowPendentes['PLACA_VEICULO']."</td>
				<td style=".$tyle.">".utf8_encode($rowPendentes['NOME_ESTADO'])."</td>
				<td style=".$tyle.">".utf8_encode($rowPendentes['NOME_CIDADE'])."</td>
				<td style=".$tyle.">".$rowPendentes['STATUS']."</td>
				<td style=".$tyle.">".$rowPendentes['LOCAL_CARREGAMENTO']."</td>
				<td style=".$tyle.">".$rowPendentes['DOCA']."</td>
				<td style=".$tyle.">".$rowPendentes['CONFERENTE']."</td>
				<td style=".$tyle.">".$rowPendentes['CAPATAZIA']."</td>

				<td style=".$tyle." class='center'>"."<a class='thickbox' href='".$link."'>[Informações]</a></td>
				<td style=".$tyle."><div class='actions'>
						<ul>
							<li><a class='action1 thickbox' href='expedicao/ver/$rowPendentes[ID_CARGA]?height=580&width=1100' title='visualizar'></a></li>
						</ul></div>
				</td>
				</tr>";

			
		}
	} 

	public function listaDifPeso()
	{
		$cargaDifPeso = $var['veiculosDiferencaPeso'] = $this->CargaModel->veiculosDiferencaPeso();

		foreach ($cargaDifPeso as $rowPendentes) {
			if($rowPendentes['TEMPO'] < 30)
			{
				$tyle="background-color:#FFF;";
			}
			if($rowPendentes['TEMPO'] > 30 && $rowPendentes['TEMPO'] < 60)
			{
				$tyle="background-color:#BD0";
			}
			if($rowPendentes['TEMPO'] >60 )
			{
				$tyle="background-color:#A00;color:#FFF;";
			}
			$link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";
			$link_recusa="";
			$link_saida = base_url()."index.php/inicio/saida/".$rowPendentes['ID_CARGA'];
			echo "<tr><td style=".$tyle.">".sqlToDataHora($rowPendentes['HORA_ENTRADA'])."</td>
				<td style=".$tyle.">".substr($rowPendentes['NOME_TRANSPORTADORA'],0,15)."</td>
				<td style=".$tyle.">".$rowPendentes['DES_OPERACAO']."</td>
				<td style=".$tyle.">".$rowPendentes['VEICULO']."</td>
				<td style=".$tyle.">".$rowPendentes['PLACA_VEICULO']."</td>
				<td style=".$tyle.">".utf8_encode($rowPendentes['NOME_ESTADO'])."</td>
				<td style=".$tyle.">".utf8_encode($rowPendentes['NOME_CIDADE'])."</td>
				<td style=".$tyle.">".$rowPendentes['LOCAL_CARREGAMENTO']."</td>
				<td style=".$tyle.">".$rowPendentes['DOCA']."</td>
				<td style=".$tyle." class='center'>"."<a class='thickbox' href='".$link."'>[Informações]</a></td>
				<!--<td style=".$tyle."><div class='actions'>
						<ul>
							<li><a class='action1 thickbox' href='expedicao/ver/$rowPendentes[ID_CARGA]?height=580&width=1100' title='visualizar'></a></li>
							<li><a class='action4 thickbox' href='<?php echo $link_recusa ?>' title='Recusar Veiculo'></a></li>
						</ul></div>
				</td>-->

				<td style=".$tyle." class='center'><a href='#' onclick='encaminha_saida($rowPendentes[ID_CARGA])'>Liberar para saída</a></td>
				</tr>";

			
		}
	}

	function buscaHistorico()
	{
		
		$dadosPesquisa = array();
		$dadosPesquisa['data_inicio'] = $_POST['data_inicio'];
		$dadosPesquisa['data_fim'] = $_POST['data_fim'];
		$dadosPesquisa['placa_carreta'] = $_POST['placa_carreta'];
		$dadosPesquisa['operacao'] = $_POST['operacao'];

		$lista_arquivo = $this->CargaModel->buscaHistorico($dadosPesquisa);
		$file_header ="";
		$file_data ="";
		$file_footer="";

		$file_header = "<table cellpadding=\"3\" cellspacing=\"3\" border=\"1\">
		<thead>
		<tr>
			<th>MATRICULA</th>
			<th>USUARIO</th>
			<th>CARRETA</th>
			<th>OPERACAO</th>
			<th>LOCAL</th>
			<th>DATA</th>			
		</tr>
		</thead>
		<tbody>		
		";			
		foreach ($lista_arquivo as $row) {	
			$file_data.= "			
			<tr>
				<td>".$row['MATRICULA']."</td>
				<td>".$row['NOM_FUNCIONARIO']."</td>
				<td>".($row['PLACA_VEICULO'])."</td>
				<td>".($row['OPERACAO'])."</td>
				<td>".($row['LOCAL_CARREGAMENTO'])."</td>
				<td>".($row['DATA_EXECUCAO'])."</td>				
			</tr>";		
		}					
		$file_footer.= "		
		</tbody>
		</table>		
		";
		
		$name = 'relatorio_auditoria_'.date('d_m_y_H_i_s').'.xls';
		force_download($name, $file_header.$file_data.$file_footer);
				
		
	}	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */