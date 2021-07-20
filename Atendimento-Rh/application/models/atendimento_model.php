<?php
class Atendimento_Model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this -> load -> database();

	}

	function getFuncionario($matricula) {
		$DB2 = $this->load->database('funcio', TRUE);

		$funcio = $DB2->query("SELECT DISTINCT F.NUM_MATRICULA, 
										                F.NOM_COMPLETO, 
										                UF.DEN_UNI_FUNCIO,
										                C.DEN_CARGO, 
										                TO_CHAR(F.DAT_ADMIS,'DD/MM/YYYY') DAT_ADMIS, 
										                BA.DEN_BAIRRO, 
										                CI.DEN_CIDADE,
										                FI.END_FUNCIO, 
										                FI.NUM_CART_IDENT, FI.NUM_CPF,
										                FI.NUM_TELEF_RES,
										                FI.NUM_PIS
										           FROM FUNCIONARIO F,
										                FUN_INFOR FI,
										                UNIDADE_FUNCIONAL UF,
										                CARGO C,
										                CIDADES CI,
										                BAIRROS BA
										          WHERE F.COD_EMPRESA = FI.COD_EMPRESA
										            AND F.NUM_MATRICULA = FI.NUM_MATRICULA
										            AND UF.COD_EMPRESA = F.COD_EMPRESA
										            AND UF.COD_UNI_FUNCIO = F.COD_UNI_FUNCIO
										            AND C.COD_EMPRESA = F.COD_EMPRESA
										            AND C.COD_CARGO = F.COD_CARGO
										            AND CI.COD_CIDADE = FI.COD_CIDADE
										            AND BA.COD_BAIRRO = FI.COD_BAIRRO
										            AND BA.COD_CIDADE = CI.COD_CIDADE
										            AND F.DAT_DEMIS IS NULL
										            AND F.NUM_MATRICULA = trim('".trim($matricula)."')")->result_array();
		return $funcio;
	}
	
	function tipoAtendimento(){
		 $DB3 = $this->load->database('logix', TRUE);
		 $tipo = $DB3->query("SELECT COD_TIPO_ATENDIMENTO, DEN_TIPO_ATENDIMENTO FROM ESM_RH_TIPO_ATENDIMENTO WHERE STATUS_TIPO_ATENDIMENTO = 'A' AND NIVEL_AREA = ".$this->session->userdata('idLevel')."ORDER BY DEN_TIPO_ATENDIMENTO ASC")->result_array();
		 return $tipo;
	}

	function getAtendimentos($COD_ATENDIMENTO = "", $NUM_MATRICULA = "") {
		$DB3 = $this -> load -> database('logix', TRUE);

		$filtro="";

		//$filtro = "and EA.NIVEL_AREA = ".$this->session->userdata('idLevel')." ";
		
		if ($COD_ATENDIMENTO != "") {
			$filtro .= "AND COD_ATENDIMENTO = " . $COD_ATENDIMENTO . " ";
		}
		if ($NUM_MATRICULA != "") {
			$filtro .= "AND EA.NUM_MATRICULA = " . $NUM_MATRICULA . " ";
		} if($COD_ATENDIMENTO == "" && $NUM_MATRICULA == ""){
			$filtro .= "AND EA.STATUS_ATENDIMENTO IN (1)";
		}
		

		$atendimentos = $DB3->query("SELECT EA.COD_ATENDIMENTO, EA.NUM_MATRICULA, F.NOM_COMPLETO,
												       TO_CHAR(DATA_INI_ATENDIMENTO,'DD/MM/YYYY') DATA_INI_ATENDIMENTO, T.DEN_TIPO_ATENDIMENTO, TIPO_ATENDIMENTO,
												       DEN_ATENDIMENTO, 
												       STATUS_ATENDIMENTO,
												       CASE STATUS_ATENDIMENTO
												          WHEN 1
												             THEN 'Em Andamento'
												          WHEN 2
												             THEN 'Concluído'
												          WHEN 3
												             THEN 'Cancelado'
												       END STATUS,
												       OBSERVACAO_ATENDIMENTO
												  FROM ESMALTEC.ESM_RH_ATENDIMENTO EA,
												       LOGIX.FUNCIONARIO F,
												       ESMALTEC.ESM_RH_TIPO_ATENDIMENTO T
												 WHERE F.NUM_MATRICULA = EA.NUM_MATRICULA
												   AND F.DAT_DEMIS IS NULL
												   AND T.COD_TIPO_ATENDIMENTO = EA.TIPO_ATENDIMENTO
												   AND EA.NIVEL_AREA = ".$this->session->userdata('idLevel')." 
												   " . $filtro ." ORDER BY TO_DATE(DATA_INI_ATENDIMENTO) DESC")->result_array();

		return $atendimentos;
	}

	function incluir($parametros) {

		$DB3 = $this->load->database('logix', TRUE);

		$DB3->trans_start();
		
		$filtro = "NULL";
		
		if($parametros['STATUS_ATENDIMENTO'] == 2 || $parametros['STATUS_ATENDIMENTO'] == 3){
			$filtro = "sysdate";
		}
				
		$DB3 -> query("INSERT INTO ESM_RH_ATENDIMENTO(COD_ATENDIMENTO, 
													  DEN_ATENDIMENTO, 
													  STATUS_ATENDIMENTO, 
													  TIPO_ATENDIMENTO, 
													  NUM_MATRICULA,
													  OBSERVACAO_ATENDIMENTO,
													  DATA_INI_ATENDIMENTO, 
													  NIVEL_AREA, 
													  ATENDENTE,
													  DATA_FIM_ATENDIMENTO) 
					 VALUES(ESM_RH_ATENDIMENTO_SEQ.NEXTVAL, 
					 		'". utf8_decode($parametros['DEN_ATENDIMENTO']) . "',
					 		'" . $parametros['STATUS_ATENDIMENTO'] . "',
					 		'" . $parametros['TIPO_ATENDIMENTO'] . "',
					 		'".$parametros['NUM_MATRICULA'] ."',
					 		'" . utf8_decode($parametros['OBSERVACAO_ATENDIMENTO']) . "',
					 		SYSDATE, 
					 		".$this->session->userdata('idLevel').", 
					 		".$this->session->userdata('idRegister').",
							".$filtro.")");
		//print_r($sql);
		//die();

		$DB3 -> trans_complete();
		if ($DB3 -> trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}

	function alterar($parametros) {
		$DB3 = $this -> load -> database('logix', TRUE);

		$DB3 -> trans_start();

		$filtro = "";

		if($parametros['STATUS_ATENDIMENTO'] == 2 || $parametros['STATUS_ATENDIMENTO'] == 3){
			$filtro = ", DATA_FIM_ATENDIMENTO = sysdate";
		}

		$DB3 -> query("UPDATE ESM_RH_ATENDIMENTO 
						SET DEN_ATENDIMENTO = '" . utf8_decode($parametros['DEN_ATENDIMENTO']) . "', 
						STATUS_ATENDIMENTO = '" . $parametros['STATUS_ATENDIMENTO'] . "', 
						NUM_MATRICULA = '". $parametros['NUM_MATRICULA'] ."',
						TIPO_ATENDIMENTO = '". $parametros['TIPO_ATENDIMENTO']."',
						OBSERVACAO_ATENDIMENTO = '". utf8_decode($parametros['OBSERVACAO_ATENDIMENTO'])."',
						NIVEL_AREA = ".$this->session->userdata('idLevel').",
						ATENDENTE = ".$this->session->userdata('idRegister')."
						".$filtro."
						WHERE COD_ATENDIMENTO = '" . $parametros['COD_ATENDIMENTO'] . "'");

		$DB3 -> trans_complete();
		if ($DB3 -> trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}

	function pesquisa_atendimento($param)
	{
		$DB3 = $this -> load -> database('logix', TRUE);
		$sql ="SELECT EA.COD_ATENDIMENTO, 
					  EA.NUM_MATRICULA,
					  EA.ATENDENTE ATENDENTE,
					  F.NOM_COMPLETO,
					  NVL(F2.NOM_COMPLETO,'ATENDENTE EXTERNO') NOM_ATENDENTE,
					  UF.DEN_UNI_FUNCIO,
					  C.DEN_CARGO,
					  TO_CHAR(DATA_INI_ATENDIMENTO,'DD/MM/YYYY HH24:MI:SS') DATA_INI_ATENDIMENTO,
					  UPPER(T.DEN_TIPO_ATENDIMENTO) DEN_TIPO_ATENDIMENTO, 
					  UPPER(TIPO_ATENDIMENTO) TIPO_ATENDIMENTO,
					  UPPER(TO_CHAR(DEN_ATENDIMENTO)) DEN_ATENDIMENTO, 
					  UPPER(STATUS_ATENDIMENTO),
					  CASE STATUS_ATENDIMENTO
					       WHEN 1
							  THEN 'EM ANDAMENTO'
						   WHEN 2
							  THEN 'CONCLUIDO'
						   WHEN 3
							  THEN 'CANCELADO'
					  END STATUS,
					  UPPER(TO_CHAR(OBSERVACAO_ATENDIMENTO)) OBSERVACAO_ATENDIMENTO
			  FROM ESMALTEC.ESM_RH_ATENDIMENTO EA,
				   LOGIX.FUNCIONARIO F,
				   LOGIX.FUNCIONARIO F2,
				   LOGIX.CARGO C,
				   LOGIX.UNIDADE_FUNCIONAL UF,
				   ESMALTEC.ESM_RH_TIPO_ATENDIMENTO T
			WHERE F.NUM_MATRICULA = EA.NUM_MATRICULA
			AND   F.DAT_DEMIS IS NULL
			AND   T.COD_TIPO_ATENDIMENTO = EA.TIPO_ATENDIMENTO
			AND   F2.NUM_MATRICULA(+) = EA.ATENDENTE
			AND   UF.COD_EMPRESA = F.COD_EMPRESA
			AND   UF.COD_UNI_FUNCIO = F.COD_UNI_FUNCIO
			AND   UF.DAT_VALIDADE_FIM > SYSDATE
			AND   C.COD_EMPRESA = F.COD_EMPRESA
			AND   C.COD_CARGO = F.COD_CARGO
			AND   C.DAT_VALIDADE_FIM > SYSDATE
			AND   EA.COD_ATENDIMENTO = '".$param."' 
			ORDER BY TO_DATE(DATA_INI_ATENDIMENTO) DESC";

			$busca = $DB3->query($sql)->result_array();
			return $busca;
	}

	function grava_impressao($atendimento,$atendente)
	{
		$DB3 = $this->load->database('logix', TRUE);
		$sql = "INSERT INTO ESMALTEC.ESM_RH_IMPRESSAO_ATENDIMENTO VALUES (ESMALTEC.ESM_RH_IMPRESSAO_ATEND_SEQ.NEXTVAL,'".$atendimento."',
		'".$atendente."','".$this->session->userdata['idRegister']."' ,SYSDATE)";
		$insert = $DB3->query($sql);

		return true;
	}

}
