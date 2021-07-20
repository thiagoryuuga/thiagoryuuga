<?php
class TransportadoraModel extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
		$this->load->database();			
    }
	
	function detalhar($id_transportadora = 0) {
		return $this->db->get_where('ESM_LOGISTICA_TRANSPORTADORA', array('ID_TRANSPORTADORA' => $id_transportadora))->row_array();
	}
	
	function insert($transportadora)
	{
		$this->db->insert('ESM_LOGISTICA_TRANSPORTADORA', $transportadora);
	}
	
	function deletar($id_transportadora)
	{
		$this->db->delete('ESM_LOGISTICA_TRANSPORTADORA', array('ID_TRANSPORTADORA' => $id_transportadora));
	}
	
	function update($id_transportadora, $transportadora)
	{
		$this->db->where('ID_TRANSPORTADORA', $id_transportadora);
		$this->db->update('ESM_LOGISTICA_TRANSPORTADORA', $transportadora);
	}
	
	function tran($id_carga)
	{
		$sql = "select ID_TRANSPORTADORA from ESM_LOGISTICA_CARGA where ID_CARGA = ".$id_carga."";
		$query = $this->db->query($sql);						
		return $query->result_array();
	}
	
	function transportadoraTodas()
	{
		
		/*
		$this->db->select('*');
		$this->db->from('ESM_LOGISTICA_TRANSPORTADORA');
		$this->db->order_by('ID_TRANSPORTADORA DESC');
		return $this->db->get()->result_array();	
		*/
		
		
		$sql = "
				SELECT * FROM (
				SELECT T.ID_TRANSPORTADORA ID_TRANSPORTADORA,
					T.CNPJ_TRANSPORTADORA CNPJ_TRANSPORTADORA,  
					T.NOME_TRANSPORTADORA NOME_TRANSPORTADORA, 
					'T' TIPO
				FROM ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
				UNION 
					SELECT  C.ID_CLIENTE ID_TRANSPORTADORA,
					C.CGCCPF_CLIENTE CNPJ_TRANSPORTADORA, 
					C.NOME_CLIENTE NOME_TRANSPORTADORA  ,
					'C' TIPO
				FROM ESMALTEC.ESM_LOGISTICA_CLIENTE C )
				ORDER BY NOME_TRANSPORTADORA";
		
		$query = $this->db->query($sql);				
		return $query->result_array();		
	}
	
	
	
	function get_transportadora($nome)
	{					
		$filtro = "";
		if($nome){
			$filtro = "'%".strtoupper($nome)."%'";
		}
		
		$sql = "
				SELECT * FROM (
				SELECT T.ID_TRANSPORTADORA ID,
					T.CNPJ_TRANSPORTADORA CNPJ,  
					T.NOME_TRANSPORTADORA NOME, 
					'T' TIPO
				FROM ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
				WHERE T.NOME_TRANSPORTADORA LIKE ".$filtro."
				UNION 
					SELECT  C.ID_CLIENTE ID,
					C.CGCCPF_CLIENTE CNPJ, 
					C.NOME_CLIENTE NOME  ,
					'C' TIPO
				FROM ESMALTEC.ESM_LOGISTICA_CLIENTE C
				WHERE C.NOME_CLIENTE LIKE ".$filtro.")
				ORDER BY NOME
		";
		
		$query = $this->db->query($sql);				
		return $query->result_array();
		
		
		
		
	} 
	
	function get_motorista($cpf = false)
	{
		$DB1 = $this->load->database('LOGIX', TRUE);
		
		$filtro = "";
		
		if($cpf){
			$filtro = "'%".strtoupper($cpf)."%'";
		}
		
		$sql = "select * from ESM_RECEB_NFE where CPF LIKE ".$filtro." ORDER BY NOM_RECEBEDOR";
		
		$resultado = $DB1->query($sql)->result_array();		
		return $resultado;
		
	}
	
	function set_motorista($termo)
	{
		$DB1 = $this->load->database('LOGIX', TRUE);		
		
		$sql = "INSERT INTO ESM_RECEB_NFE
					(CPF, NOM_RECEBEDOR, DATE_CADASTRO) 
				VALUES 
					('".$termo['cpf_motorista']."', '".$termo['nome_motorista']."', TO_DATE('".$termo['data_cadastro']."','DD/MM/YYYY HH24:MI:SS'))
				";			
		
		$resultado = $DB1->query($sql);	
		
		$cod_recebedor = "SELECT COD_RECEBEDOR FROM ESM_RECEB_NFE WHERE NOM_RECEBEDOR = '".$termo['nome_motorista']."'";		
		$result = $DB1->query($cod_recebedor)->row_array();		
		return $result['COD_RECEBEDOR'];
		
	}

	function duplicidades($param)
	{
		
		$sql ="select count(id_carga) contagem from esmaltec.esm_logistica_carga where upper(placa_veiculo) = upper('".$param."') and status not in ('TRA','CAN')";
		
		$dados = $this->db->query($sql)->result_array;
		
		if ($dados[0]['CONTAGEM'] > 0)
		{
			return 'blocked';
		}
		else
		{
			return 'ok';
		}
	}
	
}
?>