<?php
class Acidente_Model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->database();

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

	function getAcidentes($COD_ATEND_SOCIAL = "", $NUM_MATRICULA = "") {
		$DB3 = $this -> load -> database('logix', TRUE);

		$filtro = "";
		
		if ($COD_ATEND_SOCIAL != "") {
			$filtro .= "AND COD_ATEND_SOCIAL = " . $COD_ATEND_SOCIAL . " ";
		}
		if ($NUM_MATRICULA != "") {
			$filtro .= "AND EA.NUM_MATRICULA = " . $NUM_MATRICULA . " ";
		}else if($COD_ATEND_SOCIAL == "" && $NUM_MATRICULA == ""){
			$filtro .= "AND EA.STATUS_OCORRENCIA = 1 ";
		}
		

		$acidentes = $DB3->query("SELECT EA.COD_ATEND_SOCIAL, F.NUM_MATRICULA, F.NOM_COMPLETO,
										   CASE EA.STATUS_OCORRENCIA
											  WHEN 1
												 THEN 'EM ANDAMENTO'
											  WHEN 2
												 THEN 'CONCLUÍDO'
											  WHEN 3
												 THEN 'CANCELADO'
										   END STATUS,
										   T.DEN_TIPO_ATENDIMENTO,
										   TO_CHAR (EA.DATA_INI_ATEND_SOC, 'DD/MM/YYYY') DATA_INI_ATEND_SOC,
										   EA.OCORRENCIA,
										   TO_CHAR (EA.DATA_OCORRENCIA, 'DD/MM/YYYY') DATA_OCORRENCIA,
										   EA.STATUS_OCORRENCIA, EA.RELATO_OCORRENCIA,
										   EA.DEN_CID, EA.RETORNO_TRABALHO, EA.ALTA_MEDICA
									  FROM ESMALTEC.ESM_RH_ATEND_SOCIAL EA,
										   LOGIX.FUNCIONARIO F,
										   ESMALTEC.ESM_RH_TIPO_ATENDIMENTO T
									 WHERE F.NUM_MATRICULA = EA.NUM_MATRICULA
									   AND T.COD_TIPO_ATENDIMENTO = EA.TIPO_ATENDIMENTO
									 " . $filtro." ORDER BY TO_DATE(TO_CHAR (EA.DATA_INI_ATEND_SOC, 'DD/MM/YYYY'),'DD/MM/YYYY') DESC")->result_array();

		return $acidentes;
	}
	
	function getAcompanhamentos($COD_ATEND_SOCIAL = "",$COD_ACOMPANHAMENTO = "", $NUM_MATRICULA = "") {
		$DB3 = $this -> load -> database('logix', TRUE);

		$filtro = "";
		
		if ($COD_ATEND_SOCIAL != "") {
			$filtro .= "AND EA.COD_ATEND_SOCIAL = " . $COD_ATEND_SOCIAL . " ";
		}
		
		if ($COD_ACOMPANHAMENTO != "") {
			$filtro .= "AND E.COD_ACOMPANHAMENTO = " . $COD_ACOMPANHAMENTO . " ";
		}

		$acompanhamento = $DB3->query("SELECT E.COD_ACOMPANHAMENTO, EA.COD_ATEND_SOCIAL, E.HOSPITAL,
										   TO_CHAR (E.DATA_PROX_ACOMPANHAMENTO,
													'DD/MM/YYYY'
												   ) DATA_PROX_ACOMPANHAMENTO,
										   E.OBSERVACOES_ACOMPANHAMENTO, F.NUM_MATRICULA, E.MEDICO, E.ENCAMINHAMENTO, E.HOSPITAL, 
										   TO_CHAR (E.DATA_ACOMPANHAMENTO,'DD/MM/YYYY') DATA_ACOMPANHAMENTO,
										   EA.NUM_MATRICULA
									  FROM ESMALTEC.ESM_RH_ACOMPANHAMENTO E,
										   ESMALTEC.ESM_RH_ATEND_SOCIAL EA,
										   LOGIX.FUNCIONARIO F
									 WHERE E.COD_ATEND_SOCIAL = EA.COD_ATEND_SOCIAL
									   AND F.NUM_MATRICULA = EA.NUM_MATRICULA " . $filtro)->result_array();

		return $acompanhamento;
	}
	
	function getMedicamentos($COD_ACOMPANHAMENTO = "") {
		$DB3 = $this -> load -> database('logix', TRUE);

		$medicamento = $DB3->query("SELECT DEN_MEDICAMENTO, VALOR_MEDICAMENTO
										 FROM ESMALTEC.ESM_RH_MEDICAMENTOS M
										 WHERE M.COD_ACOMPANHAMENTO = ".$COD_ACOMPANHAMENTO."
										 ")->result_array();

		return $medicamento;
	}

	function incluir($parametros) {

		$DB3 = $this->load->database('logix', TRUE);

		$DB3->trans_start();
		//print_r($parametros);
				
		$DB3->query("INSERT INTO ESM_RH_ATEND_SOCIAL(COD_ATEND_SOCIAL, 
					   OCORRENCIA, RELATO_OCORRENCIA, STATUS_OCORRENCIA, 
					   TIPO_ATENDIMENTO, NUM_MATRICULA, 
					   DATA_OCORRENCIA, ALTA_MEDICA, DEN_CID, RETORNO_TRABALHO, 
					   DATA_INI_ATEND_SOC, ATENDENTE) 
					 VALUES(ESM_RH_ATEND_SOCIAL_SEQ.NEXTVAL, '". utf8_decode($parametros['OCORRENCIA']) . "',
					 '".utf8_decode($parametros['RELATO_OCORRENCIA']) . "','" . $parametros['STATUS_OCORRENCIA'] . "',
					 '".$parametros['TIPO_ATENDIMENTO'] ."','" . $parametros['NUM_MATRICULA'] . "',
					 '".$parametros['DATA_OCORRENCIA']."',
					 '".$parametros['ALTA_MEDICA']."','".utf8_decode($parametros['DEN_CID'])."','".$parametros['RETORNO_TRABALHO']."',SYSDATE,".$this->session->userdata('idRegister').")");
		//print_r($sql);
		//die();
		$COD_ATEND = $DB3->query("SELECT MAX(COD_ATEND_SOCIAL) COD_ATEND_SOCIAL FROM ESM_RH_ATEND_SOCIAL")->result_array();
		
		
		//die();
		$this->incluirAcomp($COD_ATEND[0]["COD_ATEND_SOCIAL"], $parametros);
		
		$DB3 -> trans_complete();
		if ($DB3 -> trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}
	
	function incluirAcomp($COD_ATEND_SOCIAL, $parametros){
		$DB3 = $this->load->database('logix', TRUE);

		$DB3->trans_start();
		
		$encaminhamento = "N";
		if(isset($parametros['ENCAMINHAMENTO']) && $parametros['ENCAMINHAMENTO'] == "on"){
			$encaminhamento = "S";
		}
			
		$DB3->query("INSERT INTO ESM_RH_ACOMPANHAMENTO(COD_ACOMPANHAMENTO, 
					   COD_ATEND_SOCIAL, HOSPITAL, MEDICO, ENCAMINHAMENTO,
					   DATA_PROX_ACOMPANHAMENTO, OBSERVACOES_ACOMPANHAMENTO, DATA_ACOMPANHAMENTO, ATENDENTE) 
					 VALUES(ESM_RH_ACOMPANHAMENTO_SEQ.NEXTVAL, ". $COD_ATEND_SOCIAL . ",
					 '".utf8_decode($parametros['HOSPITAL']) . "','" . utf8_decode($parametros['MEDICO']) . "', '".$encaminhamento."',
					 '".$parametros['DATA_PROX_ACOMPANHAMENTO'] ."','" . utf8_decode($parametros['OBSERVACOES_ACOMPANHAMENTO']) . "',SYSDATE, ".$this->session->userdata('idRegister').")");
		//print_r($sql);
		//die();
		$COD_ACOMPANHAMENTO = $DB3->query("SELECT MAX(COD_ACOMPANHAMENTO) COD_ACOMPANHAMENTO FROM ESM_RH_ACOMPANHAMENTO")->result_array();
		
		if($parametros["DEN_MEDICAMENTO"][0] != ""){
			$this->incluirMedicamento($COD_ACOMPANHAMENTO[0]["COD_ACOMPANHAMENTO"], $parametros);
		}

		$DB3 -> trans_complete();
		if ($DB3 -> trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}
	
	function incluirMedicamento($COD_ACOMPANHAMENTO, $parametros){
		$DB3 = $this->load->database('logix', TRUE);

		$DB3->trans_start();

		for($i=0;$i<sizeof($parametros['DEN_MEDICAMENTO']);$i++){
				
			$DB3->query("INSERT INTO ESM_RH_MEDICAMENTOS(COD_ACOMPANHAMENTO, 
						   DEN_MEDICAMENTO, VALOR_MEDICAMENTO) 
						 VALUES(". $COD_ACOMPANHAMENTO . ",
						 '".utf8_decode($parametros['DEN_MEDICAMENTO'][$i]) . "'," .str_replace(',','.',str_replace('.','',$parametros['VALOR_MEDICAMENTO'][$i])) . ")");
		}
		$DB3 -> trans_complete();
		if ($DB3 -> trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}

	function alterar($parametros) {
		$DB3 = $this->load->database('logix', TRUE);

		$DB3 -> trans_start();
		
		$up = "";
		if($parametros['STATUS_OCORRENCIA'] == "3"){
			$up = ", DATA_FIM_ATEND_SOC = SYSDATE";
		}
		
				
		$DB3->query("UPDATE ESM_RH_ATEND_SOCIAL 
						SET OCORRENCIA = '" . utf8_decode($parametros['OCORRENCIA']) . "', 
						RELATO_OCORRENCIA = '" . utf8_decode($parametros['RELATO_OCORRENCIA']) . "', 
						STATUS_OCORRENCIA = '". $parametros['STATUS_OCORRENCIA'] ."',
						TIPO_ATENDIMENTO = '". $parametros['TIPO_ATENDIMENTO']."',
						NUM_MATRICULA = '". $parametros['NUM_MATRICULA']."',
						DATA_OCORRENCIA = '". $parametros['DATA_OCORRENCIA']."',
						ALTA_MEDICA = '". $parametros['ALTA_MEDICA']."',
						DEN_CID = '". utf8_decode($parametros['DEN_CID'])."',
						RETORNO_TRABALHO = '". $parametros['RETORNO_TRABALHO']."',
						ATENDENTE = ".$this->session->userdata('idRegister')."
						".$up."
						WHERE COD_ATEND_SOCIAL = '" . $parametros['COD_ATEND_SOCIAL'] . "'");

		if($parametros['COD_ACOMPANHAMENTO'] == ''){
			if($parametros['MEDICO'] != ""){
				$this->incluirAcomp($parametros['COD_ATEND_SOCIAL'], $parametros);
			}
		}else{
			$this->alterarAcomp($parametros);
		}
		
		$DB3 -> trans_complete();
		if ($DB3 -> trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}
	
	function alterarAcomp($parametros){
		$DB3 = $this->load->database('logix', TRUE);
		
		$DB3->trans_start();
		
		$encaminhamento = "N";
		if(isset($parametros['ENCAMINHAMENTO']) && $parametros['ENCAMINHAMENTO'] == "on"){
			$encaminhamento = "S";
		}
		
		$DB3->query("UPDATE ESM_RH_ACOMPANHAMENTO 
					SET HOSPITAL = '".utf8_decode($parametros['HOSPITAL'])."', 
						MEDICO = '".utf8_decode($parametros['MEDICO'])."',
						ENCAMINHAMENTO = '".$encaminhamento."',
						DATA_PROX_ACOMPANHAMENTO = '".$parametros['DATA_PROX_ACOMPANHAMENTO']."',
						OBSERVACOES_ACOMPANHAMENTO = '".utf8_decode($parametros['OBSERVACOES_ACOMPANHAMENTO'])."',
						ATENDENTE = ".$this->session->userdata('idRegister')."
					WHERE COD_ATEND_SOCIAL = ".$parametros['COD_ATEND_SOCIAL']."
					  AND COD_ACOMPANHAMENTO = ".$parametros['COD_ACOMPANHAMENTO']."");
		
		$this->alterarMedicamento($parametros);
		
		$DB3->trans_complete();
		if ($DB3->trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}
		
		return $retorno;
	}
	
	function alterarMedicamento($parametros){
		$DB3 = $this->load->database('logix', TRUE);
		
		$DB3->trans_start();
		
		$DB3->query("DELETE FROM ESM_RH_MEDICAMENTOS WHERE COD_ACOMPANHAMENTO = ".$parametros['COD_ACOMPANHAMENTO']."");
		
		$this->incluirMedicamento($parametros['COD_ACOMPANHAMENTO'],$parametros);
		
		$DB3->trans_complete();
		if ($DB3->trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}
		
		return $retorno;
	}

}
