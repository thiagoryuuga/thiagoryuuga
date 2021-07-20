<?php
class ProdutoModel extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
    }

	function ProdutosCadastrados()
	{
		$valores = $this->db->query("select cod_produto,desc_produto, cod_ean, altura, largura, comprimento, peso, cubagem,TO_CHAR(DATA_IMPORT,'DD/MM/YYYY') DATA_IMPORT
 														from ESMALTEC.ESM_LOGISTICA_CARGA_CUBAGEM WHERE STATUS = 'A'")->result_array();
														return $valores;
	}
	
			
}
?>