<?php
class ImportProduto_Model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this -> load -> database();
		$this->load->library('Spreadsheet_Excel_Reader');
		$this->load->library('Excel');
	}
	

	function importar($files, $parametros)
	{
		
		$objPHPExcel= $objReader = PHPExcel_IOFactory::createReaderForFile($files['arquivo']['tmp_name'],false); //identifica o tipo de arquivo e habilita o leitor compativel
		$objPHPExcel =$objReader->setReadDataOnly(true); //define a tabela para leitura apenas dos dados
		$objPHPExcel = $objReader->load($files['arquivo']['tmp_name'],false); // copia para uma area de trabalho temporaria
		$objPHPExcel->setActiveSheetIndex(0); //define qual pasta de trabalho deverá estar ativa para importacao
        $objWorksheet = $objPHPExcel->getActiveSheet();  //lê a planilha ativa definida no padrão setActive
		
		
 
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();  //recebe o titulo do arquivo
			$highestRow         = $worksheet->getHighestRow(); // busca a ultima linha escrita
			$highestColumn      = $worksheet->getHighestColumn(); // busca a ultima coluna
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); //busca o titulo de cada coluna
			$nrColumns = ord($highestColumn) - 64; //limita a qtd de colunas a serem lidas
          
		               
					for ($row = 2; $row <= $highestRow; ++ $row)  //varrendo desde a linha indicada ate a ultima linha que contenha dados
						{
							$val=array();  								
								for ($col = 0; $col < $highestColumnIndex; ++ $col)  // varrendo cada uma das colunas que contenham dados
						{
					$cell = $worksheet->getCellByColumnAndRow($col, $row);  //define a celula a ser lida e atribui a um indice dentro de $val[]
					$val[] = $cell->getValue();
					
				}
				$sqlOrigem="SELECT COUNT(COD_PRODUTO) AS CONTAGEM FROM ESM_LOGISTICA_CARGA_CUBAGEM WHERE TRIM(COD_PRODUTO)=TRIM('".$val[0]."')";
				$valores = $this->db->query($sqlOrigem)->result_array();
				
				
				if($valores[0]['CONTAGEM']==0)
				{		
				$sql="INSERT INTO ESMALTEC.ESM_LOGISTICA_CARGA_CUBAGEM
		                 (
						 ID_IMPORT,
						 COD_PRODUTO,
						 DESC_PRODUTO,
						 ALTURA,
						 LARGURA,
						 COMPRIMENTO,
						 PESO,
						 CUBAGEM,
						 DATA_IMPORT,
						STATUS) 
	            	VALUES 
	                    (
						 '".$this->session->userdata('idUser')."',
						TRIM(REPLACE('".$val[0]."',' ','')),
  						'".$val[1]."',
						TRIM(REPLACE('".$val[3]."',',','.')),
						TRIM(REPLACE('".$val[4]."',',','.')),
						TRIM(REPLACE('".$val[5]."',',','.')),
						TRIM(REPLACE('".$val[6]."',',','.')),
						TRIM(REPLACE('".$val[7]."',',','.')),
						SYSDATE,'A'										
						)";
						$this->db->query($sql);
				}
				else
				{
					$sqlUpdate ="UPDATE ESM_LOGISTICA_CARGA_CUBAGEM SET STATUS ='I' WHERE TRIM(COD_PRODUTO)=trim('".$val[0]."')";
					$this->db->query($sqlUpdate); 
					
					$sql="INSERT INTO ESMALTEC.ESM_LOGISTICA_CARGA_CUBAGEM
		                 (
						 ID_IMPORT,
						 COD_PRODUTO,
						 DESC_PRODUTO,
						
						 ALTURA,
						 LARGURA,
						 COMPRIMENTO,
						 PESO,
						 CUBAGEM,
						 DATA_IMPORT,
						STATUS) 
	            	VALUES 
	                    (
						 '".$this->session->userdata('idUser')."',
						TRIM(REPLACE('".$val[0]."',' ','')),
  						'".$val[1]."',
						trim(TRIM(REPLACE('".$val[3]."',',','.'))),
						trim(TRIM(REPLACE('".$val[4]."',',','.'))),
						trim(TRIM(REPLACE('".$val[5]."',',','.'))),
						trim(TRIM(REPLACE('".$val[6]."',',','.'))),
						trim(TRIM(REPLACE('".$val[7]."',',','.'))),
						SYSDATE,'A'										
						)";
																						
					$this->db->query($sql);	
				}
				
		}
		
				
				
	}
	
}

	/*	
	function importar($files, $parametros){
	
			
		$this->db->trans_start();
		
		$data = new Spreadsheet_Excel_Reader ($_FILES['arquivo']['tmp_name']);
		
		if($data->rowcount($sheet_index=0) == 0){
			//echo "não tem";
			echo('Erro: falha de importação ou modelo de arquivo não aceito');							
			$retorno = false;			
		} else {
			//echo "tem";
			
			//$sql="truncate table ESMALTEC.ESM_LOGISTICA_CARGA_CUBAGEM";
			
			//$this->db->query($sql);
			
			for( $i=2; $i <= $data->rowcount($sheet_index=0); $i++ ){
				
				$sqlOrigem="SELECT COUNT(COD_PRODUTO) AS CONTAGEM FROM ESM_LOGISTICA_CARGA_CUBAGEM WHERE TRIM(COD_PRODUTO)=TRIM('".$data->val($i,1)."')";
				$valores = $this->db->query($sqlOrigem)->result_array();
				
				
				if($valores[0]['CONTAGEM']==0)
				{				
				$sql="INSERT INTO ESMALTEC.ESM_LOGISTICA_CARGA_CUBAGEM
		                 (
						 ID_IMPORT,
						 COD_PRODUTO,
						 DESC_PRODUTO,
						 COD_EAN,
						 ALTURA,
						 LARGURA,
						 COMPRIMENTO,
						 PESO,
						 CUBAGEM,
						 DATA_IMPORT,
						STATUS) 
	            	VALUES 
	                    (
						 '".$this->session->userdata('idUser')."',
						TRIM(REPLACE('".$data->val($i,1)."',' ','')),
  						'".$data->val($i,2)."',
						TRIM(REPLACE('".$data->val($i,3)."',' ','')),
						TRIM(REPLACE('".$data->val($i,4)."',',','.')),
						TRIM(REPLACE('".$data->val($i,5)."',',','.')),
						TRIM(REPLACE('".$data->val($i,6)."',',','.')),
						TRIM(REPLACE('".$data->val($i,7)."',',','.')),
						TRIM(REPLACE('".$data->val($i,8)."',',','.')),
						SYSDATE,'A'										
						)";
																						
					$this->db->query($sql);	
				}
				else
				{
					$sqlUpdate ="UPDATE ESM_LOGISTICA_CARGA_CUBAGEM SET STATUS ='I' WHERE TRIM(COD_PRODUTO)=trim('".$data->val($i,1)."')";
					$this->db->query($sqlUpdate); 
					
					$sql="INSERT INTO ESMALTEC.ESM_LOGISTICA_CARGA_CUBAGEM
		                 (
						 ID_IMPORT,
						 COD_PRODUTO,
						 DESC_PRODUTO,
						 COD_EAN,
						 ALTURA,
						 LARGURA,
						 COMPRIMENTO,
						 PESO,
						 CUBAGEM,
						 DATA_IMPORT,
						STATUS) 
	            	VALUES 
	                    (
						 '".$this->session->userdata('idUser')."',
						TRIM(REPLACE('".$data->val($i,1)."',' ','')),
  						'".$data->val($i,2)."',
						TRIM(REPLACE('".$data->val($i,3)."',' ','')),
						TRIM(REPLACE('".$data->val($i,4)."',',','.')),
						TRIM(REPLACE('".$data->val($i,5)."',',','.')),
						TRIM(REPLACE('".$data->val($i,6)."',',','.')),
						TRIM(REPLACE('".$data->val($i,7)."',',','.')),
						TRIM(REPLACE('".$data->val($i,8)."',',','.')),
						SYSDATE,'A'										
						)";
																						
					$this->db->query($sql);	
				}
					
			}
					
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE) {
				$retorno = false;
			} else {
				$retorno = true;
			}
			
		}
		
	
		return $retorno;
	}*/
	

}
