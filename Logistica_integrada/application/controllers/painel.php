<?php
class Painel extends CI_Controller{

    function __construct()
    {
        parent::__construct();
        
        $this->load->helper(array('form', 'url', 'funcoes', 'download'));	
		$this->load->library(array('form_validation','session'));
        $this->load->model('painel_model','Painel_Model');
       
    }

    function index()
    {
        if(isset($_POST['locais']) && !empty($_POST['locais']))
        {
           $locais = $_POST['locais'];
         $var['painel_saida'] = $this->Painel_Model->veiculos_saida($locais);
         $var['conta_veiculos_saida'] = $this->Painel_Model->conta_veiculos_saida($locais);
         $var['painel_docas'] = $this->Painel_Model->veiculos_docas($locais);
         $var['conta_veiculos_docas'] = $this->Painel_Model->conta_veiculos_docas($locais);
         $var['painel_entrada'] = $this->Painel_Model->veiculos_entrada($locais);
         $var['conta_veiculos_entrada'] = $this->Painel_Model->conta_veiculos_entrada($locais);
         $var['painel_chegada'] = $this->Painel_Model->veiculos_chegada($locais);
         $var['conta_veiculos_chegada'] = $this->Painel_Model->conta_veiculos_chegada($locais);
        }
        else {
             $var['painel_saida'] = $this->Painel_Model->veiculos_saida();
         $var['conta_veiculos_saida'] = $this->Painel_Model->conta_veiculos_saida();
         $var['painel_docas'] = $this->Painel_Model->veiculos_docas();
         $var['conta_veiculos_docas'] = $this->Painel_Model->conta_veiculos_docas();
         $var['painel_entrada'] = $this->Painel_Model->veiculos_entrada();
         $var['conta_veiculos_entrada'] = $this->Painel_Model->conta_veiculos_entrada();
         $var['painel_chegada'] = $this->Painel_Model->veiculos_chegada();
         $var['conta_veiculos_chegada'] = $this->Painel_Model->conta_veiculos_chegada();
            
        }
        
        
        $this->load->view('painel',$var);
    }
}

?>