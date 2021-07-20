<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agendamento extends CI_Controller {

	function __construct(){
	
		parent::__construct();
		
		$this->load->helper(array('form', 'url', 'funcoes', 'download'));		
		$this->load->library(array('form_validation','session'));		
		$this->load->model('transportadoramodel',"TransportadoraModel");
		$this->load->model('clientemodel',"ClienteModel");
		$this->load->model('cargamodel',"CargaModel");		
		$this->load->model('agendamentomodel', "AgendamentoModel");
		$this->load->model('email_model',"EmailModel");
			
	}
	
	public function index($msg = false) //$paraments = false
	{
		$var['resumoAgendamentos'] = $this->AgendamentoModel->resumoAgendamentos();
		$var['resumoAgendamentosImport'] = $this->AgendamentoModel->resumoAgendamentosImport();
		$var['cargasAgendamento']= $this->AgendamentoModel->cargasAgendamento();
		$var['cargasAgendamentoImport']= $this->AgendamentoModel->cargasAgendamentoImport();
		$var['cargasAgendamentoDistribuicao']= $this->AgendamentoModel->cargasAgendamentoDistribuicao();
		$var['cargasAgendamentoDistribuicaoImport']= $this->AgendamentoModel->cargasAgendamentoDistribuicaoImport();
		$var['distribuicoesGeradas'] = $this->AgendamentoModel->DistribuicoesGeradas();
		$var['distribuicoesGeradasImport'] = $this->AgendamentoModel->DistribuicoesGeradas();
		//$var['agendamentos']= $this->AgendamentoModel->agendamentos();
		//$var['msg'] = $msg;	
		$var['filial'] = $this->AgendamentoModel->getFilial();	
		$var['tempo'] = $this->CargaModel->marcarLeadTime();
		$this->load->view('agendamento_tabs', $var);
		
	}
	
	// Funções para o sistema de Agendamento/Programação
	
	
	public function gerarDistribuicao(){
		
									
		$cod_distribuicao_ebs = $_POST['distribuicoes'];		
		$cod_agendamento = $_POST['cod_agendamento'];
			
		
		$var['check'] = $this->AgendamentoModel->checkDistribuicaoEbs($cod_distribuicao_ebs);
		
		if($var['check'] == 0){
				
			$this->AgendamentoModel->gerarDistribuicao($cod_distribuicao_ebs, $cod_agendamento);
		
			//$this->session->set_flashdata('flashSucesso', 'Distribuição realizada com sucesso!');
			redirect('agendamento#tab-3','refresh');
			
		} else {
			
			//$this->session->set_flashdata('flashErro', 'Distribuição (EBS) já cadastrado!');
			redirect('agendamento#tab-3','refresh');					
		}
			
	}
	
	
	public function devolverAgendamento (){
		$var = $this->AgendamentoModel->devolverAgendamento($_POST);		
		$this->EmailModel->enviar($_POST);
		//$this->session->set_flashdata('flashDevolvido', 'Devolvido com sucesso!');
		redirect('agendamento#tab-2',  'refresh');	
	}
	
	public function alterarAgendamento(){
		$cod_agendamento = $this->uri->segment(3);
		$var['agendamento'] = $this->AgendamentoModel->cargasAgendamento($cod_agendamento);
		$var['justificativa'] = $this->AgendamentoModel->getJustificativa($cod_agendamento);
		$this->load->view('agendamento_atualizar_agenda.php',$var);
	}
	
	public function alterar(){
		$altera = $this->AgendamentoModel->alterar($_POST);
		if($altera){
			$this->index();
		}
	}
	
	
	function programacaoCancelar(){
		$cod_agendamento = $this->uri->segment(3);						
		$this->AgendamentoModel->programacaoCancelar($cod_agendamento);
		//$this->session->set_flashdata('flashCancelado', 'Item cancelado!');
		redirect('agendamento#tab-1',  'refresh');
	}
		
	public function verMotivoDevolucao ($id_carga){
		
		$var['row']  = $this->AgendamentoModel->verMotivoDevolucao($id_carga);
				
		$this->load->view('agendamento_modal', $var);
		
	}
	
	public function carga ($id_carga){
			
		$var['carga'] = $this->CargaModel->cargasLogistica($id_carga);		
		$var['id_carga'] = $id_carga;
		$var['distribuicoes'] = $this->AgendamentoModel->distribuicoes($id_carga);
		$var['status_carga'] = $this->CargaModel->getStatus($id_carga);	
		
		if (empty($var['distribuicoes'])){
			$var ['msg'] = "Já foi realizada a operação";
			$var['color'] = "background: #c7e5c2; border: 1px solid #a2d399;padding: 10px;";
			$var['verCargaDistribuicoes'] = $this->AgendamentoModel->verCargaDistribuicoes($id_carga);
			
		} else {
			
			$var ['msg'] = "Nenhuma Informação";
		}	

		$this->load->view('agendamento_distibuicao', $var);
	}
	
	
	function distribuicaoCarga(){
		$this->AgendamentoModel->distribuicaoCarga($_POST);
		$this->carga($_POST['id_carga']);
		//$this->session->set_flashdata('flashSucessoLiberado', 'Distribuição Atribuida à Carga!');
		redirect('agendamento/carga/'.$_POST['id_carga'].'',  'refresh');
	}
	
	function caragaVerificaExpedicao ($id_carga){
				
		$var['carga'] = $this->CargaModel->cargasExpedicao($id_carga);
		
	}
	
	public function cargaDistribuicao($id_carga){	
		
			
		$var['carga'] = $this->CargaModel->cargasExpedicao($id_carga, "LIB");
		

		@$distribuicoes = $_POST['distribuicoes'];
		
		if(empty($_POST['distribuicoes'])){
							
			$var['verCargaDistribuicoes'] = $this->AgendamentoModel->verCargaDistribuicoes($id_carga);
			
			$var ['msg'] = "Verifique as Informações abaixo";			
			$var ['dev'] = true;
			$var['color'] = "background: #e5e509; border: 1px solid #e0d182;padding: 10px;";
			
		} else {
										
			//$var['cargaDistribuicoes'] = $this->AgendamentoModel->cargaDistribuicoes($id_carga, $distribuicoes);				
			$var['verCargaDistribuicoes'] = $this->AgendamentoModel->verCargaDistribuicoes($id_carga);
			
			$var ['msg'] = "Transação realizada com sucesso!";
			$var['color'] = "background: #c7e5c2; border: 1px solid #a2d399;padding: 10px;";
			
		}
		
		$this->load->view('agendamento_distibuicao', $var);
	}
	
	
	public function liberar_carga($id_carga){	
		
		$var['carga'] = $this->CargaModel->cargasExpedicao($id_carga, "LIB");
		@$distribuicoes = $_POST['distribuicoes'];
		
		if(empty($_POST['distribuicoes'])){
			
			$var['verCargaDistribuicoes'] = $this->AgendamentoModel->verCargaDistribuicoes($id_carga);
			$var ['msg'] = "Verifique as Informações abaixo";			
			$var ['dev'] = true;
			$var['color'] = "background: #e5e509; border: 1px solid #e0d182;padding: 10px;";
			
		} else {
										
			//$var['cargaDistribuicoes'] = $this->AgendamentoModel->cargaDistribuicoes($id_carga, $distribuicoes);				
			$var['verCargaDistribuicoes'] = $this->AgendamentoModel->verCargaDistribuicoes($id_carga);
			$var ['msg'] = "Transação realizada com sucesso!";
			$var['color'] = "background: #c7e5c2; border: 1px solid #a2d399;padding: 10px;";
			
		}
				
		$this->load->view('liberar_carga', $var);
	}	
	
	function getItensDistrib(){
		$codDistribuicao = $_POST['codDistribuicao'];
		$idCarga = $_POST['idCarga'];
		$detalhes =  $this->AgendamentoModel->verItensDistribuicoes($codDistribuicao,$idCarga);
		$table = '<table cellpadding="5" cellspacing="0" border="1" style="padding-left:50px;" class="display">';
		$table .= '<tr class="title ui-state-default">
						<td>Cliente</td>
						<td>Endereço</td>
						<td>Cod Item</td>
						<td>Descrição do Item</td>
						<td>Quantidade</td>
						<td>Data Agendamento</td>
				   </tr>';
		foreach($detalhes as $row){	   
		$table .= '<tr class="od odd">
						<td>'.utf8_encode($row['CLIENTE']).'</td>
						<td>'.utf8_encode($row['ENDERECO']).'</td>
						<td>'.$row['COD_ITEM'].'</td>
						<td>'.utf8_encode($row['DEN_ITEM']).'</td>
						<td>'.$row['QUANTIDADE'].'</td>
						<td>'.$row['DATA_AGENDAMENTO'].'</td>
				   </tr>';
		}
		$table .= '</table>';
		
		echo $table;		
	}
	
	function devolver(){
		
		$cod_agendamento = $this->AgendamentoModel->getCodAgendamento($_POST['ID_CARGA']);
		
		$param  = array('COD_AGENDAMENTO' => $cod_agendamento, 'justificativa' => $_POST['JUSTIFICATIVA']);
		$this->AgendamentoModel->devolverAgendamento($param);
		$this->AgendamentoModel->excluirCargDist($_POST['ID_CARGA']);
		$this->AgendamentoModel->devolverLogistica($_POST['ID_CARGA'], 'DEV');
		//$this->session->set_flashdata('flashDevolvido', 'Devolvido com sucesso!');
	    redirect('agendamento/carga/'.$_POST['ID_CARGA'].'',  'refresh');
	}
		
	function distribuicaoDevolver(){		
		
		
		$cod_agendamento = $this->AgendamentoModel->retornaAgendamento($_POST['DISTRIBUICAO']);
		
				
		$param  = array('COD_AGENDAMENTO' => $cod_agendamento, 'justificativa' => $_POST['JUSTIFICATIVA']);
		
		$this->AgendamentoModel->devolverAgendamento($param);
		$this->AgendamentoModel->excluirCargaDistribuicao($_POST['DISTRIBUICAO']);
		$this->AgendamentoModel->devolverLogistica($_POST['ID_CARGA'], 'LOG');
		//$this->session->set_flashdata('flashDevolvido', 'Devolvido com sucesso!');
	    redirect('agendamento/carga/'.$_POST['ID_CARGA'].'',  'refresh');
		
		/*
		
		@$liberar = $_POST['liberar'];		
		@$devolver = $_POST['devolver'];
		
		$id_carga = $_POST['ID_CARGA'];
		$status = $_POST['STATUS'];
		$status_logistica = "LOG";
		$mat_programador = $_POST['MAT_PROGRAMADOR'];		
		$justificativa = $_POST['justificativa'];
		
		if($devolver){
			//echo "Devolvida - ";
			if ($justificativa) {									
				//echo "com justificativa";
					
				// A distribuição é retormada para a Administração de vendas			
				$this->AgendamentoModel->devolverAgendamento($id_carga, $status, $justificativa, $mat_programador, $cod_distribuicao_ebs);
				
				// Quando devolve-se uma distribuição, é necessário desassociá-la a carga.
				$this->AgendamentoModel->excluirCargaDistribuicao($cod_distribuicao_ebs);
				
				// A carga volta para a logistica e deverá ser associadas as distribuições novamente				
				$this->AgendamentoModel->devolverLogistica($id_carga, $status_logistica);
				
				$this->session->set_flashdata('flashSucessoLiberado', 'Liberação realizada com sucesso!');
				redirect('agendamento/cargaDistribuicao/'.$id_carga.'',  'refresh');
				
			} else {
					
				//echo "sem justificativa";
				$this->session->set_flashdata('flashErroDev', 'Qual a justificativa para devolução?');
				redirect('agendamento/cargaDistribuicao/'.$id_carga.'',  'refresh');												
			}					
		}
					
		if ($liberar) {
			
			if ($id_carga) {
				$aux = array('STATUS' => "SAI");			
		    	$this->CargaModel->liberar_saida($id_carga, $aux);
			}
					
			$this->session->set_flashdata('flashSucessoLiberado', 'Liberação realizada com sucesso!');
			redirect('agendamento/cargaDistribuicao/'.$id_carga.'',  'refresh');
							
		}
		*/
	}
	
	function getStatus(){
		$id_carga = $_POST['idCarga'];
		$status = $this->CargaModel->getStatus($id_carga);
		echo $status;
	}
	
	function liberar_saida()
	{	
		$cod_agendamento = $this->AgendamentoModel->getCodAgendamento($_POST['ID_CARGA']);
		$aux = array('STATUS' => "SAI");			
		$this->CargaModel->liberar_saida($_POST['ID_CARGA'], $aux);
		foreach($cod_agendamento as $row)
		{
			$this->AgendamentoModel->registrar_acao($row,'LIBERADO');
		}
		//$this->session->set_flashdata('flashSucessoLiberado', 'Liberação realizada com sucesso!');
		redirect('agendamento/carga/'.$_POST['ID_CARGA'].'',  'refresh');
	}
	
	function getLeadTimes()
	{
		$var['tempo'] = $this->CargaModel->marcarLeadTime();
	}
	
	function setInfoCargas()
	{		
		$COD_AGENDAMENTO = $_POST['cod_agendamento'];
		
		
		$RETENCOES = $_POST['retencoes'];
		$RESERVAS = $_POST['reservas'];
		$QUANTIDADE = $_POST['quantid'];
		$COD_DISTRIBUICAO = $_POST['distribuicao'];
		
		$var['ajusteLT'] = $this->AgendamentoModel->setInfoCargas($COD_AGENDAMENTO,$RETENCOES,$RESERVAS,$QUANTIDADE,$COD_DISTRIBUICAO);

		//$this->session->set_flashdata('flashSucesso', 'Informações alteradas com sucesso!');
			//redirect('agendamento#tab-2','refresh');
	}

	function setLeadTimePorCarreta()
	{			
		$COD_CARRETA = $_POST['cod_carreta'];
		$VALOR_LEADTIME = $_POST['leadtime'];
				
		$var['ajusteLT'] = $this->AgendamentoModel->defineLTCarreta($COD_CARRETA,$VALOR_LEADTIME);

		$var['retorno'] = 'true';

		if ($var['retorno'] == 'true')
		{

			//$this->session->set_flashdata('flashSucesso', 'Leadtime definido com sucesso!');
			echo redirect('agendamento#tab-2','refresh');
		}	
		
	}
	
	function setLeadTimeDuplicado()
	{		
		$COD_AGENDAMENTO = $_POST['cod_agendamento'];
		
		$RETENCOES = $_POST['retencoes'];
		$RESERVAS = $_POST['reservas'];
		$QUANTIDADE = $_POST['quantid'];
		
		$var['ajusteLTD']= $this->AgendamentoModel->defineLTDuplicado($COD_AGENDAMENTO,$RETENCOES,$RESERVAS,$QUANTIDADE);

		//$this->session->set_flashdata('flashSucesso', 'Leadtime definido com sucesso!');
			redirect('agendamento#tab-2','refresh');		
	}

	function transfereAgendamento()
	{		
		$COD_AGENDAMENTO = $_POST['cod_agendamento'];
		$var['trasnfAgenda']= $this->AgendamentoModel->transfereAgendamento($COD_AGENDAMENTO);		
	}

	function descartaAgendamento()
	{
		$COD_AGENDAMENTO = $_POST['cod_agendamento'];
		$var['agendamentoDescartado'] = $this->AgendamentoModel->excluiDadosImportacao($COD_AGENDAMENTO);
	}
	
	function trocaTransp()
	{
	 	$this->CargaModel->trocaTransp($_POST);
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */