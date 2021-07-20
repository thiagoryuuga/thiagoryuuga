<?php
class ClienteModel extends CI_Model {
	
	function detalhar($id_cliente = 0) {
		return $this->db->get_where('ESM_LOGISTICA_CLIENTE ', array('ID_CLIENTE' => $id_cliente))->row_array();
	}
	
	function insert($cliente)
	{
			$this->db->query("
					INSERT INTO ESM_LOGISTICA_CLIENTE
						(ID_CLIENTE, NOME_CLIENTE, CGCCPF_CLIENTE) 
					VALUES 
						(SEQ_ESM_LOGISTICA_CLIENTE.NEXTVAL,
						'".$cliente['NOME_CLIENTE']."',
						'".$cliente['CGCCPF_CLIENTE']."'									
						)
					"); 		
		
		return true;
	}
	
	function deletar($id_cliente)
	{
		$this->db->delete('ESM_LOGISTICA_CLIENTE ', array('ID_CLIENTE' => $id_cliente));
	}
	
	function update($id_cliente, $cliente)
	{
		
		$this->db->query("UPDATE ESM_LOGISTICA_CLIENTE	
								SET 
								NOME_CLIENTE = '".$cliente['NOME_CLIENTE']."',								
								CGCCPF_CLIENTE = '".$cliente['CGCCPF_CLIENTE']."'															
								WHERE ID_CLIENTE = '".$id_cliente."'");
		
		return true;
	}
	
	function clienteTodos()
	{
		$this->db->select('ID_CLIENTE, NOME_CLIENTE, CGCCPF_CLIENTE');
		$this->db->from('ESM_LOGISTICA_CLIENTE');
		$this->db->order_by('NOME_CLIENTE ASC');
		//$this->db->limit(200);
		return $this->db->get()->result_array();		
	}
	
	


}
?>