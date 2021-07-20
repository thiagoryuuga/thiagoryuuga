<?php
class Tipo_Model extends CI_Model  {
    
	function __construct()
    {
        parent::__construct();
		$this->load->database();
		
    }
	
	function getTipos($COD_TIPO = ""){
		$DB3 = $this->load->database('logix', TRUE);
		
		$filtro = "";
		if($COD_TIPO != ""){
			$filtro = "AND COD_TIPO_ATENDIMENTO = ".$COD_TIPO."";
		}
		
		$tipos = $DB3->query("SELECT COD_TIPO_ATENDIMENTO, 
									 DEN_TIPO_ATENDIMENTO, 
									 CASE STATUS_TIPO_ATENDIMENTO WHEN 'A' THEN 'ATIVO' WHEN 'I' THEN 'INATIVO' END
									 STATUS_TIPO_ATENDIMENTO
							    FROM ESM_RH_TIPO_ATENDIMENTO 
								WHERE NIVEL_AREA = ".$this->session->userdata('idLevel')." ".$filtro)->result_array();
		
		return $tipos;
	}
	
	function incluir($parametros){
		
		//print_r($parametros['DEN_TIPO_ATENDIMENTO']);
		//die();
		
		$DB3 = $this->load->database('logix', TRUE);
		
		$DB3->trans_start();
		
		
		$DB3->query("INSERT INTO ESM_RH_TIPO_ATENDIMENTO(COD_TIPO_ATENDIMENTO, DEN_TIPO_ATENDIMENTO, STATUS_TIPO_ATENDIMENTO, NIVEL_AREA) 
					 VALUES(ESM_RH_TIPO_ATENDIMENTO_SEQ.NEXTVAL, '".utf8_decode($parametros['DEN_TIPO_ATENDIMENTO'])."','".$parametros['STATUS_TIPO_ATENDIMENTO']."', ".$this->session->userdata('idLevel').")");
		//print_r($sql);
		//die();
		
		
		$DB3->trans_complete();
		if ($DB3->trans_status() === FALSE){ 
			$retorno = false;
		}else{
			$retorno = true;
		}
		
		return $retorno;		
	}	
	
	function alterar($parametros){
		$DB3 = $this->load->database('logix', TRUE);
		
		$DB3->trans_start();
		
		$DB3->query("UPDATE ESM_RH_TIPO_ATENDIMENTO SET DEN_TIPO_ATENDIMENTO = '".utf8_decode($parametros['DEN_TIPO_ATENDIMENTO'])."', 
					STATUS_TIPO_ATENDIMENTO = '".$parametros['STATUS_TIPO_ATENDIMENTO']."' WHERE COD_TIPO_ATENDIMENTO = '".$parametros['COD_TIPO_ATENDIMENTO']."'");
		
		$DB3->trans_complete();
		if ($DB3->trans_status() === FALSE){ 
			$retorno = false;
		}else{
			$retorno = true;
		}
		
		return $retorno;		
	}	
}