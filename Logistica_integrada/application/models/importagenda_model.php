<?php
class Importagenda_Model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this -> load -> database();
		$this->load->library('Spreadsheet_Excel_Reader');

	}

	function checkCodEbs ($linha, $ordem){
		
		/*$sql = "SELECT COD_AGENDAMENTO FROM ESM_LOGISTICA_AGENDAMENTO
				WHERE 
					LINHA = ".$linha." 
				AND
					ORDEM = '".$ordem."'
				AND 
					STATUS <> 'C'					
				";		
		
		$query = $this->db->query($sql);
		
		return $query->num_rows();*/	
		
	}
	
	function importar($files, $parametros){
	
		$filial = $parametros['filial'];
		$mat_agendador =  $this->session->userdata('idRegister');
		$data_atual =  date('Y-m-d');
			
		
		$this->db->trans_start();
		
		$data = new Spreadsheet_Excel_Reader($_FILES['arquivo']['tmp_name']);
		
		if($data->rowcount($sheet_index=0) == 0){
			//echo "não tem";
			echo('Erro: falha de importação ou modelo de arquivo não aceito');							
			$retorno = false;			
		} else {
			//echo "tem";
			
			for( $i=2; $i <= $data->rowcount($sheet_index=0); $i++ ){
								
				$var1 = $this->checkCodEbs("", "");				
					
											
				if($var1 == 0){
				
				$COD_CARRETA = explode('/',$data->val($i,14));
				if (strlen($data->val($i,13)) < 3)
				{
					$NUM_CARRETA =str_pad($data->val($i,13), 3, "0", STR_PAD_LEFT);  
				}
				else
				{
					$NUM_CARRETA =($data->val($i,13));  
				}
				$COD_CARRETA_FORMAT = substr($COD_CARRETA[2],2,2).$COD_CARRETA[1].$COD_CARRETA[0].$NUM_CARRETA;
				
				
									
				/*echo("Regional: val($i,1) ".$data->val($i,1));
				echo("<br>");
				echo("Data de Solicitação:val($i,2) ".$data->val($i,2));
				echo("<br>");
				echo("Cliente para Entrega val($i,3) ".$data->val($i,3));
				echo("<br>");
				echo("Ordem val($i,4) ".$data->val($i,4));
				echo("<br>");
				echo("OC_cliente: val($i,5)".$data->val($i,5));
				echo("<br>");
				echo("Cod. Item: val($i,6)".$data->val($i,6));
				echo("<br>");
				echo("Desc. Item: val($i,7)". $data->val($i,7));
				echo("<br>");
				echo("Quantidade: val($i,8) ".$data->val($i,8));
				echo("<br>");
				echo("Reservas:val($i,9)".$data->val($i,9));
				echo("<br>");
				echo("Linhas: val($i,10)".$data->val($i,10));
				echo("<br>");
				echo("Retenções: val($i,11) ".$data->val($i,11));
				echo("<br>");
				echo("Estoque: val($i,12) ".$data->val($i,12));
				echo("<br>");
				echo("Carreta: val($i,13) ".$data->val($i,13));
				echo("<br>");
				echo("Agenda: val($i,14)".$data->val($i,14));
				echo("<br>");
				echo("Valor Un: val($i,15) ".$data->val($i,15));
				echo("<br>");
				
				print_r ($COD_CARRETA);
				echo("<br>");
				echo("<br>");*/


				
				$sql="insert into ESMALTEC.ESM_LOGISTICA_AGENDA_IMPORT 
							(
							COD_AGENDAMENTO,
							REGIONAL,
							DATA_SOLICITACAO,
							CLIENTE_ENTREGA,
							ORDEM_VENDA,
							OC_CLIENTE,
							COD_ITEM,
							DEN_ITEM,
							QTD,
							RESERVA,
							LINHA,
							RETENCAO,
							LOCAL_CARGA,
							COD_CARRETA,
							DATA_AGENDAMENTO,
							VALOR_UNITARIO,
							FILIAL,
							DATA_CADASTRO,
							STATUS)
							VALUES
							(
							ESM_LOGISTICA_AGEND_IMPORT_SEQ.NEXTVAL,
							'".$data->val($i,1)."',
							'".$data->val($i,2)."',
							'".$data->val($i,3)."',
							'".$data->val($i,4)."',
							'".$data->val($i,5)."',
							'".$data->val($i,6)."',
							'".$data->val($i,7)."',
							'".$data->val($i,8)."',
							'".$data->val($i,9)."',
							'".$data->val($i,10)."',
							'".$data->val($i,11)."',
							'".$data->val($i,12)."',
							'".$COD_CARRETA_FORMAT."',
							'".$data->val($i,14)."',
							'".$data->val($i,15)."',
							'".$filial."',
							SYSDATE,
							'A')";
						//print_r($sql);

						//die();
					$this->db->query($sql);	
					//$COD_REUTILIZADO =	$this->db->query('Select max(cod_agendamento) cod_agendamento from esm_logistica_agendamento')->result_array();
						
					//$sql2 = "INSERT INTO ESM_LOGISTICA_ACOES_AGEND(COD_AGENDAMENTO, NUM_MATRICULA, DATA_ACAO, ACAO) VALUES(".$COD_REUTILIZADO[0]
					//['COD_AGENDAMENTO'].",".$this->session->userdata('idRegister').", sysdate ,'AGENDAMENTO') ";					
						
					
				//	$this->db->query($sql2);
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
	}

}
