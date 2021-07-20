<?php 
class Cadastrar_Carreta extends CI_Controller{

    function __construct(){
        
        parent::__construct();
        $this->load->helper(array('form', 'url', 'funcoes'));	
		$this->load->library(array('form_validation','session'));
		$this->load->model('Carretas','CarretaModel');
        $var = array();
        
    }

    function index()
    { 
       $var['veiculos_cadastrados'] = $this->CarretaModel->Listar_Cadastrados();      
       $this->load->view('cadastrar_carreta',$var);      
    }

    function cadastra_carreta()
    {
        $info = array();
        $info['id_veiculo'] = $_POST['ID_VEICULO'];
		$info['placa_carreta'] = $_POST['PLACA_CARRETA'];
		$info['placa_cavalo'] = $_POST['PLACA_CAVALO'];
		$info['veiculo'] = $_POST['VEICULO'];               
		$retorno = $this->CarretaModel->cadastrar($info);

        if($retorno =='OK')
		{   
            $var['veiculos_cadastrados'] = $this->CarretaModel->Listar_Cadastrados();
            $var['msg_cadastro'] = 'Operação concluida com sucesso';
            $var['color_tag'] = 'green';
		}
        if($retorno =='ERROR')
        {
          $var['veiculos_cadastrados'] = $this->CarretaModel->Listar_Cadastrados();
          $var['msg_cadastro'] = 'Carreta ou cavalo já cadastrados. Por favor verificar informações!';
          $var['color_tag'] = 'yellow';
        }

        $this->load->view('cadastrar_carreta',$var);
        
	}

    function busca_carreta()
    {
        $placa_carreta = $_POST['term'];
        $valores = $this->CarretaModel->buscar_carreta($placa_carreta);
        foreach ($valores as $placas)
        {
            $array_placas[] = $placas['PLACA_CARRETA'];
        }
        if(!empty($array_placas))
        {
             print_r($array_placas[0]);
        } 
        else {
            $array_placas[0]="";
            print_r($array_placas[0]);
        }       
       
    }

    function busca_cavalo()
    {
        $placa_cavalo = $_POST['cavalo'];
        $placa_carreta = $_POST['carreta'];
        $valores = $this->CarretaModel->buscar_cavalo($placa_cavalo, $placa_carreta);
       $array_placas = array();
         foreach ($valores as $placas)
        {   
                    
            $array_placas[] = $placas['PLACA_CAVALO'];
        }
        echo json_encode($array_placas);
    }

    function busca_tipo()
    {
        $placa_cavalo = $_POST['placa_cavalo'];
        $dados = $this->CarretaModel->buscar_tipo($placa_cavalo);
        echo $dados[0]['TIPO_VEICULO'];
    }

    function excluir()
    {
       $id_veiculo = str_replace('_',' ',$this->uri->segment('3'));
       $retorno =  $this->CarretaModel->remove_placa($id_veiculo);
       redirect('/cadastrar_carreta/index', 'reload');     
    }   
    
}
?>