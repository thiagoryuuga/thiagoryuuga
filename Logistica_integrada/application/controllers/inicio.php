<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

	function __construct(){
	
		parent::__construct();
		
		$this->load->helper(array('form', 'url', 'funcoes'));
		$this->load->library(array('form_validation', 'session'));		
		$this->load->model('transportadoramodel',"TransportadoraModel");
		$this->load->model('clientemodel',"ClienteModel");
		$this->load->model('cargamodel',"CargaModel");
		
	}
		
	public function getCidades($id) {
		
		$this->load->model('cidades_model');
		
		$cidades = $this->cidades_model->getCidades($id);
		
		if( empty ( $cidades ) ) 
			return '{ "NOME": "Nenhuma cidade encontrada" }';
			
		$arr_cidade = array();

		foreach ($cidades as $cidade) {			
			$arr_cidade[] = '{"ID":' . $cidade->ID . ',"NOME":"' . $cidade->NOME . '"}';			 	
		}
		
		echo utf8_encode('[ ' . implode(",", $arr_cidade) . ']');				
		return;		
	}
	
	public function index()
	{
		$this->load->model('cidades_model');
		$var['estados'] = $this->cidades_model->getEstados();			
		$var['transportadoras']= $this->TransportadoraModel->transportadoraTodas();
		$var['clientes']= $this->ClienteModel->clienteTodos();		
		$var['cargasAutorizadas']= $this->CargaModel->cargasAutorizadas();
		$var['cargasPendentes']= $this->CargaModel->cargasPendentes();
		$var['cargasSaidas']= $this->CargaModel->cargasSaidas();
		$var['veiculosRecusados']= $this->CargaModel->veiculosRecusados();
		$var['PortariaOk']= false;
		$this->load->view('portaria', $var);
	}
	
	public function tabela()
	{		
		$this->load->view('datatable');
	}
	
	function transportadoras(){		
		$term = $_POST['term'];
		$lider = $this->TransportadoraModel->get_transportadora($term);
		$arr = array();
		foreach($lider as $row)
		{
			$arr[] = $row['CNPJ']." - ".$row['NOME']." - ".$row['TIPO']." - ".$row['ID'];			
		}		
        echo json_encode($arr);		 
	}
	
	function motorista(){		
		$termo = $_POST['term'];
		$lider = $this->TransportadoraModel->get_motorista($termo);
		$arr = array();
		foreach($lider as $row)
		{
			$arr[] = $row['CPF']." - ".$row['NOM_RECEBEDOR']." - ".$row['COD_RECEBEDOR'];		
		}		
        echo json_encode($arr);			 
	}	
	
	function inserir_motorista(){		
		$termo = array('cpf_motorista' => $_POST['cpf'],
					   'nome_motorista' => $_POST['nome'],
					   'data_cadastro' => $_POST['d']);			
		
		$lider = $this->TransportadoraModel->set_motorista($termo);
		
		
		echo $lider;		 
	}
			
	public function checked($var){		
		if (!empty($var)){
			$this->form_validation->set_message('checked', '%s não está cadastrado!');
			return FALSE;
		} else {
			return TRUE;
		}
	}	
	
	public function entrada()
	{	
		ob_start();
		$data_atual = date("Y-m-d");
		$hora_atual = date("H:i");	
		
		
		$tipo = explode('-', $_POST['ID_TRANSPORTADORA']);
		
		
		if(!empty($tipo[0])){
			@$tipo_trans = trim($tipo['2']); //TIPO_TRANSPORTADORA: [C] = Cliente ou [T] = Transportadora			
			
		} else {
			$tipo_trans = $tipo[0];
		}
		
		
		$trans = explode('-', $_POST['ID_TRANSPORTADORA']);
		
		
		if(!empty($trans[3])){
			@$id_trans = trim($trans['3']);	// ID_TRANSPORTADORA
			
		} else {
			$id_trans = null;
			$this->checked($id_trans);
			$this->form_validation->set_rules('ID_TRANSPORTADORA', 'Transportadora', 'callback_checked');
		}
		
		$cpf_motorista = explode('-', $_POST['CPF_MOTORISTA1']);
		$cpf = trim($cpf_motorista['0']);		
		
		$nom_motorista = explode('-', $_POST['CPF_MOTORISTA1']);
		if(!empty($nom_motorista)){
			@$nome_motorista = trim($nom_motorista['1']);						
		}
		
		$codi_recebedor = explode('-', $_POST['CPF_MOTORISTA1']);
		if(!empty($codi_recebedor[2])){
			@$cod_motorista = trim($codi_recebedor['2']);
			
		} else {
			@$cod_motorista = null;
			$this->checked($cod_motorista);
			$this->form_validation->set_rules('CPF_MOTORISTA1', 'CPF do Motorista', 'callback_checked');				
			//echo "NADA";
		}
		
		if(!empty($cod_motorista)){
			$cod_motorista;
		} else {
			$cod_motorista = $_POST['cod_recebedor'];			
		}		
						
		$cargaPost['row'] = $_POST;
		$this->load->model('cidades_model');
		$cargaPost['estados'] = $this->cidades_model->getEstados();	
		$cargaPost['transportadoras']= $this->TransportadoraModel->transportadoraTodas();		
		$cargaPost['cargasAutorizadas']= $this->CargaModel->cargasAutorizadas();
		$cargaPost['cargasPendentes']= $this->CargaModel->cargasPendentes();		
		$cargaPost['PortariaOk']= false;	
		
		if($cod_motorista and $id_trans){
			
			$carga = array(
				'CPF_MOTORISTA' => $cpf,
				'NOME_MOTORISTA' => $nome_motorista,
				'COD_RECEBEDOR' => $cod_motorista,				
				'TIPO_OPERACAO' => $this->input->post("TIPO_OPERACAO"),
				'TELEFONE_CONTATO' => $this->input->post("TELEFONE_CONTATO"),				
				'DATA_ATUAL' =>  "TO_DATE('".$data_atual."','YYYY-MM-DD')", //$data_atual,
				'ID_TRANSPORTADORA' => $id_trans,
				'ESTADO' => $this->input->post("estado"),
				'CIDADE' => $this->input->post("cidade"),				
				'VEICULO' => $this->input->post('VEICULO'),
				'PLACA_VEICULO' => $this->input->post('PLACA_VEICULO'),
				'PLACA_CAVALO' => $this->input->post('PLACA_CAVALO'),
				'NUM_CONTAINER' => $this->input->post('NUM_CONTAINER'),
				'HORA_CHEGADA' =>  $hora_atual, //"TO_DATE('".$hora_atual."','HH24:MI:SS')", //$hora_atual,
				'STATUS' => "LOG",		
				'USUARIO_PORTARIA' => $this->session->userdata('idUser'),
				'TIPO_TRANSPORTADORA' => $tipo_trans
			);
			
					
			//Inicio do Processo - Cadastro das informações pela Portaria;
			$valido = $this->CargaModel->valida_carga($carga);
			if($valido==true)
			{
				$this->CargaModel->insert($carga);
			}

			$this->load->model('cidades_model');
			$var['estados'] = $this->cidades_model->getEstados();	
			$var['transportadoras']= $this->TransportadoraModel->transportadoraTodas();
			$var['clientes']= $this->ClienteModel->clienteTodos();
			
			// Lista todas as cargas autorizada
			$var['cargasAutorizadas']= $this->CargaModel->cargasAutorizadas();
			// Lista todas as caragas pendentes de autorização
			$var['cargasPendentes']= $this->CargaModel->cargasPendentes();

			
			$var['PortariaOk']= true;			
			redirect('inicio#page','refresh');		
			
		} else {
			if ($this->form_validation->run() == false){
				$this->load->view('portaria', $cargaPost);				
			}
			
		}
		ob_end_flush();
		
		
	}
	
	public function tab_transporte()
	{
		$this->load->model('cidades_model');
		$var['estados'] = $this->cidades_model->getEstados();	
		$var['transportadoras']= $this->TransportadoraModel->transportadoraTodas();
		$var['clientes']= $this->ClienteModel->clienteTodos();			
		$this->load->view('tab_transporte', $var);
		
	}
	
	public function tab_cliente()
	{
		$this->load->model('cidades_model');
		$var['estados'] = $this->cidades_model->getEstados();	
		$var['transportadoras']= $this->TransportadoraModel->transportadoraTodas();
		$var['clientes']= $this->ClienteModel->clienteTodos();		
		$this->load->view('tab_cliente', $var);
		
	}	
	
	public function autorizados()
	{
		$var['cargasAutorizadas']= $this->CargaModel->cargasAutorizadas();		
		$this->load->view('autorizados', $var);		
	}
	
	// Dados do Motorista
	public function dados_motorista($id_carga)
	{		
		if ($id_carga) {
			$var['carga'] = $this->CargaModel->cargasConferi($id_carga);		
		}				
		$this->load->view('dados_motorista', $var);
	}

	public function recusa_entrada($id_carga)
	{
		if($id_carga){
			$var ['carga'] = $id_carga;
			$this->load->view('recusa_entrada',$var);
		}
		
	}
	
	public function pendentes()
	{		
		$var['cargasPendentes']= $this->CargaModel->cargasPendentes();		
		$this->load->view('pendentes', $var);
	}
	
	public function liberadas()
	{		
		$var['cargasLiberadas']= $this->CargaModel->cargasLiberadas();		
		$this->load->view('liberadas', $var);	
	}
	
	
	public function listagem_saidas()
	{		
		$var['cargasSaidas']= $this->CargaModel->cargasSaidas();		
		$this->load->view('saidas_expedicao', $var);		
	}
	
	public function liberar($id_carga)
	{			
		if ($id_carga) {		
			$hora_entrada = date("Y-m-d H:i:s");					
			$aux = array('STATUS' => "LIB", 'HORA_ENTRADA' =>  "TO_DATE('".$hora_entrada."','YYYY-MM-DD HH24:MI:SS')");			
	    	$var['cargasAutorizadas'] = $this->CargaModel->liberar($id_carga, $aux);
		}		
		redirect('/inicio#tab-3', 'refresh');				
	}
	
	public function saida($id_carga)
	{		
		if ($id_carga) {
			$aux = array('STATUS' => "SAI");			
	    	$var['cargasLiberadas'] = $this->CargaModel->liberar_saida($id_carga, $aux);
		}
		
		$var['cargasLiberadas']= $this->CargaModel->cargasLiberadas();
		$var['cargasExpedicao'] = $this->CargaModel->cargasExpedicao();	
		$this->load->view('expedicao', $var);		
		redirect('/expedicao#tab-2', 'refresh');				
	}
	public function encaminha_saida($id_carga)
	{		
		if ($id_carga) {
			$aux = array('STATUS' => "SAI");			
	    	$var['cargasLiberadas'] = $this->CargaModel->liberar_saida($id_carga, $aux);
		}
		
		$var['cargasLiberadas']= $this->CargaModel->cargasLiberadas();
		$var['cargasExpedicao'] = $this->CargaModel->cargasExpedicao();		
		redirect('/inicio#tab-6', 'refresh');				
	}
	
	public function saida_expedicao($id_carga)
	{			
		if ($id_carga) {		
			$hora_saida = date("Y-m-d H:i:s");					
			$aux = array('STATUS' => "TRA", 'HORA_SAIDA' =>  "TO_DATE('".$hora_saida."','YYYY-MM-DD HH24:MI:SS')");			
	    	$var['cargasAutorizadas'] = $this->CargaModel->liberar_tra($id_carga, $aux);			
		}
		redirect('/inicio#tab-4', 'refresh');				
	}

	public function diferenca_peso($id_carga)
	{			
		if ($id_carga) {		
			$aux = array('STATUS' => "DIF");			
	    	$var['cargasAutorizadas'] = $this->CargaModel->diferenca_peso($id_carga, $aux);			
		}
		redirect('/inicio#tab-4', 'refresh');				
	}

	public function motorista1(){		
			$var['motorista']= $this->TransportadoraModel->get_motorista();
			print_r($var['motorista']);
	}

	public function duplicidades()
	{
		$placa_veiculo = $_POST['placa_veiculo'];		
		$retorno = $this->TransportadoraModel->duplicidades($placa_veiculo);		
		echo ($retorno);	
	}

	function local_descarregamento()
	{
		$var['id_carga'] = $this->uri->segment('3');
		$this->load->view('local_carga',$var);
	}
	function gera_descarregamento()
	{
		$info['registro'] = $_POST['carga_editada'];
		$info['local_descarga'] = $_POST['opcao_descarregamento']; 
		
		$this->CargaModel->processa_descarga($info);

		$this->saida_expedicao($info['registro']);
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */