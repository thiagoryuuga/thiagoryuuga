<?php
class AgendamentoModel extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
    }
	
	function cargaDetalhe($id_carga) {
		return $this->db->get_where('ESM_LOGISTICA_CARGA', array('ID_CARGA' => $id_carga))->row_array();
	}
	
	function getFilial(){
		$this->db->where('NUM_MATRICULA',$this->session->userdata('idRegister'));
		$filial = $this->db->get('ESM_LOGISTICA_USUARIO_FILIAL')->result_array();
		return $filial;
	}
	
	// 22/03/2013
	
	function cargasAgendamento($cod_agendamento = ""){
		$filtro = "";
		if($cod_agendamento != ""){
			$filtro = " AND COD_AGENDAMENTO = ".$cod_agendamento;	
		}
			
					
					/*alterado em 08/10/2015*/
					$sql="SELECT ELA.COD_CARRETA, 
										  ELA.REGIONAL, 
										  TO_CHAR(ELA.DATA_SOLICITACAO,'DD/MM/YYYY') DATA_SOLICITACAO, 
										  ELA.LOCAL_CARGA, 
										  CASE WHEN(TRIM(ELA.RETENCAO) LIKE '%*%') OR (UPPER(ELA.RETENCAO) LIKE('N%') OR ELA.RETENCAO IS NULL)
								THEN ('NAO')
								ELSE 'SIM' END as RETENCAO, 
								 RESERVA,
										  TO_CHAR(DATA_CADASTRO,'DD/MM/YYYY') DATA_CADASTRO, 
										  TRIM(TO_CHAR(ECC.CUBAGEM)) CUBAGEM, 
										  TRIM(TO_CHAR(ECC.PESO)) PESO, 
										  ELA.COD_AGENDAMENTO, 
										  ELA.LINHA, 
										  ELA.ORDEM_VENDA, 
										  ELA.OC_CLIENTE, 
										  ELA.CLIENTE_ENTREGA, 
										  trim(to_char(ELA.ENDERECO)) ENDERECO, 
										  ELA.COD_ITEM, 
										  ELA.DEN_ITEM, 
										  ELA.QTD, 
										  ELUF.DEN_FILIAL, 
										  TO_CHAR(ELA.DATA_AGENDAMENTO,'DD/MM/YYYY') DATA_AGENDAMENTO, 
										   ELD.COD_DISTRIBUICAO_EBS,
										  ELA.STATUS, 
										  to_char(ELA.DATA_LIMITE,'DD/MM/YYYY') DATA_LIMITE, 
										  trunc(ela.data_limite-sysdate) as restante,
										  data_agendamento -ELA.data_limite   as leadtime 
							   FROM ESM_LOGISTICA_AGENDAMENTO ELA, 
							   		     ESM_LOGISTICA_USUARIO_FILIAL ELUF, 
										 ESM_LOGISTICA_CARGA_CUBAGEM ECC,
										 ESM_LOGISTICA_DISTRIBUICAO ELD
							where trim (ECC.COD_PRODUTO) = TRIM(ELA.COD_ITEM) 
										AND ELUF.NUM_MATRICULA = '".$this->session->userdata('idRegister')."'
										AND ELUF.DEN_FILIAL = ELA.FILIAL
										AND ECC.STATUS = 'A'
										AND (ELD.ATRIBUIDO = 'S' or ELD.ATRIBUIDO is NULL)
										AND ELD.COD_AGENDAMENTO(+)= ELA.COD_AGENDAMENTO
					".$filtro." ORDER BY  ELA.COD_CARRETA ASC, ELA.DATA_CADASTRO ASC";
							
		 		
		$query = $this->db->query($sql);
		
		return $query->result_array();	
		
	}

	function cargasAgendamentoImport($cod_agendamento = ""){
		$filtro = "";
		if($cod_agendamento != ""){
			$filtro = " AND COD_AGENDAMENTO = ".$cod_agendamento;	
		}
			
					
					/*alterado em 08/10/2015*/
					$sql="SELECT ELA.COD_CARRETA, 
										  ELA.REGIONAL, 
										  TO_CHAR(ELA.DATA_SOLICITACAO,'DD/MM/YYYY') DATA_SOLICITACAO, 
										  ELA.LOCAL_CARGA, 
										  CASE WHEN(TRIM(ELA.RETENCAO) LIKE '%*%') OR (UPPER(ELA.RETENCAO) LIKE('N%') OR ELA.RETENCAO IS NULL)
								THEN ('NAO')
								ELSE 'SIM' END as RETENCAO, 
								 RESERVA,
										  TO_CHAR(DATA_CADASTRO,'DD/MM/YYYY') DATA_CADASTRO, 
										  TRIM(TO_CHAR(ECC.CUBAGEM)) CUBAGEM, 
										  TRIM(TO_CHAR(ECC.PESO)) PESO, 
										  ELA.COD_AGENDAMENTO, 
										  ELA.LINHA, 
										  ELA.ORDEM_VENDA, 
										  ELA.OC_CLIENTE, 
										  ELA.CLIENTE_ENTREGA, 
										  trim(to_char(ELA.ENDERECO)) ENDERECO, 
										  ELA.COD_ITEM, 
										  ELA.DEN_ITEM, 
										  ELA.QTD, 
										  ELUF.DEN_FILIAL, 
										  TO_CHAR(ELA.DATA_AGENDAMENTO,'DD/MM/YYYY') DATA_AGENDAMENTO, 
										   
										  ELA.STATUS, 
										  to_char(ELA.DATA_LIMITE,'DD/MM/YYYY') DATA_LIMITE, 
										  trunc(ela.data_limite-sysdate) as restante,
										  data_agendamento -ELA.data_limite   as leadtime 
							   FROM esm_logistica_agenda_import ELA, 
							   		     ESM_LOGISTICA_USUARIO_FILIAL ELUF, 
										 ESM_LOGISTICA_CARGA_CUBAGEM ECC 
							where trim (ECC.COD_PRODUTO) = TRIM(ELA.COD_ITEM) 
										AND ELUF.NUM_MATRICULA = '".$this->session->userdata('idRegister')."'
										AND ELUF.DEN_FILIAL = ELA.FILIAL
										AND ECC.STATUS = 'A'
					".$filtro." ORDER BY  ELA.COD_CARRETA ASC, ELA.DATA_CADASTRO ASC";
							
		 		
		$query = $this->db->query($sql);
		
		return $query->result_array();	
		
	}
	
	function resumoAgendamentos()
	{
		$sql = "select distinct(regional),
						to_char(data_solicitacao,'DD/MM/YYYY') DATA_SOLICITACAO,
						cliente_entrega, 
						cod_carreta,
						TO_CHAR(data_agendamento,'DD/MM/YYYY') DATA_AGENDAMENTO,
						TO_CHAR(data_limite,'DD/MM/YYYY') DATA_LIMITE, 
						(select sum(ag.qtd) from esm_logistica_agendamento ag where ag.cod_carreta = tbl1.cod_carreta) as total_itens,
						 sum(tbl1.peso_parcial) as total_peso, status, count(cod_distribuicao) DIS
						 from
						(select cod_agendamento, regional, cod_distribuicao,
			        	  data_solicitacao, cliente_entrega, 
						  elcc.peso,
					  (elcc.peso*qtd) peso_parcial,
					  ELCC.CUBAGEM, ordem_venda,
					   oc_cliente, cod_item,
					   den_item, 
					   qtd, 
					   linha, 
					   retencao, 
					   cod_carreta, 
					data_agendamento, local_carga, filial, data_cadastro, data_limite, ela.status
					from esmaltec.esm_logistica_agendamento ELA,ESMALTEC.ESM_LOGISTICA_CARGA_CUBAGEM ELCC where ela.cod_item = elcc.cod_produto and elcc.status='A'  AND ela.STATUS = 'A')tbl1 
					 group by regional,data_solicitacao,cliente_entrega, cod_carreta, data_agendamento,data_limite, status";
					 
					  $query = $this->db->query($sql);
					 return $query->result_array();
	}

	function resumoAgendamentosImport()
	{
		$sql = "select distinct(regional),
						to_char(data_solicitacao,'DD/MM/YYYY') DATA_SOLICITACAO,
						cliente_entrega, 
						cod_carreta,
						TO_CHAR(data_agendamento,'DD/MM/YYYY') DATA_AGENDAMENTO,
						TO_CHAR(data_limite,'DD/MM/YYYY') DATA_LIMITE, 
						(select sum(ag.qtd) from esm_logistica_agendamento ag where ag.cod_carreta = tbl1.cod_carreta) as total_itens,
						 sum(tbl1.peso_parcial) as total_peso, status, count(cod_distribuicao) DIS
						 from
						(select cod_agendamento, regional, cod_distribuicao,
			        	  data_solicitacao, cliente_entrega, 
						  elcc.peso,
					  (elcc.peso*qtd) peso_parcial,
					  ELCC.CUBAGEM, ordem_venda,
					   oc_cliente, cod_item,
					   den_item, 
					   qtd, 
					   linha, 
					   retencao, 
					   cod_carreta, 
					data_agendamento, local_carga, filial, data_cadastro, data_limite, ela.status
					from esmaltec.esm_logistica_agenda_import ELA,ESMALTEC.ESM_LOGISTICA_CARGA_CUBAGEM ELCC where ela.cod_item = elcc.cod_produto and elcc.status='A'  AND ela.STATUS = 'A')tbl1 
					 group by regional,data_solicitacao,cliente_entrega, cod_carreta, data_agendamento,data_limite, status";
					 
					  $query = $this->db->query($sql);
					 return $query->result_array();
	}
	
	function cargasAgendamentoDistribuicao(){
			
		
					/*alterado em 08/10/2015*/
					$sql="	
								SELECT 
								TO_CHAR(DATA_CADASTRO,'DD/MM/YYYY') DATA_CADASTRO, 
								ELA.COD_AGENDAMENTO,
								ELA.COD_CARRETA, 								
								TO_CHAR(ELA.DATA_LIMITE,'DD/MM/YYYY') 
								DATA_LIMITE,
								ELA.LINHA, ELA.ORDEM_VENDA, 
								ELA.OC_CLIENTE, 
								ELA.CLIENTE_ENTREGA, 
								ELD.COD_DISTRIBUICAO_EBS,
								 ELA.RESERVA,
								 ELA.REGIONAL,
								ELA.COD_ITEM,
								CASE WHEN(TRIM(ELA.RETENCAO) LIKE '%*%') OR (UPPER(ELA.RETENCAO) LIKE('N%') OR ELA.RETENCAO IS NULL)
								THEN ('NAO')
								ELSE 'SIM' END as RETENCAO, 
								ELA.DEN_ITEM, 
								ELA.QTD, 
								ELUF.DEN_FILIAL, 
								TO_CHAR(ELA.DATA_AGENDAMENTO,'DD/MM/YYYY') DATA_AGENDAMENTO, 
								ELA.STATUS, 								
								TO_CHAR(ELA.DATA_LIMITE,'YYYYMMDD') - TO_CHAR(SYSDATE,'YYYYMMDD') as RESTANTE, 
								TO_CHAR(ELA.DATA_AGENDAMENTO,'YYYYMMDD') - TO_CHAR(ELA.DATA_LIMITE,'YYYYMMDD') as LEADTIME 
								FROM ESM_LOGISTICA_AGENDAMENTO ELA, 
								ESM_LOGISTICA_DISTRIBUICAO ELD,
								ESM_LOGISTICA_USUARIO_FILIAL ELUF 
								WHERE ELA.FILIAL = ELUF.DEN_FILIAL AND 
								ELD.COD_AGENDAMENTO(+) = ELA.COD_AGENDAMENTO AND
								ELUF.NUM_MATRICULA = '".$this->session->userdata('idRegister')."' AND STATUS in ('A','D') ORDER BY DATA_CADASTRO ASC,ELA.cod_CARRETA ASC";		
		
		$query = $this->db->query($sql);
		
		return $query->result_array();	
			
	}

	function cargasAgendamentoDistribuicaoImport(){
			
		
					/*alterado em 08/10/2015*/
					$sql="	
								SELECT 
								TO_CHAR(DATA_CADASTRO,'DD/MM/YYYY') DATA_CADASTRO, 
								ELA.COD_AGENDAMENTO,
								ELA.COD_CARRETA, 								
								TO_CHAR(ELA.DATA_LIMITE,'DD/MM/YYYY') 
								DATA_LIMITE,
								ELA.LINHA, ELA.ORDEM_VENDA, 
								ELA.OC_CLIENTE, 
								ELA.CLIENTE_ENTREGA, 
								ELD.COD_DISTRIBUICAO_EBS,
								 ELA.RESERVA,
								 ELA.REGIONAL,
								ELA.COD_ITEM,
								CASE WHEN(TRIM(ELA.RETENCAO) LIKE '%*%') OR (UPPER(ELA.RETENCAO) LIKE('N%') OR ELA.RETENCAO IS NULL)
								THEN ('NAO')
								ELSE 'SIM' END as RETENCAO, 
								ELA.DEN_ITEM, 
								ELA.QTD, 
								ELUF.DEN_FILIAL, 
								TO_CHAR(ELA.DATA_AGENDAMENTO,'DD/MM/YYYY') DATA_AGENDAMENTO, 
								ELA.STATUS, 								
								TO_CHAR(ELA.DATA_LIMITE,'YYYYMMDD') - TO_CHAR(SYSDATE,'YYYYMMDD') as RESTANTE, 
								TO_CHAR(ELA.DATA_AGENDAMENTO,'YYYYMMDD') - TO_CHAR(ELA.DATA_LIMITE,'YYYYMMDD') as LEADTIME 
								FROM esm_logistica_agenda_import ELA, 
								ESM_LOGISTICA_DISTRIBUICAO ELD,
								ESM_LOGISTICA_USUARIO_FILIAL ELUF 
								WHERE ELA.FILIAL = ELUF.DEN_FILIAL AND 
								ELD.COD_AGENDAMENTO(+) = ELA.COD_AGENDAMENTO AND
								ELUF.NUM_MATRICULA = '".$this->session->userdata('idRegister')."' AND STATUS in ('A','D') ORDER BY DATA_CADASTRO ASC,ELA.cod_CARRETA ASC";		
		
		$query = $this->db->query($sql);
		
		return $query->result_array();	
			
	}
	
	function getJustificativa($cod_agendamento = ""){
		$justificativa = $this->db->query("SELECT JUSTIFICATIVA_DEVOLUCAO FROM ESM_LOGISTICA_ACOES_AGEND WHERE COD_AGENDAMENTO = '".$cod_agendamento."' order by DATA_ACAO DESC")->result_array();
		return $justificativa[0]['JUSTIFICATIVA_DEVOLUCAO'];		
		
	}
	
	function alterar($param){
		$this->db->query("UPDATE ESM_LOGISTICA_AGENDAMENTO 
						  SET DATA_AGENDAMENTO = TO_DATE('".$param['DATA_AGENDAMENTO']."','DD/MM/YYYY'),
						  	  QTD = ".$param['QTD'].",
							  STATUS = 'A'
						  WHERE COD_AGENDAMENTO = ".$param['COD_AGENDAMENTO']."");
						  
		$this->db->query("INSERT INTO ESM_LOGISTICA_ACOES_AGEND(COD_AGENDAMENTO, NUM_MATRICULA, DATA_ACAO, ACAO) VALUES(".$param['COD_AGENDAMENTO'].",".$this->session->userdata('idRegister').", sysdate ,'REAGENDAMENTO DA CARGA ".$param['COD_AGENDAMENTO']." PARA O DIA ".$param['DATA_AGENDAMENTO']."') ");
		return true;
	}
	
	function programacaoCancelar($cod_agendamento){
		
		$this->db->query("UPDATE ESM_LOGISTICA_AGENDAMENTO SET STATUS = 'C' WHERE COD_AGENDAMENTO = '".$cod_agendamento."'");
		$this->db->query("INSERT INTO ESM_LOGISTICA_ACOES_AGEND (COD_AGENDAMENTO, NUM_MATRICULA, DATA_ACAO, ACAO) VALUES(".$cod_agendamento.",".$this->session->userdata('idRegister').", sysdate ,'CANCELAMENTO') ");
		return true;
	}
	
	function distribuicoes($id_carga){
		
		$verifica = $this->verCargaDistribuicoes($id_carga);
				
		if (empty($verifica)){		
				
			
		    $sql= "SELECT DISTINCT ELD.COD_DISTRIBUICAO_EBS, ELD.COD_AGENDAMENTO,ELA.COD_CARRETA FROM ESM_LOGISTICA_DISTRIBUICAO ELD, ESM_LOGISTICA_AGENDAMENTO ELA
  						WHERE (ELD.COD_AGENDAMENTO = ELA.COD_AGENDAMENTO OR ELD.COD_AGENDAMENTO IS NULL) AND ELA.STATUS = 'B' AND ELD.ID_CARGA IS NULL";
			$query = $this->db->query($sql);
		
			return $query->result_array();	
			
		}
		
	}
	
	function distribuicaoCarga($param){
		$this->db->trans_start();
					
			foreach($param['distribuicoes'] as $row){
				$cod_dist = explode("-",$row);
				//print_r($param);
				//print_r ($cod_dist);
				
				
				$sql = ("UPDATE ESM_LOGISTICA_DISTRIBUICAO SET ID_CARGA = '".$param['id_carga']."', atribuido = 'S' where cod_agendamento = '".$cod_dist[1]."' and 
				cod_distribuicao_ebs = '".$cod_dist[0]."'" );
				//print_r($sql);
				//exit('encontrei a funcao');
				//die();
				$this->db->query($sql);
				
				
				//$this->db->query("INSERT INTO ESM_LOGISTICA_DISTRIBUICAO(COD_DISTRIBUICAO_EBS, ID_CARGA,ATRIBUIDO) VALUES (".$row.",".$param['id_carga'].",'S')");
				$this->db->query("UPDATE ESM_LOGISTICA_AGENDAMENTO SET STATUS = 'V' WHERE  COD_DISTRIBUICAO = ".$row."");
				$COD_AGENDAMENTO = $this->db->query("SELECT COD_AGENDAMENTO FROM ESM_LOGISTICA_AGENDAMENTO WHERE COD_DISTRIBUICAO = ".$row."")->result_array();
				foreach($COD_AGENDAMENTO as $agen){
					

					$sql_dist_carga = ("INSERT INTO ESM_LOGISTICA_ACOES_AGEND (COD_AGENDAMENTO, NUM_MATRICULA, DATA_ACAO, ACAO) VALUES(".$agen['COD_AGENDAMENTO'].",".$this->session->userdata('idRegister').", sysdate ,'VINCULACAO A CARGA ".$cod_dist[0]."') ");
					print_r($sql_dist_carga);
					die();
					exit();

					//$this->db->query($sql_dist_carga);
				}
			}
		$this->db->trans_complete();
				
		if ($this->db->trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}
		
		return $retorno;
		
	}
	
	function cargaDistribuicoes ($id_carga, $distribuicoes){
				
		for( $i=0; $i <= count($distribuicoes); $i++ ){		
			$this->db->query("UPDATE ESM_LOGISTICA_DISTRIBUICAO	 
							SET ID_CARGA = '".$id_carga."'									
							WHERE COD_DISTRIBUICAO_EBS = '".$distribuicoes[$i]."'");			
			return true;			
		}
	}
	
	function verCargaDistribuicoes($id_carga){
			
		
		$sql = "SELECT DISTINCT 
					   DIST.COD_DISTRIBUICAO_EBS ||' - '|| AGEN.QTD||'(Un) '|| AGEN.DEN_ITEM 	COD_DISTRIBUICAO_EBS				 
                FROM ESM_LOGISTICA_DISTRIBUICAO DIST, 
					 ESM_LOGISTICA_CARGA CARGA,
					 ESM_LOGISTICA_AGENDAMENTO AGEN
				WHERE DIST.ID_CARGA = CARGA.ID_CARGA
                    AND AGEN.COD_AGENDAMENTO = DIST.COD_AGENDAMENTO
                  AND DIST.ID_CARGA = '".$id_carga."' ";		
		
		
		//$sql = "select * from ESM_LOGISTICA_DISTRIBUICAO where ID_CARGA = '".$id_carga."'";
		
		
		$query = $this->db->query($sql);
		
		return $query->result_array();	
		
	}

	
	
	
function checkDistribuicaoEbs ($cod_distribuicao_ebs){
		
		/*$sql = "SELECT 
					COD_DISTRIBUICAO_EBS FROM ESM_LOGISTICA_DISTRIBUICAO
				WHERE 
					COD_DISTRIBUICAO_EBS IN  (".$cod_distribuicao_ebs.")";		
		
		$query = $this->db->query($sql);
		
		return $query->num_rows();*/	
		
	}
	
	function gerarDistribuicao ($cod_distribuicao_ebs, $cod_agendamento){
		
		
		//print_r($cod_agendamento);
		//print_r($cod_distribuicao_ebs);
		
	
		$nDistribuicao = (explode(',',$cod_distribuicao_ebs));
		
	$this->db->trans_start();
	 	
		for($i = 0; $i < count($cod_agendamento); $i++) //para cada codigo de agendamento
		{
			for($j = 0; $j< sizeof($nDistribuicao); $j++)//para cada numero de distribuicao
			{
				$sql1 = "SELECT COUNT(COD_AGENDAMENTO) total FROM ESM_LOGISTICA_DISTRIBUICAO WHERE trim(COD_AGENDAMENTO) = '".$cod_agendamento[$i]."' AND  trim(COD_DISTRIBUICAO_EBS) = '".$nDistribuicao[$j]."'";
				
				//print_r($sql1);
				
			
				
				$encontrados =  $this->db->query($sql1)->result_array();
				
				//print_r($sql1);
				
				
				
				if ($encontrados[0]['TOTAL'] == 0)
				{
					$sql2 = "INSERT INTO ESM_LOGISTICA_DISTRIBUICAO (COD_AGENDAMENTO, COD_DISTRIBUICAO_EBS,ATRIBUIDO) 
									VALUES('".$cod_agendamento[$i]."','".$nDistribuicao[$j]."','N')";
									
										//print_r($sql2);
				
									$this->db->query($sql2);
									
											
				}	
				
				
				//Update na tabela ESM_LOGISTICA_AGENDAMENTO inserindo o $cod_distribuicao_ebs
				$sql= "UPDATE ESM_LOGISTICA_AGENDAMENTO	
								SET 
									STATUS = 'B'												
								WHERE COD_AGENDAMENTO = '".$cod_agendamento[$i]."'";
											
								$this->db->query($sql);
			
				
				$this->db->query("INSERT INTO ESM_LOGISTICA_ACOES_AGEND (COD_AGENDAMENTO, NUM_MATRICULA, DATA_ACAO, ACAO) VALUES(".$cod_agendamento[$i].",".$this->session->userdata('idRegister').", sysdate ,'DISTRIBUICAO DEFINIDA COM NUMERO ".$nDistribuicao[$j]."' ) ");			
			}
			
		}
		
		$this->db->trans_complete();
		return true;
			
	/*	$this->db->trans_start();
		
		for( $i=0; $i < count($cod_agendamento); $i++ ){
			
			
			
			if($cod_agendamento[$i] != ""){
								
				//Update na tabela ESM_LOGISTICA_AGENDAMENTO inserindo o $cod_distribuicao_ebs
				$sql= "UPDATE ESM_LOGISTICA_AGENDAMENTO	
								SET COD_DISTRIBUICAO = '".$cod_distribuicao_ebs."',
									STATUS = 'B'												
								WHERE COD_AGENDAMENTO = '".$cod_agendamento[$i]."'";
				
				$this->db->query($sql);
								
								
		
				$this->db->query("INSERT INTO ESM_LOGISTICA_ACOES_AGEND (COD_AGENDAMENTO, NUM_MATRICULA, DATA_ACAO, ACAO) VALUES(".$cod_agendamento[$i].",".$this->session->userdata('idRegister').", sysdate ,'DISTRIBUICAO DEFINIDA COM NUMERO ".$cod_distribuicao_ebs."' ) ");
				
				//$car.= $cargas[$i]."-";
			}
		}
				
		$this->db->trans_complete(); */
				
		if ($this->db->trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}
		
		return $retorno;
		
		//die();
		
	}
	
	function devolverAgendamento ($param){			
		$this->db->trans_start();	
		  
		  foreach($param['COD_AGENDAMENTO'] as $row){
			$data = array(/*'LOCALIZACAO' => 'DEVOLVIDO',*/
						  'STATUS' => 'D');
				
			$this->db->where('COD_AGENDAMENTO', $row);
			$this->db->update('ESM_LOGISTICA_AGENDAMENTO', $data);
			
			$sql = ("SELECT  DISTINCT ID_CARGA FROM ESM_LOGISTICA_DISTRIBUICAO WHERE COD_AGENDAMENTO = '".$row."' AND ATRIBUIDO = 'S'");
			$carga = $this->db->query($sql)->result_array();	
			
			$sql2 = "DELETE FROM ESM_LOGISTICA_DISTRIBUICAO WHERE COD_AGENDAMENTO = '".$row."' AND ID_CARGA = '".$carga[0]['ID_CARGA']."'";
			
			$this->db->query($sql2);
									
			$this->db->query("INSERT INTO ESM_LOGISTICA_ACOES_AGEND(COD_AGENDAMENTO, NUM_MATRICULA, DATA_ACAO, ACAO, JUSTIFICATIVA_DEVOLUCAO) VALUES(".$row.",".$this->session->userdata('idRegister').", sysdate, 'DEVOLUCAO DO AGENDAMENTO ".$row."', '".$param['justificativa']."')");
		  }
		$this->db->trans_complete();
				
		if ($this->db->trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}
		
		return $retorno;			
	}
	
	function retornaAgendamento($distribuicao){
		$cod_agendamento = array();
		foreach($distribuicao as $row){
			$valor_dist = explode(' - ',$row);
			$agendamento = $this->db->query("SELECT COD_AGENDAMENTO FROM ESM_LOGISTICA_DISTRIBUICAO WHERE COD_DISTRIBUICAO_EBS= '".$valor_dist[0]."'")->result_array();
			foreach($agendamento as $agen){
				$cod_agendamento[] = $agen['COD_AGENDAMENTO'];
			}
		}
		return $cod_agendamento;
	}
	
	function getCodAgendamento($id_carga){
		$cod_agendamento = array();
		$agendamento = $this->db->query("SELECT LD.COD_AGENDAMENTO 
																	FROM ESM_LOGISTICA_AGENDAMENTO LA, ESM_LOGISTICA_DISTRIBUICAO LD 
																	WHERE LD.ID_CARGA = ".$id_carga."")->result_array();
		foreach($agendamento as $agen){
				$cod_agendamento[] = $agen['COD_AGENDAMENTO'];
		}
		
		return $cod_agendamento;
	}
	
	
	function excluirCargaDistribuicao($cod_distribuicao_ebs){
		
		foreach($cod_distribuicao_ebs as $row){
			$this->db->query("DELETE FROM ESM_LOGISTICA_DISTRIBUICAO WHERE COD_DISTRIBUICAO_EBS = '".$row."'");
		}
		return true;
	}
	
	function excluirCargDist ($id_carga){	
		$this->db->query("DELETE FROM ESM_LOGISTICA_DISTRIBUICAO WHERE ID_CARGA = '".$id_carga."'");
	}
	
	function devolverLogistica($id_carga, $status){
		
		$this->db->query("UPDATE ESM_LOGISTICA_CARGA SET STATUS = '".$status."' WHERE ID_CARGA = '".$id_carga."'");
		
		return true;
	}
	
	function verMotivoDevolucao ($id_carga){
				
		return $this->db->get_where('ESM_LOGISTICA_AGENDAMENTO', array('ID' => $id_carga))->row_array();
	
	}
	
	
	
	function agendamentos (){
		
		$sql = "Select COD_CARGA_EBS, count(COD_CARGA_EBS)  from ESM_LOGISTICA_AGENDAMENTO group by COD_CARGA_EBS";
		
		$query = $this->db->query($sql);
		
		return $query->result_array();	
	}
	
	
	function verItensDistribuicoes($codDistribuicao, $idCarga){
		$distrib = $this->db->query("SELECT CARRETA, LINHA, OC_CLIENTE, CLIENTE, ENDERECO, COD_ITEM, DEN_ITEM,
										   QUANTIDADE, TO_CHAR(DATA_AGENDAMENTO,'DD/MM/YYYY') DATA_AGENDAMENTO
									  FROM ESM_LOGISTICA_AGENDAMENTO ELA, ESM_LOGISTICA_DISTRIBUICAO ELD
									 WHERE ELA.DISTRIBUICAO = ELD.COD_DISTRIBUICAO_EBS
									   AND ELD.ID_CARGA = ".$idCarga."
									   AND (ELD.COD_DISTRIBUICAO_EBS = ".$codDistribuicao." OR ELD.COD_DISTRIBUICAO_EBS IS NULL)")->result_array();
		return $distrib;
	
	}
	
	function registrar_acao($cod_agendamento, $acao){
		$this->db->query("INSERT INTO ESM_LOGISTICA_ACOES_AGEND(COD_AGENDAMENTO, NUM_MATRICULA, DATA_ACAO, ACAO) VALUES(".$cod_agendamento.",".$this->session->userdata('idRegister').", sysdate ,'".$acao."') ");
		
		
	}
	
	
	
	function defineInfoCargas($COD_AGENDAMENTO,$RETENCOES,$RESERVAS,$QUANTIDADE,$COD_DISTRIBUICAO)
	{
		$COD_AGENDAMENTO = $COD_AGENDAMENTO;
		$RETENCOES = $RETENCOES;
		$RESERVAS = $RESERVAS;
		$QUANTIDADE = $QUANTIDADE;
		$COD_DISTRIBUICAO = $COD_DISTRIBUICAO;	
		$sql_busca_carreta = "select cod_carreta from esmaltec.ESM_LOGISTICA_AGENDAMENTO where cod_agendamento = ".$COD_AGENDAMENTO;
		print_r($sql_busca_carreta);
		print_r('<br />');
		$COD_CARRETA = $this->db->query($sql_busca_carreta)->result_array();

		$sql_busca_agendamentos = "select cod_agendamento from esmaltec.ESM_LOGISTICA_AGENDAMENTO where cod_carreta = ".$COD_CARRETA[0]['COD_CARRETA'];
		print_r($sql_busca_agendamentos);
		print_r("<br />");
		$LISTA_AGENDAMENTOS = $this->db->query($sql_busca_agendamentos)->result_array();

					
		$sql_reservas = "UPDATE ESM_LOGISTICA_AGENDAMENTO SET  RESERVA = '".$RESERVAS."' WHERE COD_AGENDAMENTO = '".$COD_AGENDAMENTO."'";
		print_r($sql_reservas);
		$this->db->query($sql_reservas);
		$this->db->query ("INSERT INTO ESM_LOGISTICA_ACOES_AGEND VALUES('".$COD_AGENDAMENTO."','".$this->session->userdata('idRegister')."',SYSDATE,
		'STATUS DE RESERVAS DEFINIDO PARA ".$RESERVAS."','')");
		
		$sql_retencoes = "UPDATE ESM_LOGISTICA_AGENDAMENTO SET  RETENCAO = '".$RETENCOES."' WHERE COD_AGENDAMENTO = ".$COD_AGENDAMENTO;
		print_r($sql_retencoes);
		$this->db->query($sql_retencoes);
		$this->db->query ("INSERT INTO ESM_LOGISTICA_ACOES_AGEND VALUES('".$COD_AGENDAMENTO."','".$this->session->userdata('idRegister')."',SYSDATE,
		'STATUS DE RETENCAO DEFINIDO PARA ".$RETENCOES."','')");
		
		$sql_quantidades = "UPDATE ESM_LOGISTICA_AGENDAMENTO SET  QTD= '".$QUANTIDADE."' WHERE COD_AGENDAMENTO = ".$COD_AGENDAMENTO;
		print_r($sql_quantidades);
		$this->db->query($sql_quantidades);
		$this->db->query ("INSERT INTO ESM_LOGISTICA_ACOES_AGEND VALUES('".$COD_AGENDAMENTO."','".$this->session->userdata('idRegister')."',SYSDATE,
		'QUANTIDADE DE ITENS DEFINIDA PARA ".$QUANTIDADE."','')");	

		$sql_distribuicoes = "SELECT COD_AGENDAMENTO FROM ESMALTEC.ESM_LOGISTICA_DISTRIBUICAO WHERE COD_DISTRIBUICAO_EBS= ".$COD_DISTRIBUICAO;
		$agendamentos_registrados = $this->db->query($sql_distribuicoes)->result_array();

		if(sizeof($agendamentos_registrados)>0)
		{
			$sql_atualiza_distribuicoes = "UPDATE ESMALTEC.ESM_LOGISTICA_DISTRIBUICAO SET COD_DISTRIBUICAO_EBS = '".$COD_DISTRIBUICAO."' WHERE COD_AGENDAMENTO = '".$agendamentos_registrados[0]['COD_AGENDAMENTO']."'";
			$this->db->query($sql_atualiza_distribuicoes);
		}
		else
		{
			$sql_insere_distribuicoes = "INSERT INTO ESMALTEC.ESM_LOGISTICA_DISTRIBUICAO (COD_DISTRIBUICAO_EBS,ATRIBUIDO,COD_AGENDAMENTO) VALUES ('".$COD_DISTRIBUICAO."','N','".$COD_AGENDAMENTO."')";

			$this->db->query($sql_insere_distribuicoes);	
		}
		
		return true;
	
		}

		function defineLTCarreta($COD_CARRETA,$VALOR_LEADTIME)
		{
			$COD_CARRETA = $COD_CARRETA;
			$VALOR_LEADTIME = $VALOR_LEADTIME;
					
		$AGENDAMENTOS = $this->db->query("select cod_agendamento from esm_logistica_agendamento where cod_carreta =" .$COD_CARRETA)->result_array();
		foreach ($AGENDAMENTOS as $AGENDA)
		{
			
			//$this->db->query("UPDATE ESM_LOGISTICA_AGENDAMENTO SET  DATA_LIMITE = TRIM(to_char('".$DATA_AGENDAMENTO."')) WHERE COD_CARRETA = ".$COD_CARRETA);
			$query = ("UPDATE ESM_LOGISTICA_AGENDAMENTO SET DATA_LIMITE = (DATA_AGENDAMENTO - INTERVAL '".$VALOR_LEADTIME."' DAY )
			 WHERE COD_CARRETA = ".$COD_CARRETA ." AND COD_AGENDAMENTO = ".$AGENDA['COD_AGENDAMENTO']);
			
			$this->db->query($query);

			$this->db->query ("INSERT INTO ESM_LOGISTICA_ACOES_AGEND VALUES('".$AGENDA['COD_AGENDAMENTO']."',".$this->session->userdata('idRegister').",
			SYSDATE,'LEADTIME DEFINIDO PARA ".$VALOR_LEADTIME." DIAS DE ANTECEDENCIA','')");
		}
		
		return true;	
	
		}
		
		function defineLTDuplicado($COD_AGENDAMENTO)
	{
		$sql_linha = "select max(linha) as linha from esm_logistica_agendamento where cod_carreta =  
								(select cod_carreta from esm_logistica_agendamento where cod_agendamento =".$COD_AGENDAMENTO." ) ";
								
								print_r($sql_linha);
								$linha = $this->db->query($sql_linha)->result_array();
								$linha_temp = explode('.',$linha[0]['LINHA']);
								//print_r($linha_temp);
								$linha_adicional = $linha_temp[0].'.'.($linha_temp[1]+1);
								//print_r($linha_adicional);
								$sql_agendamento = "select ESM_LOGISTICA_AGENDAMENTO_SEQ.NEXTVAL cod_agenda from dual";
								//print_r($sql_agendamento);
								//echo("<br><br><br><br><br><br><br><br><br>");
								$sql_novo_agendamento =  $this->db->query($sql_agendamento)->result_array();
								$novo_agendamento = $sql_novo_agendamento[0]['COD_AGENDA'];
								//print_r($sql_novo_agendamento);
								
									
		$this->db->trans_start();
		
		
		$sql_originais = "select REGIONAL, TO_CHAR(DATA_SOLICITACAO,'DD/MM/YYYY') DATA_SOLICITACAO,CLIENTE_ENTREGA,ORDEM_VENDA,OC_CLIENTE,COD_ITEM,DEN_ITEM,QTD,LINHA,RETENCAO,COD_CARRETA,TO_CHAR(DATA_AGENDAMENTO,'DD/MM/YYYY') ,TO_CHAR(DATA_AGENDAMENTO,'DD/MM/YYYY') DATA_AGENDAMENTO,LOCAL_CARGA,FILIAL, DATA_CADASTRO, TO_CHAR(DATA_LIMITE,'DD/MM/YYYY') DATA_LIMITE, STATUS,ENDERECO,VALOR_UNITARIO,COD_DISTRIBUICAO,RESERVA
 from esm_logistica_agendamento LA where cod_agendamento = '".$COD_AGENDAMENTO."'";		
 print_r($sql_originais);
		
		$infos = $this->db->query($sql_originais)->result_array();
		
		
		$cloned_query = ("insert into esm_logistica_agendamento VALUES ('".$novo_agendamento."','".$infos[0]['REGIONAL']."','".$infos[0]['DATA_SOLICITACAO']."','".$infos[0]['CLIENTE_ENTREGA']."','".$infos[0]['ORDEM_VENDA']."','"
		.$infos[0]['OC_CLIENTE']."','".$infos[0]['COD_ITEM']."','".$infos[0]['DEN_ITEM']."','".''."','".$linha_adicional."','".$infos[0]['RETENCAO']."','".$infos[0]['COD_CARRETA']."','".$infos[0]['DATA_AGENDAMENTO']."','"
		.$infos[0]['LOCAL_CARGA']."','".$infos[0]['FILIAL']."','".$infos[0]['DATA_CADASTRO']."','".$infos[0]['DATA_LIMITE']."','"
		.$infos[0]['STATUS']."','".$infos[0]['ENDERECO']."','".$infos[0]['VALOR_UNITARIO']."','".$infos[0]['COD_DISTRIBUICAO']."','".''."')");
		
	
		
		$this->db->query($cloned_query);
		$registro = "INSERT INTO ESM_LOGISTICA_ACOES_AGEND VALUES('".$COD_AGENDAMENTO."','".$this->session->userdata('idRegister')."',
			SYSDATE,'COD. AGENDAMENTO ".$COD_AGENDAMENTO." ACABA DE SER DIVIDIDO COM O AGENDAMENTO ".$novo_agendamento." ','')";
		
		
		$this->db->query ($registro);
		
		
		
		$this->db->trans_complete();
				
		if ($this->db->trans_status() === FALSE) {
			$retorno = false;
		} else {
			//$retorno = true;
			$retorno ="ok";
		}
		
			return $retorno;
		
		}

		function transfereAgendamento($COD_AGENDAMENTO)
		{
			$sql = "select COD_AGENDAMENTO,
				  REGIONAL,
				  TO_CHAR(DATA_SOLICITACAO,'DD/MM/YYYY') DATA_SOLICITACAO,
				  CLIENTE_ENTREGA,
				  ORDEM_VENDA,
				  OC_CLIENTE,
				  COD_ITEM,
				  DEN_ITEM,
				  QTD,
				  LINHA,
				  RETENCAO,
				  COD_CARRETA,
				  TO_CHAR(DATA_AGENDAMENTO,'DD/MM/YYYY') DATA_AGENDAMENTO,
				  LOCAL_CARGA,
				  FILIAL,
				  TO_CHAR(DATA_CADASTRO,'DD/MM/YYYY') DATA_CADASTRO,
				  DATA_LIMITE,
				  STATUS,
				  ENDERECO,
				  VALOR_UNITARIO,
				  COD_DISTRIBUICAO,
  				  RESERVA 
  				  from esmaltec.esm_logistica_agenda_import where cod_agendamento = ".$COD_AGENDAMENTO;
			$info = $this->db->query($sql)->result_array();

			//print_r($info);
			
			$sqlAtualizaDados = 
						"INSERT INTO ESMALTEC.ESM_LOGISTICA_AGENDAMENTO(
						COD_AGENDAMENTO,
						REGIONAL,
						DATA_SOLICITACAO,
						CLIENTE_ENTREGA,
						ORDEM_VENDA,
						OC_CLIENTE,
						COD_ITEM,
						DEN_ITEM,
						QTD,LINHA,
						RETENCAO,
						COD_CARRETA,
						DATA_AGENDAMENTO,
						LOCAL_CARGA,
						FILIAL,
						DATA_CADASTRO,
						DATA_LIMITE,
						STATUS,
						ENDERECO,
						VALOR_UNITARIO,
						COD_DISTRIBUICAO,
						RESERVA)
						VALUES
						(ESMALTEC.ESM_LOGISTICA_AGENDAMENTO_SEQ.NEXTVAL,
						'".$info[0]['REGIONAL']."',
						'".$info[0]['DATA_SOLICITACAO']."',
						'".$info[0]['CLIENTE_ENTREGA']."',
						'".$info[0]['ORDEM_VENDA']."',
						'".$info[0]['OC_CLIENTE']."',
						'".$info[0]['COD_ITEM']."',
						'".$info[0]['DEN_ITEM']."',
						'".$info[0]['QTD']."',
						'".$info[0]['LINHA']."',
						'".$info[0]['RETENCAO']."',
						'".$info[0]['COD_CARRETA']."',
						'".$info[0]['DATA_AGENDAMENTO']."',
						'".$info[0]['LOCAL_CARGA']."',
						'".$info[0]['FILIAL']."',
						'".$info[0]['DATA_CADASTRO']."',
						'".$info[0]['DATA_LIMITE']."',
						'".$info[0]['STATUS']."',
						'".$info[0]['ENDERECO']."',
						'".$info[0]['VALOR_UNITARIO']."',
						'".$info[0]['COD_DISTRIBUICAO']."',
						'".$info[0]['RESERVA']."')";

						$this->db->query($sqlAtualizaDados);

						$this->db->query("DELETE FROM ESMALTEC.esm_logistica_agenda_import WHERE COD_AGENDAMENTO = ".$COD_AGENDAMENTO);
						return true;

			
		}

		function excluiDadosImportacao($COD_AGENDAMENTO)
		{
			$this->db->query("delete from esmaltec.esm_logistica_agenda_import where cod_agendamento = ".$COD_AGENDAMENTO);
			return true;
		}
		
 function DistribuicoesGeradas()
 {
	 $query =$this->db->query("SELECT ELA.COD_CARRETA, 
												   ELD.COD_DISTRIBUICAO_EBS, 
												   CASE WHEN(ELD.ID_CARGA IS NULL)
												   THEN ('VEICULO NÃO DEFINIDO')
												   ELSE ELT.NOME_CLIENTE END AS TRANSPORTADORA, 
												   ELD.ATRIBUIDO, 
												   ELD.COD_AGENDAMENTO, 
												   ELA.QTD, 
												   ELA.COD_ITEM, 
												   ELA.DEN_ITEM, 
												   ELA.CLIENTE_ENTREGA,
												   TO_NUMBER(ELC.ID_TRANSPORTADORA) ID_TRANSPORTADORA
       
												FROM ESM_LOGISTICA_DISTRIBUICAO ELD
												LEFT JOIN ESM_LOGISTICA_AGENDAMENTO ELA
												ON ELA.COD_AGENDAMENTO = ELD.COD_AGENDAMENTO
												LEFT JOIN ESM_LOGISTICA_CARGA ELC
												ON ELC.ID_CARGA = ELD.ID_CARGA
											  LEFT  JOIN ESM_LOGISTICA_CLIENTE ELT
											   ON (ELC.ID_TRANSPORTADORA) = (ELT.ID_CLIENTE) 
											  WHERE (ELC.status not like ('%TRA%') OR ELD.ID_CARGA IS NULL)")->result_array();
							  return $query;
 }
			
}
?>