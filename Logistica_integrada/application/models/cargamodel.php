<?php
class CargaModel extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
    }
	
	function detalhar($id_transportadora = 0) {
		return $this->db->get_where('ESM_LOGISTICA_TRANSPORTADORA', array('ID_TRANSPORTADORA' => $id_transportadora))->row_array();
	}

	function valida_carga($carga)
	{
		//print_r($carga);
		$sql = "SELECT COUNT(*) RESULT FROM ESMALTEC.ESM_LOGISTICA_CARGA C 
		WHERE c.status not in('TRA','CAN') 
		AND c.PLACA_VEICULO = '".$carga['PLACA_VEICULO']."'
		AND C.PLACA_CAVALO =  '".$carga['PLACA_CAVALO']."'
		AND C.CPF_MOTORISTA = '".$carga['CPF_MOTORISTA']."'";
		$dados = $this->db->query($sql)->result_array();
		if ($dados[0]['RESULT'] < 1)
		{
			return true; 
		} else
		{  
			return false;
		}
		//print_r($sql);
		//die();
	}
	
	function insert($carga)
	{
		
		$this->db->query("
				INSERT INTO ESMALTEC.ESM_LOGISTICA_CARGA
					(ID_CARGA, 
					DATA_ATUAL, 
					ID_TRANSPORTADORA, 
					ESTADO, 
					CIDADE, 
					VEICULO, 
					PLACA_VEICULO, 
					PLACA_CAVALO, 
					NUM_CONTAINER, 
					HORA_CHEGADA, 
					STATUS, 
					USUARIO_PORTARIA, 
					TIPO_TRANSPORTADORA, 
					TELEFONE_CONTATO, 
					TIPO_OPERACAO, 
					COD_RECEBEDOR, 
					CPF_MOTORISTA, 
					NOME_MOTORISTA) 
				VALUES 
					(ESMALTEC.SEQ_ESM_LOGISTICA_CARGA.NEXTVAL,
					" .$carga['DATA_ATUAL'].",
					'".$carga['ID_TRANSPORTADORA']."', 
					'".$carga['ESTADO']."',
					'".$carga['CIDADE']."',
					'".$carga['VEICULO']."',
					'".$carga['PLACA_VEICULO']."',
					'".$carga['PLACA_CAVALO']."',
					'".$carga['NUM_CONTAINER']."',
					'".$carga['HORA_CHEGADA']."',
					'".$carga['STATUS']."',
					" .$carga['USUARIO_PORTARIA'].",
					'".$carga['TIPO_TRANSPORTADORA']."',					
					'".$carga['TELEFONE_CONTATO']."',
					'".$carga['TIPO_OPERACAO']."',
					'".$carga['COD_RECEBEDOR']."',
					'".$carga['CPF_MOTORISTA']."',
					'".$carga['NOME_MOTORISTA']."'		
					)
				");				
		
		
		return true;

	}

	function insere_complementos($param, $dados)
	{ 
		$value = null;
		if ($value == null)
		{
			$sqlnextval = ("SELECT ESMALTEC.SEQ_ESM_LOGISTICA_CARGA.NEXTVAL VALOR FROM DUAL");
			$nextval = $this->db->query($sqlnextval)->result_array();
			$value = ($nextval[0]['VALOR']);
		}
		
		
		$sql = 'SELECT * FROM ESMALTEC.ESM_LOGISTICA_CARGA WHERE ID_CARGA = '.$param;
		$dados = $this->db->query($sql)->result_array();
		$sql_complementos = "INSERT INTO ESM_LOGISTICA_CARGA
		(ID_CARGA,
		DATA_ATUAL,
		ID_TRANSPORTADORA,
		ESTADO,
		CIDADE,
		VEICULO,
		PLACA_VEICULO,
		PLACA_CAVALO,
		NUM_CONTAINER,
		HORA_CHEGADA,
		ID_CLIENTE,
		TIPO_DOCUMENTO,
		DOCUMENTOS,
		OBSERVACAO,
		SENHA_AGENDAMENTO,
		STATUS,
		HORA_SAIDA_LOGISTICA,
		TIPO_TRANSPORTADORA,
		TELEFONE_CONTATO,
		TIPO_OPERACAO,
		COD_RECEBEDOR,
		LOCAL_CARREGAMENTO,
		TAMANHO_VEICULO,
		DISTRIBUICAO,
		CPF_RECEBEDOR,
		TIPO_RECEBEDOR,
		CPF_MOTORISTA,
		NOME_MOTORISTA,
		PESO_ENTRADA,
		PESO_SAIDA,
		OBSERVACAO_RECUSA,
		
		COMPLEMENTO) VALUES
		($value,
		 SUBSTR(SYSDATE,1,10),
		 '".$dados[0]['ID_TRANSPORTADORA']."',
		 '".$dados[0]['ESTADO']."',
		 '".$dados[0]['CIDADE']."',
		 '".$dados[0]['VEICULO']."',
		 '".$dados[0]['PLACA_VEICULO']."',
		 '".$dados[0]['PLACA_CAVALO']."',
		'".$dados[0]['NUM_CONTAINER']."',
		TO_CHAR(SYSDATE,'HH24:MI'),
		'".$dados[0]['ID_CLIENTE']."',
		'".$dados[0]['TIPO_DOCUMENTO']."',
		'".$dados[0]['DOCUMENTOS']."',
		'".$dados[0]['OBSERVACAO']."',
		'".$dados[0]['SENHA_AGENDAMENTO']."',
		'EXP',
		SYSDATE,
		'".$dados[0]['TIPO_TRANSPORTADORA']."',
		'".$dados[0]['TELEFONE_CONTATO']."',
		'".$dados[0]['TIPO_OPERACAO']."',
		'".$dados[0]['COD_RECEBEDOR']."',
		'".$_POST['LOCAL_COMPLEMENTO']."',
		'".$dados[0]['TAMANHO_VEICULO']."',
		'".$dados[0]['DISTRIBUICAO']."',
		'".$dados[0]['CPF_RECEBEDOR']."',
		'".$dados[0]['TIPO_RECEBEDOR']."',
		'".$dados[0]['CPF_MOTORISTA']."',
		'".$dados[0]['NOME_MOTORISTA']."',
		'".$dados[0]['PESO_ENTRADA']."',
		'".$dados[0]['PESO_SAIDA']."',
		'".$dados[0]['OBSERVACAO_RECUSA']."',
		'".$param."')";
		
		$this->db->query($sql_complementos);

		$value = null;		

	}


	function recusaEntrada($param)
	{
		$sql = "UPDATE ESMALTEC.ESM_LOGISTICA_CARGA SET OBSERVACAO_RECUSA = upper('".utf8_decode($param['motivo_recusa'])."'), STATUS ='".$param['status_carga']."'  WHERE ID_CARGA = ".$param['id_carga']; 
		return $this->db->query($sql);

	}
	
	function deletar($id_transportadora)
	{
		$this->db->delete('ESM_LOGISTICA_TRANSPORTADORA', array('ID_TRANSPORTADORA' => $id_transportadora));
	}
	
	function update($idcarga, $carga)
	{
		$adicionais ="";

		if($carga['TIPO_OPERACAO']!="DSC" && $carga['STATUS']=="CAR")
								{
								 $adicionais="HORA_CARREGAMENTO_INI = TO_DATE(SYSDATE),";
								}
		if($carga['TIPO_OPERACAO']=="DSC" && $carga['STATUS']=="CAR")
								{
								$carga['HORA_SAIDA_EXPEDICAO'] = "";
								 $adicionais="HORA_CARREGAMENTO_FIM = NULL,";
								}
		if($carga['STATUS']=="ROM")
		{
							 	$adicionais="HORA_CARREGAMENTO_FIM = TO_DATE(SYSDATE),
								 			HORA_CRIACAO_ROMANEIO_INI = TO_DATE(SYSDATE),";
								}
		if($carga['STATUS']=="FIS")
								{
								 $adicionais="HORA_CRIACAO_ROMANEIO_FIM = TO_DATE(SYSDATE),
								 			HORA_INICIO_LIB_FISCAL = TO_DATE(SYSDATE),";
								}
		if($carga['STATUS']=="SAI")
								{	
								 $adicionais="HORA_FIM_LIB_FISCAL = TO_DATE(SYSDATE),
								 			  HORA_AUTORIZ_SAIDA = TO_DATE(SYSDATE),";
								}

		$sql="UPDATE ESMALTEC.ESM_LOGISTICA_CARGA	
								SET ID_CLIENTE = '".$carga['ID_CLIENTE']."',
								ID_TRANSPORTADORA = '".$carga['ID_TRASPORTADORA']."',
								ESTADO = '".$carga['ESTADO']."',
								CIDADE = '".$carga['CIDADE']."',
								VEICULO = '".$carga['VEICULO']."',
								TIPO_DOCUMENTO = '".$carga['TIPO_DOCUMENTO']."',
								DISTRIBUICAO = '".$carga['DISTRIBUICAO']."',
								DOCUMENTOS = '".$carga['DOCUMENTOS']."',
								OBSERVACAO = '".$carga['OBSERVACAO']."',
								DATA_SAIDA = ".$carga['DATA_SAIDA'].",
								SENHA_AGENDAMENTO = '".$carga['SENHA_AGENDAMENTO']."',
								STATUS = '".$carga['STATUS']."',
								HORA_SAIDA_LOGISTICA = ".$carga['HORA_SAIDA_LOGISTICA'].",
								USUARIO_LOGISTICA = '".$carga['USUARIO_LOGISTICA']."',								
								LOCAL_CARREGAMENTO = '".$carga['LOCAL_CARREGAMENTO']."',
								DOCA = '".$carga['DOCA']."',
								CONFERENTE = '".$carga['CONFERENTE']."',
								CONFERENTE_2 = '".$carga['CONFERENTE_2']."',
								CONFERENTE_3 = '".$carga['CONFERENTE_3']."',
								CAPATAZIA = '".$carga['CAPATAZIA']."',
								TAMANHO_VEICULO = '".$carga['TAMANHO_VEICULO']."',
								CPF_RECEBEDOR = '".$carga['CPF_RECEBEDOR']."',"
								.$adicionais."
								TIPO_RECEBEDOR = '".$carga['TIPO_RECEBEDOR']."'								
								WHERE ID_CARGA = '".$idcarga."'";
								
								

		$this->db->query($sql);
		
		$sql2=("UPDATE ESMALTEC.ESM_LOGISTICA_CARGA SET  DISTRIBUICAO = (SELECT DISTINCT DISTRIBUICAO FROM ESMALTEC.ESM_LOGISTICA_AGENDAMENTO ELA,
 ESM_LOGISTICA_DISTRIBUICAO ELD WHERE (ELA.COD_DISTRIBUICAO = ELD.COD_DISTRIBUICAO_EBS OR ELD.COD_DISTRIBUICAO_EBS IS NULL) AND ELD.ID_CARGA ='".$idcarga."') WHERE ID_CARGA= '".$idcarga."'");
																
								$this->db->query($sql2);
										
		return true;

	}
	
	function update_expedicao($idcarga, $carga)
	{


		if(isset($carga['CPF_RECEBEDOR']))
		{
			
			$cpf_num = "CPF_RECEBEDOR = '".$carga['CPF_RECEBEDOR']."',";
		}
		else
		{
			$cpf_num =''; 
		}
		$adicionais ="";

		if($carga['STATUS']=="CAR")
								{
								 $adicionais="HORA_CARREGAMENTO_INI = TO_DATE(SYSDATE),";

								 }
		if($carga['STATUS']=="ROM")
								{
								 $adicionais="HORA_CARREGAMENTO_FIM = TO_DATE(SYSDATE),
								 			HORA_CRIACAO_ROMANEIO_INI = TO_DATE(SYSDATE),";

								 											 			
								}
		if($carga['STATUS']=="SAI" && $carga['TIPO_OPERACAO']=='DSC')
								{
									
								 $adicionais="HORA_CARREGAMENTO_FIM = TO_DATE(SYSDATE),";

								 											 			
								}
		if($carga['STATUS']=="FIS")
								{
								 $adicionais="HORA_CRIACAO_ROMANEIO_FIM = TO_DATE(SYSDATE),
								 			HORA_LIB_FISCAL_INI = TO_DATE(SYSDATE),";

								 			
								}
		if($carga['STATUS']=="SAI" &&$carga['TIPO_OPERACAO']!="DSC")
								{	
								 $adicionais="HORA_LIB_FISCAL_FIM = TO_DATE(SYSDATE),
								 			  HORA_AUTORIZ_SAIDA = TO_DATE(SYSDATE),";
								}
										
		
		$this->db->query("UPDATE ESMALTEC.ESM_LOGISTICA_CARGA	
								SET ID_CLIENTE = '".$carga['ID_CLIENTE']."',
								ID_TRANSPORTADORA = '".$carga['ID_TRASPORTADORA']."',
								ESTADO = '".$carga['ESTADO']."',
								CIDADE = '".$carga['CIDADE']."',
								VEICULO = '".$carga['VEICULO']."',
								TIPO_DOCUMENTO = '".$carga['TIPO_DOCUMENTO']."',
								DISTRIBUICAO = '".$carga['DISTRIBUICAO']."',
								DOCUMENTOS = '".$carga['DOCUMENTOS']."',
								OBSERVACAO = '".$carga['OBSERVACAO']."',
								DATA_SAIDA = ".$carga['DATA_SAIDA'].",
								SENHA_AGENDAMENTO = '".$carga['SENHA_AGENDAMENTO']."',
								STATUS = '".$carga['STATUS']."',
								HORA_SAIDA_EXPEDICAO = ".$carga['HORA_SAIDA_EXPEDICAO'].",
								USUARIO_EXPEDICAO = '".$carga['USUARIO_EXPEDICAO']."',
								LOCAL_CARREGAMENTO = '".$carga['LOCAL_CARREGAMENTO']."',
								DOCA = '".$carga['DOCA']."',
								CONFERENTE = '".$carga['CONFERENTE']."',
								CONFERENTE_2 = '".$carga['CONFERENTE_2']."',
								CONFERENTE_3 = '".$carga['CONFERENTE_3']."',
								INFORMACOES_FISCAIS = '".$carga['INFORMACOES_FISCAIS']."',
								CAPATAZIA = '".$carga['CAPATAZIA']."',"
								.$adicionais
								."TAMANHO_VEICULO = '".$carga['TAMANHO_VEICULO']."',

								".$cpf_num."
								TIPO_RECEBEDOR = '".$carga['TIPO_RECEBEDOR']."'									
								WHERE ID_CARGA = '".$idcarga."'");		
								
								$this->db->query("UPDATE ESMALTEC.ESM_LOGISTICA_CARGA SET DISTRIBUICAO = (SELECT DISTINCT COD_DISTRIBUICAO FROM ESM_LOGISTICA_AGENDAMENTO ELA, ESM_LOGISTICA_DISTRIBUICAO ELD WHERE (ELA.COD_DISTRIBUICAO = ELD.COD_DISTRIBUICAO_EBS OR ELD.COD_DISTRIBUICAO_EBS IS NULL) AND ELD.ID_CARGA =".$idcarga."AND ROWNUM = 1) WHERE ID_CARGA= '".$idcarga."'");
								$this->db->query("UPDATE ESMALTEC.ESM_LOGISTICA_DISTRIBUICAO ELD SET ATRIBUIDO = 'S' WHERE   ELD.ID_CARGA =".$idcarga);
		return true;
		
	}
	
	function transportadoraTodas()
	{
		$this->db->select('*');
		$this->db->from('ESMALTEC.ESM_LOGISTICA_CARGA');
		$this->db->order_by('ID_TRANSPORTADORA DESC');
		return $this->db->get()->result_array();		
	}
	
	function cargasExpedicao($id_carga = false, $par = false)
	{
		$filtro = "";
		if($id_carga){
			$filtro = "and CARGA.ID_CARGA = ".$id_carga."";
		}
		$var1 = "'EXP'";
		if($par){
			$var1 = "'".$par."'";
		}
		
			$sql =
				"SELECT  distinct
                CARGA.*,
                TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
                TO_CHAR(CARGA.HORA_SAIDA_LOGISTICA,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_LOGISTICA,
                TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA, 
                CD.*, CD.NOME AS NOME_CIDADE,
                EST.*,
                EST.NOME AS NOME_ESTADO, 
                TRA.NOME_TRANSPORTADORA, 
				CNPJ_TRANSPORTADORA,
                cli.NOME_CLIENTE NOME_CLIENTE,   
               CASE WHEN( (select count (eld.cod_distribuicao_ebs) 
               	from esmaltec.esm_logistica_distribuicao eld where trim(eld.id_carga) = trim(carga.id_carga) AND ELD.ATRIBUIDO = 'S' )=1)
                THEN
                ELD.COD_DISTRIBUICAO_EBS
                 ELSE                 
                 ''
                END  AS DIS,
				CASE WHEN (TIPO_OPERACAO = 'CIF')
					THEN 'Cif Cliente' 
					WHEN (TIPO_OPERACAO = 'COL')
					THEN 'Coleta'
					WHEN (TIPO_OPERACAO = 'C_EXP')
					THEN 'Carga Expressa'
					WHEN (TIPO_OPERACAO = 'DSC')
					THEN 'Descarregamento'
					WHEN (TIPO_OPERACAO = 'FOB')
					THEN 'FOB Cliente'
					WHEN (TIPO_OPERACAO = 'FRA')
					THEN 'Fracionado'
					WHEN (TIPO_OPERACAO = 'TRA')
					THEN 'Transferencia'
					END DES_OPERACAO,            
                    CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
                       (select DISTINCT T.NOME_TRANSPORTADORA AS NOME                
                        from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
                        WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
                    WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
                        (select DISTINCT CLIENTE.NOME_CLIENTE AS NOME                
                        from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
                        WHERE cliente.ID_cliente = CARGA.ID_TRANSPORTADORA and rownum <2)
                    END AS NOME_TRANSPORTADORA                           
            FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
                JOIN ESMALTEC.ESM_LOGISTICA_CIDADES CD
                    ON CD.ID = CARGA.CIDADE
                JOIN ESMALTEC.ESM_LOGISTICA_ESTADOS EST
                    ON EST.ID = CARGA.ESTADO
                  LEFT JOIN ESMALTEC.ESM_LOGISTICA_DISTRIBUICAO ELD
                      ON ELD.ID_CARGA = CARGA.ID_CARGA
                LEFT JOIN ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA TRA
                    ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA
                JOIN ESMALTEC.esm_logistica_cliente cli
                     ON cli.id_cliente = carga.id_cliente
         			
			WHERE STATUS  = ".$var1." ".$filtro." ORDER BY CARGA.DATA_ATUAL DESC";

		$query = $this->db->query($sql);
		
		return $query->result_array();		
	}
	
	function cargasLogistica($id_carga)
	{
		
		$filtro = "";
		if($id_carga!=""){
			$filtro = "/* and */ CARGA.ID_CARGA = ".$id_carga."";
		}
		else
		{
			$filtro = "";
		}		
			
			$sql = "
			SELECT 
				CARGA.*,
				TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
				TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA, 
				CD.*, CD.NOME AS NOME_CIDADE,
				EST.*,
				EST.NOME AS NOME_ESTADO, 
				TRA.*,	
				CARGA.DISTRIBUICAO,
				CASE WHEN (TIPO_OPERACAO = 'CIF')
					THEN 'Cif Cliente' 
					WHEN (TIPO_OPERACAO = 'COL')
					THEN 'Coleta'
					WHEN (TIPO_OPERACAO = 'C_EXP')
					THEN 'Carga Expressa'
					WHEN (TIPO_OPERACAO = 'DSC')
					THEN 'Descarregamento'
					WHEN (TIPO_OPERACAO = 'FOB')
					THEN 'FOB Cliente'
					WHEN (TIPO_OPERACAO = 'FRA')
					THEN 'Fracionado'
					WHEN (TIPO_OPERACAO = 'TRA')
					THEN 'Transferencia'
					END DES_OPERACAO,
				(SELECT COUNT(COD_DISTRIBUICAO_EBS) FROM ESMALTEC.ESM_LOGISTICA_DISTRIBUICAO WHERE ATRIBUIDO = 'S' AND ID_CARGA = CARGA.ID_CARGA ) AS DIST,
				(select CLIENTE.NOME_CLIENTE AS NOME				
						from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
						WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA) AS NOME_CLIENTE,			
					CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
					   (select T.NOME_TRANSPORTADORA AS NOME				
						from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
						WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
					WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
						(select CLIENTE.NOME_CLIENTE AS NOME				
						from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
						WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
					END AS NOME_TRANSPORTADORA   						
			FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
				JOIN ESMALTEC.ESM_LOGISTICA_CIDADES CD
					ON CD.ID = CARGA.CIDADE
				JOIN ESMALTEC.ESM_LOGISTICA_ESTADOS EST
					ON EST.ID = CARGA.ESTADO
				LEFT JOIN ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA TRA
                    ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
			WHERE /*(STATUS  = 'LOG' OR STATUS = 'DEV')*/ ".$filtro." ORDER BY CARGA.DATA_ATUAL DESC";
				
		$query = $this->db->query($sql);
		
		return $query->result_array();
		
	}
	
	function marcarLeadTime()
	{			
		$sql = "SELECT L.LOCALIDADE||', '||ES.SIGLA AS LOCALIDADE, L.LEADTIME 
                        FROM ESMALTEC.ESM_LOGISTICA_LEADTIME L, ESMALTEC.ESM_LOGISTICA_ESTADOS ES                         
                         WHERE L.ESTADO = ES.ID                         
                         ORDER BY ES.NOME ASC, L.LOCALIDADE ASC";
						 
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	function cargasLogisticaSemDist($id_carga)
	{
		$filtro = "";
		if($id_carga!=""){
			$filtro = "and CARGA.ID_CARGA = ".$id_carga."";
		}
		else
		{
			$filtro = "";
		}			
			
			$sql = "
			SELECT 
				CARGA.*,
				TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
				TO_CHAR(CARGA.DATA_ATUAL,'DD/MM/YYYY') AS DATA_ATUAL_2,
				TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA, 
				CD.*, CD.NOME AS NOME_CIDADE,
				EST.*,
				EST.NOME AS NOME_ESTADO, 
				TRA.*,	
				CARGA.DISTRIBUICAO,
				CASE WHEN (TIPO_OPERACAO = 'CIF')
					THEN 'Cif Cliente' 
					WHEN (TIPO_OPERACAO = 'COL')
					THEN 'Coleta'
					WHEN (TIPO_OPERACAO = 'C_EXP')
					THEN 'Carga Expressa'
					WHEN (TIPO_OPERACAO = 'DSC')
					THEN 'Descarregamento'
					WHEN (TIPO_OPERACAO = 'FOB')
					THEN 'FOB Cliente'
					WHEN (TIPO_OPERACAO = 'FRA')
					THEN 'Fracionado'
					WHEN (TIPO_OPERACAO = 'TRA')
					THEN 'Transferencia'
					END DES_OPERACAO,
				(select CLIENTE.NOME_CLIENTE AS NOME				
						from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
						WHERE CLIENTE.ID_CLIENTE = CARGA.ID_CLIENTE) AS NOME_CLIENTE,			
					CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
					   (select T.NOME_TRANSPORTADORA AS NOME				
						from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
						WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
					WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
						(select CLIENTE.NOME_CLIENTE AS NOME				
						from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
						WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
					END AS NOME_TRANSPORTADORA   						
			FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
				JOIN ESM_LOGISTICA_CIDADES CD
					ON CD.ID = CARGA.CIDADE
				JOIN ESM_LOGISTICA_ESTADOS EST
					ON EST.ID = CARGA.ESTADO
				LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
                    ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
			WHERE STATUS IN ('LOG','DEV') AND DISTRIBUICAO   IS NULL ".$filtro."			
			AND ROWNUM < 500			
			ORDER BY CARGA.DATA_ATUAL DESC";
		
		
		
		$query = $this->db->query($sql);
		
		return $query->result_array();
		
	}
	
	function cargasLogisticaDist($id_carga = "")
	{
		if($id_carga){
			$filtro = "and CARGA.ID_CARGA = ".$id_carga."";
		}	
			
			
			$sql = "
			SELECT 
				CARGA.*,
				TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
				TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA, 
				CD.*, CD.NOME AS NOME_CIDADE,
				EST.*,
				EST.NOME AS NOME_ESTADO, 
				TRA.*,	
				CARGA.DISTRIBUICAO,
				(select CLIENTE.NOME_CLIENTE AS NOME				
						from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
						WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA) AS NOME_CLIENTE,			
					CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
					   (select T.NOME_TRANSPORTADORA AS NOME				
						from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
						WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
					WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
						(select CLIENTE.NOME_CLIENTE AS NOME				
						from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
						WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
					END AS NOME_TRANSPORTADORA   						
			FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
				JOIN ESM_LOGISTICA_CIDADES CD
					ON CD.ID = CARGA.CIDADE
				JOIN ESM_LOGISTICA_ESTADOS EST
					ON EST.ID = CARGA.ESTADO
				LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
                    ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
			WHERE DISTRIBUICAO IS not NULL AND ROWNUM < 350
			
			
			ORDER BY CARGA.DATA_ATUAL DESC 
			";
		
		//echo $sql;
		
		$query = $this->db->query($sql);
		
		return $query->result_array();
		
	}
	
	function cargasConferi($id_carga = false)
	{
		
		$filtro = "";
		if($id_carga){
			$filtro = "CARGA.ID_CARGA = ".$id_carga."";
		}
			
			$sql = "
			SELECT 
				CARGA.*,
				TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
				TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA,
				TO_CHAR(CARGA.HORA_CARREGAMENTO_INI,'YYYY-MM-DD HH24:MI') AS HORA_CARREGAMENTO_INI,
				TO_CHAR(CARGA.HORA_CARREGAMENTO_FIM,'YYYY-MM-DD HH24:MI') AS HORA_CARREGAMENTO_FIM,
				TO_CHAR(CARGA.HORA_PROGRAMACAO_INI,'YYYY-MM-DD HH24:MI') AS HORA_PROGRAMACAO_INI,
				TO_CHAR(CARGA.HORA_PROGRAMACAO_FIM,'YYYY-MM-DD HH24:MI') AS HORA_PROGRAMACAO_FIM, 
				CLI.*,
				CD.*, CD.NOME AS NOME_CIDADE,
				EST.*,
				EST.NOME AS NOME_ESTADO, 
				TRA.*,	
				CARGA.DISTRIBUICAO,
				CLI.NOME_CLIENTE AS NOME_CLIENTE,			
					CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
					   (select T.NOME_TRANSPORTADORA AS NOME				
						from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
						WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
					WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
						(select CLIENTE.NOME_CLIENTE AS NOME_CLIENTE				
						from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
						WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
					END AS NOME_TRANSPORTADORA   						
			FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
				JOIN ESM_LOGISTICA_CIDADES CD
					ON CD.ID = CARGA.CIDADE
				JOIN ESM_LOGISTICA_ESTADOS EST
					ON EST.ID = CARGA.ESTADO
				LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
                    ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA
				LEFT JOIN ESM_LOGISTICA_CLIENTE CLI
					ON CLI.ID_CLIENTE = CARGA.ID_CLIENTE 
			WHERE ".$filtro." ORDER BY CARGA.DATA_ATUAL DESC
			";
					
		$query = $this->db->query($sql);
		
		return $query->result_array();
		
	}
	
	function cargasAutorizadas($id_carga = false)
	{
		$filtro = "";
		if($id_carga){
			$filtro = "and CARGA.ID_CARGA = ".$id_carga."";
		}
		
			$sql = "
			SELECT 
				CARGA.*,
				TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
				TO_CHAR(CARGA.HORA_SAIDA_LOGISTICA,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_LOGISTICA,
				TO_CHAR(CARGA.HORA_SAIDA_EXPEDICAO,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_EXPEDICAO,
				TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA, 
				CD.*, CD.NOME AS NOME_CIDADE,
				EST.*,
				EST.NOME AS NOME_ESTADO,
				CASE WHEN (TIPO_OPERACAO = 'CIF')
					THEN 'Cif Cliente' 
					WHEN (TIPO_OPERACAO = 'COL')
					THEN 'Coleta'
					WHEN (TIPO_OPERACAO = 'C_EXP')
					THEN 'Carga Expressa'
					WHEN (TIPO_OPERACAO = 'DSC')
					THEN 'Descarregamento'
					WHEN (TIPO_OPERACAO = 'FOB')
					THEN 'FOB Cliente'
					WHEN (TIPO_OPERACAO = 'FRA')
					THEN 'Fracionado'
					WHEN (TIPO_OPERACAO = 'TRA')
					THEN 'Transferencia'
					END DES_OPERACAO, 
				TRA.*,				
					CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
					   (select T.NOME_TRANSPORTADORA AS NOME				
						from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
						WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
					WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
						(select CLIENTE.NOME_CLIENTE AS NOME				
						from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
						WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
					END AS NOME_TRANSPORTADORA   						
			FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
				JOIN ESM_LOGISTICA_CIDADES CD
					ON CD.ID = CARGA.CIDADE
				JOIN ESM_LOGISTICA_ESTADOS EST
					ON EST.ID = CARGA.ESTADO
				LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
                    ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
			WHERE STATUS  = 'AUT' ".$filtro." ORDER BY CARGA.HORA_SAIDA_EXPEDICAO DESC
			";
			
		$query = $this->db->query($sql);
		
		return $query->result_array();		
	}
		
	function cargasPendentes($id_carga = false)
	{
		
		$filtro = "";
		if($id_carga){
			$filtro = "and C.ID_CARGA = ".$id_carga."";
		}
			
			
		$sql = 
			"SELECT DISTINCT CARGA.*, CD.*, CD.NOME AS NOME_CIDADE,  EST.*, EST.NOME AS NOME_ESTADO, 
			CARGA.ID_CARGA ||' ; '||ELD.ID_CARGA AS DISTRIBUICAO,
			CASE WHEN (TIPO_OPERACAO = 'CIF')
			THEN 'Cif Cliente' 
			WHEN (TIPO_OPERACAO = 'COL')
			THEN 'Coleta'
			WHEN (TIPO_OPERACAO = 'C_EXP')
					THEN 'Carga Expressa'
			WHEN (TIPO_OPERACAO = 'DSC')
			THEN 'Descarregamento'
			WHEN (TIPO_OPERACAO = 'FOB')
			THEN 'FOB Cliente'
			WHEN (TIPO_OPERACAO = 'FRA')
			THEN 'Fracionado'
			WHEN (TIPO_OPERACAO = 'TRA')
			THEN 'Transferencia'
			END DES_OPERACAO,			
			 CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
                       (select T.NOME_TRANSPORTADORA AS NOME                
                        from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
                        WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
                    WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
                        (select CLIENTE.NOME_CLIENTE AS NOME                
                        from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
                        WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
                    END AS NOME_TRANSPORTADORA                                                    
            FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
                JOIN ESM_LOGISTICA_CIDADES CD
                    ON CD.ID = CARGA.CIDADE
                JOIN ESM_LOGISTICA_ESTADOS EST
                    ON EST.ID = CARGA.ESTADO
               LEFT JOIN ESM_LOGISTICA_DISTRIBUICAO ELD
                     ON ELD.ID_CARGA = CARGA.ID_CARGA    
                LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
                    ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
            WHERE STATUS  = 'LOG' or STATUS  = 'EXP' AND CARGA.COMPLEMENTO IS NULL ORDER BY HORA_CHEGADA DESC	
		";
			
		$query = $this->db->query($sql);
		
		return $query->result_array();		
		
	}

	function cargasPendentesComplementos()
	{
		
		$sql = 
			"SELECT DISTINCT CARGA.*, CD.*, CD.NOME AS NOME_CIDADE,  EST.*, EST.NOME AS NOME_ESTADO, 
			CARGA.ID_CARGA ||' ; '||ELD.ID_CARGA AS DISTRIBUICAO,
			CASE WHEN (TIPO_OPERACAO = 'CIF')
					THEN 'Cif Cliente' 
					WHEN (TIPO_OPERACAO = 'COL')
					THEN 'Coleta'
					WHEN (TIPO_OPERACAO = 'C_EXP')
					THEN 'Carga Expressa'
					WHEN (TIPO_OPERACAO = 'DSC')
					THEN 'Descarregamento'
					WHEN (TIPO_OPERACAO = 'FOB')
					THEN 'FOB Cliente'
					WHEN (TIPO_OPERACAO = 'FRA')
					THEN 'Fracionado'
					WHEN (TIPO_OPERACAO = 'TRA')
					THEN 'Transferencia'
					END DES_OPERACAO,	
			 CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
                       (select T.NOME_TRANSPORTADORA AS NOME                
                        from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
                        WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
                    WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
                        (select CLIENTE.NOME_CLIENTE AS NOME                
                        from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
                        WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
                    END AS NOME_TRANSPORTADORA                                                    
            FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
                JOIN ESM_LOGISTICA_CIDADES CD
                    ON CD.ID = CARGA.CIDADE
                JOIN ESM_LOGISTICA_ESTADOS EST
                    ON EST.ID = CARGA.ESTADO
               LEFT JOIN ESM_LOGISTICA_DISTRIBUICAO ELD
                     ON ELD.ID_CARGA = CARGA.ID_CARGA    
                LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
                    ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
            WHERE (STATUS  = 'LOG' or STATUS  = 'EXP') AND CARGA.COMPLEMENTO IS NOT NULL
            ORDER BY HORA_CHEGADA DESC";
			
		$query = $this->db->query($sql);
		
		return $query->result_array();		
		
	}
	
	function cargasLiberadas($id_carga = false)
	{
		
		$filtro = "";
		if($id_carga){
			$filtro = "and CARGA.ID_CARGA = ".$id_carga."";
		}
		
			$sql = "
			SELECT 
				CARGA.*,
				TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
				TO_CHAR(CARGA.HORA_SAIDA_LOGISTICA,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_LOGISTICA,
				TO_CHAR(CARGA.HORA_SAIDA_EXPEDICAO,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_EXPEDICAO,
				TO_CHAR(CARGA.HORA_ENTRAdA,'YYYY-MM-DD HH24:MI:SS') AS HORA_ENTRADA,
				TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA, 
				CD.*, CD.NOME AS NOME_CIDADE,
				EST.*,
				EST.NOME AS NOME_ESTADO, 
				TRA.*,
				CASE WHEN (TIPO_OPERACAO = 'CIF')
					THEN 'Cif Cliente' 
					WHEN (TIPO_OPERACAO = 'COL')
					THEN 'Coleta'
					WHEN (TIPO_OPERACAO = 'C_EXP')
					THEN 'Carga Expressa'
					WHEN (TIPO_OPERACAO = 'DSC')
					THEN 'Descarregamento'
					WHEN (TIPO_OPERACAO = 'FOB')
					THEN 'FOB Cliente'
					WHEN (TIPO_OPERACAO = 'FRA')
					THEN 'Fracionado'
					WHEN (TIPO_OPERACAO = 'TRA')
					THEN 'Transferencia'
					END DES_OPERACAO,				
					CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
					   (select T.NOME_TRANSPORTADORA AS NOME				
						from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
						WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
					WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
						(select CLIENTE.NOME_CLIENTE AS NOME				
						from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
						WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
					END AS NOME_TRANSPORTADORA   						
			FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
				JOIN ESM_LOGISTICA_CIDADES CD
					ON CD.ID = CARGA.CIDADE
				JOIN ESM_LOGISTICA_ESTADOS EST
					ON EST.ID = CARGA.ESTADO
				LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
                    ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
			WHERE STATUS  = 'LIB' ".$filtro." ORDER BY CARGA.DATA_ATUAL DESC
			";
		
			
		$query = $this->db->query($sql);
		
		return $query->result_array();
		
				
	}

	function veiculosCarregando($id_carga = false)
	{
		
		$filtro = "";
		if($id_carga){
			$filtro = "and CARGA.ID_CARGA = ".$id_carga."";
		}
		
			$sql = "
			SELECT 
				CARGA.*,
				TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
				TO_CHAR(CARGA.HORA_SAIDA_LOGISTICA,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_LOGISTICA,
				TO_CHAR(CARGA.HORA_SAIDA_EXPEDICAO,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_EXPEDICAO,
				TO_CHAR(CARGA.HORA_ENTRADA,'YYYY-MM-DD HH24:MI:SS') AS HORA_ENTRADA,
				TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA, 
				CD.*, CD.NOME AS NOME_CIDADE,
				EST.*,
				EST.NOME AS NOME_ESTADO, 
				TRA.*,
				CASE WHEN (TIPO_OPERACAO = 'CIF')
					THEN 'Cif Cliente' 
					WHEN (TIPO_OPERACAO = 'COL')
					THEN 'Coleta'
					WHEN (TIPO_OPERACAO = 'C_EXP')
					THEN 'Carga Expressa'
					WHEN (TIPO_OPERACAO = 'DSC')
					THEN 'Descarregamento'
					WHEN (TIPO_OPERACAO = 'FOB')
					THEN 'FOB Cliente'
					WHEN (TIPO_OPERACAO = 'FRA')
					THEN 'Fracionado'
					WHEN (TIPO_OPERACAO = 'TRA')
					THEN 'Transferencia'
					END DES_OPERACAO,				
					CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
					   (select T.NOME_TRANSPORTADORA AS NOME				
						from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
						WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
					WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
						(select CLIENTE.NOME_CLIENTE AS NOME				
						from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
						WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
					END AS NOME_TRANSPORTADORA   						
			FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
				JOIN ESM_LOGISTICA_CIDADES CD
					ON CD.ID = CARGA.CIDADE
				JOIN ESM_LOGISTICA_ESTADOS EST
					ON EST.ID = CARGA.ESTADO
				LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
                    ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
			WHERE STATUS  = 'CAR' ".$filtro." ORDER BY CARGA.DATA_ATUAL DESC
			";
		
			
		$query = $this->db->query($sql);
		
		return $query->result_array();
		
				
	}

	function veiculosRecusados($id_carga = false)
	{
		
		$filtro = "";
		if($id_carga){
			$filtro = "and CARGA.ID_CARGA = ".$id_carga."";
		}
		
			$sql = "SELECT 
					CARGA.*,
					TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
					TO_CHAR(CARGA.HORA_SAIDA_LOGISTICA,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_LOGISTICA,
					TO_CHAR(CARGA.HORA_SAIDA_EXPEDICAO,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_EXPEDICAO,
					TO_CHAR(CARGA.HORA_ENTRAdA,'YYYY-MM-DD HH24:MI:SS') AS HORA_ENTRADA,
					TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA, 
					CD.*, CD.NOME AS NOME_CIDADE,
					EST.*,
					EST.NOME AS NOME_ESTADO, 
					TRA.*,				
						CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
						(select T.NOME_TRANSPORTADORA AS NOME				
							from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
							WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
						WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
							(select CLIENTE.NOME_CLIENTE AS NOME				
							from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
							WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
						END AS NOME_TRANSPORTADORA   						
				FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
					JOIN ESM_LOGISTICA_CIDADES CD
						ON CD.ID = CARGA.CIDADE
					JOIN ESM_LOGISTICA_ESTADOS EST
						ON EST.ID = CARGA.ESTADO
					LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
						ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
				WHERE STATUS  = 'REC' ".$filtro." ORDER BY CARGA.ID_CARGA ASC";			
		$query = $this->db->query($sql);		
		return $query->result_array();				
	}

	function veiculosRomaneio($id_carga = false)
	{
		
		$filtro = "";
		if($id_carga){
			$filtro = "and CARGA.ID_CARGA = ".$id_carga."";
		}
		
			$sql = "SELECT 
				CARGA.*,
				TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
				TO_CHAR(CARGA.HORA_SAIDA_LOGISTICA,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_LOGISTICA,
				TO_CHAR(CARGA.HORA_SAIDA_EXPEDICAO,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_EXPEDICAO,
				TO_CHAR(CARGA.HORA_ENTRADA,'YYYY-MM-DD HH24:MI:SS') AS HORA_ENTRADA,
				TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA,
				FLOOR((SYSDATE  - CARGA.HORA_CRIACAO_ROMANEIO_INI) * 1440) TEMPO, 
				CD.*, CD.NOME AS NOME_CIDADE,
				EST.*,
				EST.NOME AS NOME_ESTADO, 
				TRA.*,
				CASE WHEN (TIPO_OPERACAO = 'CIF')
					THEN 'Cif Cliente' 
					WHEN (TIPO_OPERACAO = 'COL')
					THEN 'Coleta'
					WHEN (TIPO_OPERACAO = 'C_EXP')
					THEN 'Carga Expressa'
					WHEN (TIPO_OPERACAO = 'DSC')
					THEN 'Descarregamento'
					WHEN (TIPO_OPERACAO = 'FOB')
					THEN 'FOB Cliente'
					WHEN (TIPO_OPERACAO = 'FRA')
					THEN 'Fracionado'
					WHEN (TIPO_OPERACAO = 'TRA')
					THEN 'Transferencia'
					END DES_OPERACAO,				
					CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
					   (select T.NOME_TRANSPORTADORA AS NOME				
						from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
						WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
					WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
						(select CLIENTE.NOME_CLIENTE AS NOME				
						from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
						WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
					END AS NOME_TRANSPORTADORA   						
			FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
				JOIN ESM_LOGISTICA_CIDADES CD
					ON CD.ID = CARGA.CIDADE
				JOIN ESM_LOGISTICA_ESTADOS EST
					ON EST.ID = CARGA.ESTADO
				LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
                    ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
			WHERE STATUS  = 'ROM' ".$filtro." ORDER BY FLOOR((SYSDATE  - CARGA.HORA_CRIACAO_ROMANEIO_INI) * 1440) DESC";
		
			
		$query = $this->db->query($sql);
		
		return $query->result_array();
		
				
	}

	

	function veiculosLibFiscal($id_carga = false)
	{
		
		$filtro = "";
		if($id_carga){
			$filtro = "and CARGA.ID_CARGA = ".$id_carga."";
		}
		
			$sql = "SELECT 
					CARGA.*,
					TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
					TO_CHAR(CARGA.HORA_SAIDA_LOGISTICA,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_LOGISTICA,
					TO_CHAR(CARGA.HORA_SAIDA_EXPEDICAO,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_EXPEDICAO,
					TO_CHAR(CARGA.HORA_ENTRAdA,'YYYY-MM-DD HH24:MI:SS') AS HORA_ENTRADA,
					TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA,
					FLOOR((SYSDATE  - CARGA.HORA_LIB_FISCAL_INI) * 1440) TEMPO, 
					CD.*, CD.NOME AS NOME_CIDADE,
					EST.*,
					EST.NOME AS NOME_ESTADO, 
					TRA.*,
					CASE WHEN (TIPO_OPERACAO = 'CIF')
						THEN 'Cif Cliente' 
						WHEN (TIPO_OPERACAO = 'COL')
						THEN 'Coleta'
						WHEN (TIPO_OPERACAO = 'C_EXP')
					THEN 'Carga Expressa'
						WHEN (TIPO_OPERACAO = 'DSC')
						THEN 'Descarregamento'
						WHEN (TIPO_OPERACAO = 'FOB')
						THEN 'FOB Cliente'
						WHEN (TIPO_OPERACAO = 'FRA')
						THEN 'Fracionado'
						WHEN (TIPO_OPERACAO = 'TRA')
						THEN 'Transferencia'
						END DES_OPERACAO,				
						CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
						(select T.NOME_TRANSPORTADORA AS NOME				
							from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
							WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
						WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
							(select CLIENTE.NOME_CLIENTE AS NOME				
							from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
							WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
						END AS NOME_TRANSPORTADORA   						
				FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
					JOIN ESM_LOGISTICA_CIDADES CD
						ON CD.ID = CARGA.CIDADE
					JOIN ESM_LOGISTICA_ESTADOS EST
						ON EST.ID = CARGA.ESTADO
					LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
						ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
				WHERE STATUS  = 'FIS' ".$filtro." ORDER BY FLOOR((SYSDATE  - CARGA.HORA_LIB_FISCAL_INI) * 1440) DESC";
		
			
		$query = $this->db->query($sql);
		
		return $query->result_array();
		
				
	}

	function veiculosDiferencaPeso()
	{
		$sql = "SELECT 
					CARGA.*,
					TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
					TO_CHAR(CARGA.HORA_SAIDA_LOGISTICA,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_LOGISTICA,
					TO_CHAR(CARGA.HORA_SAIDA_EXPEDICAO,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_EXPEDICAO,
					TO_CHAR(CARGA.HORA_ENTRAdA,'YYYY-MM-DD HH24:MI:SS') AS HORA_ENTRADA,
					TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA,
					FLOOR((SYSDATE  - CARGA.HORA_LIB_FISCAL_FIM) * 1440) TEMPO, 
					CD.*, CD.NOME AS NOME_CIDADE,
					EST.*,
					EST.NOME AS NOME_ESTADO, 
					TRA.*,
					CASE WHEN (TIPO_OPERACAO = 'CIF')
						THEN 'Cif Cliente' 
						WHEN (TIPO_OPERACAO = 'COL')
						THEN 'Coleta'
						WHEN (TIPO_OPERACAO = 'C_EXP')
					    THEN 'Carga Expressa'
						WHEN (TIPO_OPERACAO = 'DSC')
						THEN 'Descarregamento'
						WHEN (TIPO_OPERACAO = 'FOB')
						THEN 'FOB Cliente'
						WHEN (TIPO_OPERACAO = 'FRA')
						THEN 'Fracionado'
						WHEN (TIPO_OPERACAO = 'TRA')
						THEN 'Transferencia'
						END DES_OPERACAO,						
						CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
						(select T.NOME_TRANSPORTADORA AS NOME				
							from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
							WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
						WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
							(select CLIENTE.NOME_CLIENTE AS NOME				
							from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
							WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
						END AS NOME_TRANSPORTADORA   						
				FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
					JOIN ESM_LOGISTICA_CIDADES CD
						ON CD.ID = CARGA.CIDADE
					JOIN ESM_LOGISTICA_ESTADOS EST
						ON EST.ID = CARGA.ESTADO
					LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
						ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
				WHERE STATUS  = 'DIF' ORDER BY TEMPO DESC";
		
			
		$query = $this->db->query($sql);
		
		return $query->result_array();
		
				
	}
	

	
	
	function cargasSaidas($id_carga = false)
	{

		$filtro = "";
		if($id_carga){
			$filtro = "and CARGA.ID_CARGA = ".$id_carga."";
		}
		
		$sql = "SELECT 
					CARGA.*,
					TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,	
					TO_CHAR(CARGA.HORA_SAIDA,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA,								
					TO_CHAR(CARGA.DATA_SAIDA,'YYYY-MM-DD') AS DATA_SAIDA, 
					CD.*, CD.NOME AS NOME_CIDADE,
					EST.*,
					EST.NOME AS NOME_ESTADO, 
					TRA.*,	
					CASE WHEN (TIPO_OPERACAO = 'CIF')
						THEN 'Cif Cliente' 
						WHEN (TIPO_OPERACAO = 'COL')
						THEN 'Coleta'
						WHEN (TIPO_OPERACAO = 'C_EXP')
					THEN 'Carga Expressa'
						WHEN (TIPO_OPERACAO = 'DSC')
						THEN 'Descarregamento'
						WHEN (TIPO_OPERACAO = 'FOB')
						THEN 'FOB Cliente'
						WHEN (TIPO_OPERACAO = 'FRA')
						THEN 'Fracionado'
						WHEN (TIPO_OPERACAO = 'TRA')
						THEN 'Transferencia'
						END DES_OPERACAO,				
						CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
						(select T.NOME_TRANSPORTADORA AS NOME				
							from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
							WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
						WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
							(select CLIENTE.NOME_CLIENTE AS NOME				
							from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
							WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
						END AS NOME_TRANSPORTADORA   						
				FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
					JOIN ESM_LOGISTICA_CIDADES CD
						ON CD.ID = CARGA.CIDADE
					JOIN ESM_LOGISTICA_ESTADOS EST
						ON EST.ID = CARGA.ESTADO
					LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
						ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
				WHERE STATUS  = 'SAI' ".$filtro." ORDER BY CARGA.DATA_ATUAL DESC";
		
		$query = $this->db->query($sql)->result_array();
		
		return $query;
	
			
	}
	
	function pesquisarCargas($id_carga = false, $dados_pesquisa)
	{			
		
		$data_pesquisa1 = "";
		$data_pesquisa2 = "";
		if(!empty($dados_pesquisa['data_pesquisa2'])){
			$data_pesquisa2 = "CARGA.DATA_ATUAL BETWEEN to_date('".$dados_pesquisa['data_pesquisa1']."','YYYY-MM-DD') AND to_date('".$dados_pesquisa['data_pesquisa2']."','YYYY-MM-DD')";			
		} else {
			$data_pesquisa1  = "CARGA.DATA_ATUAL = to_date('".$dados_pesquisa['data_pesquisa1']."','YYYY-MM-DD')";			
		}
		$status = "";
		if($dados_pesquisa['status']){			
			$status = "AND CARGA.STATUS = '".$dados_pesquisa['status']."'";
		}
		
		
			$sql = "SELECT 
				CARGA.*, CLI.*,
				TO_CHAR(CARGA.DATA_ATUAL,'YYYY-MM-DD') AS DATA_ATUAL,
				TO_CHAR(CARGA.HORA_SAIDA_LOGISTICA,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_LOGISTICA,
				TO_CHAR(CARGA.HORA_SAIDA_EXPEDICAO,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA_EXPEDICAO,
				TO_CHAR(CARGA.HORA_ENTRADA,'YYYY-MM-DD HH24:MI:SS') AS HORA_ENTRADA,				  			 
				TO_CHAR(CARGA.HORA_SAIDA,'YYYY-MM-DD HH24:MI:SS') AS HORA_SAIDA,				 
				CD.*, CD.NOME AS NOME_CIDADE,
				EST.*,
				EST.NOME AS NOME_ESTADO, 
				TRA.*,
				CARGA.DISTRIBUICAO,
				CLI.NOME_CLIENTE AS NOME_CLIENTE,
				
					CASE WHEN CARGA.TIPO_TRANSPORTADORA = 'T' THEN
					   (select T.NOME_TRANSPORTADORA AS NOME				
						from ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
						WHERE T.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA)                
					WHEN CARGA.TIPO_TRANSPORTADORA = 'C' THEN
						(select CLIENTE.NOME_CLIENTE AS NOME				
						from ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
						WHERE CLIENTE.ID_CLIENTE = CARGA.ID_TRANSPORTADORA)
					END AS NOME_TRANSPORTADORA,

					CASE WHEN CARGA.COMPLEMENTO IS NULL THEN
					   ('NAO' )                
					ELSE
					  ('SIM') 
					END AS POSSUI_COMPLEMENTO,
					CASE WHEN (CARGA.TIPO_OPERACAO = 'CIF')
					THEN 'CIF CLIENTE'
					WHEN (CARGA.TIPO_OPERACAO = 'COL')
					THEN 'COLETA'
					WHEN (TIPO_OPERACAO = 'C_EXP')
					THEN 'CARGA EXPRESSA'
					WHEN (CARGA.TIPO_OPERACAO = 'TRA')
					THEN 'TRANSFERENCIA'
					WHEN (CARGA.TIPO_OPERACAO = 'FOB')
					THEN 'FOB CLIENTE'
					WHEN (CARGA.TIPO_OPERACAO = 'FRA')
					THEN 'FRACIONADO'
					WHEN (CARGA.TIPO_OPERACAO = 'DSC')
					THEN 'DESCARREGAMENTO'
					END DESC_OPERACAO   						
			FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
				JOIN ESM_LOGISTICA_CIDADES CD
					ON CD.ID = CARGA.CIDADE
				JOIN ESM_LOGISTICA_ESTADOS EST
					ON EST.ID = CARGA.ESTADO
				LEFT JOIN ESM_LOGISTICA_CLIENTE CLI
					ON CLI.ID_CLIENTE = CARGA.ID_CLIENTE   	
				LEFT JOIN ESM_LOGISTICA_TRANSPORTADORA TRA
                    ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA 
			WHERE ".$data_pesquisa1." ".$data_pesquisa2." ".$status." ORDER BY  CARGA.DATA_ATUAL DESC";
				
		$query = $this->db->query($sql);
		
		return $query->result_array();
		
		
	}
	
	function liberar($id_carga, $carga)
	{		
		$this->db->query("UPDATE ESM_LOGISTICA_CARGA	
								SET 
								
								HORA_ENTRADA = ".$carga['HORA_ENTRADA'].",								
								STATUS = '".$carga['STATUS']."'														
								WHERE ID_CARGA = '".$id_carga."'");		
		return true;		
	}
	
	function liberar_tra($id_carga, $carga)
	{		
		$this->db->query("UPDATE ESM_LOGISTICA_CARGA	
								SET HORA_SAIDA = ".'SYSDATE'.",
								/*PESO_SAIDA = '".$id_carga[1]."',*/							
								STATUS = '".$carga['STATUS']."'
								WHERE ID_CARGA = '".$id_carga."'");		
		return true;		
	}

	function diferenca_peso($id_carga, $carga)
	{
		
		$this->db->query("UPDATE ESM_LOGISTICA_CARGA	
								SET 						
								STATUS = '".$carga['STATUS']."',
								INFORMACOES_FISCAIS = 'VEICULO COM DIFER. PESO',
								HORA_DIF_INI = SYSDATE														
								WHERE ID_CARGA = '".$id_carga."'");		
		return true;		
	}
	
	function liberar_saida($id_carga, $carga)
	{				
		$this->db->query("UPDATE ESM_LOGISTICA_CARGA 
							   SET
							   STATUS = '".$carga['STATUS']."',
							   HORA_DIF_FIM = SYSDATE 
							   WHERE ID_CARGA = '".$id_carga."'");		
		return true;
		
	}
	
	
	function saida($id_carga, $status)
	{		
		$this->db->where('ID_CARGA', $id_carga);
		$this->db->update('ESM_LOGISTICA_CARGA', $status);
	}
	
	function cancelar($id_carga, $status)
	{		
		$this->db->where('ID_CARGA', $id_carga);
		$this->db->update('ESM_LOGISTICA_CARGA', $status);
	}
	
	function verificDistrib($id_carga){
		$this->db->select("COD_DISTRIBUICAO_EBS");
		$this->db->where("ID_CARGA",$id_carga);
		$num = $this->db->get("ESM_LOGISTICA_DISTRIBUICAO")->num_rows();		
		return $num;	

	}
	
	function getStatus($id_carga){
		$this->db->select('STATUS');
		$this->db->where('ID_CARGA', $id_carga);
		$status = $this->db->get('ESM_LOGISTICA_CARGA')->result_array();
		return $status[0]['STATUS'];
	}
	
	function defineLT($param)
	{
		$leadtime = $param['time'];
		$id = $param['cod_agendamento'];
		$sql="select data_agendamento from esm_logistica_agendamento where cod_agendamento = ". $id;
		$cod = $this->db->query($sql)->result_array();
		$sql="UPDATE esm_logistica_agendamento SET data_limite=(DATA_AGENDAMENTO - INTERVAL '".$param['time']."' DAY) WHERE COD_AGENDAMENTO =".$id;
		$this->db->query($sql);				
	}
	function trocaTransp($param){
		
		foreach($param['id_carga'] as $id_carga)
		{						
			$this->db->query("update esm_logistica_carga set id_transportadora = '".$param['transp']."' where id_carga =".$id_carga['value']);
		}			
			 			
	}

	function processa_descarga($info)
	{
		$sql_busca_informacoes = "SELECT 
		TO_CHAR(DATA_ATUAL,'DD/MM/YYYY') DATA_ATUAL, ID_TRANSPORTADORA, 
		ESTADO,                                      CIDADE, 
        VEICULO,                                     PLACA_VEICULO,   
		PLACA_CAVALO,                                NUM_CONTAINER,     
		TO_CHAR(TO_DATE(HORA_CHEGADA,'HH24:MI'),'HH24:MI') HORA_CHEGADA,
        ID_CLIENTE,                                  TIPO_DOCUMENTO,  
		DOCUMENTOS,                                  OBSERVACAO, 
        DATA_SAIDA,                                  SENHA_AGENDAMENTO, 
        'LIB' STATUS,                                TIPO_TRANSPORTADORA, 
        TELEFONE_CONTATO,                            'DSC' TIPO_OPERACAO, 
        COD_RECEBEDOR,                               DOCA,
		CONFERENTE,                                  CAPATAZIA,                                 
        TAMANHO_VEICULO,                             DISTRIBUICAO,
        CPF_RECEBEDOR,                               TIPO_RECEBEDOR,
        CPF_MOTORISTA 
        FROM ESMALTEC.ESM_LOGISTICA_CARGA WHERE ID_CARGA =".$info['registro'];

		$dados = ($this->db->query($sql_busca_informacoes)->result_array());

		$sql_insere_dados = "INSERT INTO ESMALTEC.ESM_LOGISTICA_CARGA (
		ID_CARGA,
		DATA_ATUAL,
        ID_TRANSPORTADORA, 
        ESTADO, 
        CIDADE, 
        VEICULO, 
        PLACA_VEICULO, 
        PLACA_CAVALO, 
        NUM_CONTAINER, 
        HORA_CHEGADA,
        ID_CLIENTE,
        TIPO_DOCUMENTO,
        DOCUMENTOS,
        OBSERVACAO, 
        DATA_SAIDA, 
        SENHA_AGENDAMENTO,
		HORA_ENTRADA, 
        STATUS,
		HORA_SAIDA_LOGISTICA, 
        TIPO_TRANSPORTADORA, 
        TELEFONE_CONTATO, 
        TIPO_OPERACAO, 
        COD_RECEBEDOR, 
        DOCA,
		CONFERENTE, 
        CAPATAZIA, 
        TAMANHO_VEICULO,
        DISTRIBUICAO,
        CPF_RECEBEDOR,
        TIPO_RECEBEDOR,
		LOCAL_CARREGAMENTO,
        CPF_MOTORISTA ) 
		VALUES (SEQ_ESM_LOGISTICA_CARGA.NEXTVAL,
		
		TO_DATE('".$dados[0]['DATA_ATUAL']."','DD/MM/YYYY'),
        '".$dados[0]['ID_TRANSPORTADORA']."', 
        '".$dados[0]['ESTADO']."', 
        '".$dados[0]['CIDADE']."', 
        '".$dados[0]['VEICULO']."', 
        '".$dados[0]['PLACA_VEICULO']."', 
        '".$dados[0]['PLACA_CAVALO']."', 
        '".$dados[0]['NUM_CONTAINER']."', 
        '".$dados[0]['HORA_CHEGADA']."',
        '".$dados[0]['ID_CLIENTE']."',
        '".$dados[0]['TIPO_DOCUMENTO']."',
        '".$dados[0]['DOCUMENTOS']."',
        '".$dados[0]['OBSERVACAO']."', 
        SUBSTR('".$dados[0]['DATA_SAIDA']."',0,10),
        '".$dados[0]['SENHA_AGENDAMENTO']."',
		SYSDATE, 
        '".$dados[0]['STATUS']."',
		SYSDATE, 
        '".$dados[0]['TIPO_TRANSPORTADORA']."', 
        '".$dados[0]['TELEFONE_CONTATO']."', 
        '".$dados[0]['TIPO_OPERACAO']."', 
        '".$dados[0]['COD_RECEBEDOR']."', 
        '".$dados[0]['DOCA']."',
		'".$dados[0]['CONFERENTE']."', 
        '".$dados[0]['CAPATAZIA']."', 
        '".$dados[0]['TAMANHO_VEICULO']."',
        '".$dados[0]['DISTRIBUICAO']."',
        '".$dados[0]['CPF_RECEBEDOR']."',
        '".$dados[0]['TIPO_RECEBEDOR']."',
		'".$info['local_descarga']."',
        '".$dados[0]['CPF_MOTORISTA']."')";

		$this->db->query($sql_insere_dados);

		return true;	

	}

	function buscaHistorico($dados_pesquisa = null)
	{
		$filtros = "";

		if(!empty($dados_pesquisa['data_inicio']))
		{
			$filtros.=" AND TO_CHAR(M.DATA_EXECUCAO,'DD/MM/YYYY') 
					BETWEEN TO_CHAR(TO_DATE('".$dados_pesquisa['data_inicio']."','DD/MM/YYYY')) 
						AND TO_CHAR(TO_DATE('".$dados_pesquisa['data_fim']."','DD/MM/YYYY')) ";
		}
		
		if(!empty($dados_pesquisa['placa_carreta']))
		{
			$filtros.=" AND UPPER(C.PLACA_VEICULO) = UPPER('".$dados_pesquisa['placa_carreta']."') ";
		}
		if(!empty($dados_pesquisa['operacao']))
		{
			$filtros.=" AND UPPER(M.DATA_EXECUCAO) LIKE(UPPER('%".$dados_pesquisa['operacao']."%')) ";
		}
		

		/*$sql = "SELECT M.USUARIO MATRICULA, F.NOM_FUNCIONARIO, C.PLACA_VEICULO, M.OPERACAO,C.LOCAL_CARREGAMENTO,M.DATA_EXECUCAO  
				 FROM ESM_LOGISTICA_MOVIMENTACAO M, 
					  ESM_LOGISTICA_CARGA C,
					  LOGIX.FUNCIONARIO F
		        WHERE C.ID_CARGA = M.ID_CARGA 
				--AND '588'||SUBSTR(NUM_MATRICULA,-6) = M.USUARIO
				AND NUM_MATRICULA = M.USUARIO
				".$filtros."
				AND ROWNUM < 100
				ORDER BY M.DATA_EXECUCAO DESC";*/
				$sql = "SELECT T.* FROM(
		        SELECT M.USUARIO MATRICULA, F.NOM_FUNCIONARIO, C.PLACA_VEICULO, M.OPERACAO,C.LOCAL_CARREGAMENTO,M.DATA_EXECUCAO  
				 FROM ESM_LOGISTICA_MOVIMENTACAO M, 
					  ESM_LOGISTICA_CARGA C, 
					  LOGIX.FUNCIONARIO F
		        WHERE C.ID_CARGA = M.ID_CARGA
				AND M.USUARIO = F.NUM_MATRICULA 
				".$filtros."
				ORDER BY M.DATA_EXECUCAO DESC)T
				WHERE ROWNUM < 100";
						
		return $this->db->query($sql)->result_array();
	}

	function gravaOperacao($idcarga,$msg_update,$usuario_operacao)
	{
		$sql = "INSERT INTO ESM_LOGISTICA_MOVIMENTACAO VALUES (ESM_LOGISTICA_MOVIMENTACAO_SEQ.NEXTVAL,'".$idcarga."','".$msg_update."','".$usuario_operacao."',SYSDATE)";
		return $this->db->query($sql);
		
	}
	
}
?>