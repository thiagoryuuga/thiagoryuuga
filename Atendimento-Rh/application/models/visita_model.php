<?php
class Visita_Model extends CI_Model {

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
	
	
	function getVisitas($COD_VISITA = "", $NUM_MATRICULA = "") {
		$DB3 = $this->load->database('logix', TRUE);

		$filtro = "";
		if ($COD_VISITA != "") {
			$filtro = " AND COD_VISITA = " . $COD_VISITA . " ";
		}
		if ($NUM_MATRICULA != ""){
			$filtro .= " AND F.NUM_MATRICULA = ".$NUM_MATRICULA." ";
		}

		$visitas = $DB3 -> query("SELECT V.COD_VISITA, V.NUM_MATRICULA, F.NOM_COMPLETO,
												       TO_CHAR(V.DATA_VISITA,'DD/MM/YYYY') DATA_VISITA, V.RELATO_VISITA
												  FROM ESMALTEC.ESM_RH_VISITA V,
												       LOGIX.FUNCIONARIO F												      
												 WHERE F.NUM_MATRICULA = V.NUM_MATRICULA " . $filtro. 
												 " ORDER BY TO_DATE(TO_CHAR(V.DATA_VISITA,'DD/MM/YYYY'),'DD/MM/YYYY') DESC") -> result_array();

		return $visitas;
	}

	function incluir($parametros) {

		$DB3 = $this->load->database('logix', TRUE);

		$DB3->trans_start();

		$DB3->query("INSERT INTO ESM_RH_VISITA(COD_VISITA, DATA_VISITA, RELATO_VISITA, NUM_MATRICULA, ATENDENTE) 
					 VALUES(ESM_RH_VISITA_SEQ.NEXTVAL, TO_DATE('".$parametros['DATA_VISITA'] . "','DD/MM/YYYY'),'" . utf8_decode($parametros['RELATO_VISITA']) . "','".$parametros['NUM_MATRICULA'] ."', ".$this->session->userdata('idRegister').")");
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

		$DB3 -> query("UPDATE ESM_RH_VISITA 
						SET DATA_VISITA = TO_DATE('" . $parametros['DATA_VISITA'] . "','DD/MM/YYYY'), 
						RELATO_VISITA = '" . utf8_decode($parametros['RELATO_VISITA']) . "', 
						NUM_MATRICULA = '". $parametros['NUM_MATRICULA'] ."',
						ATENDENTE = ".$this->session->userdata('idRegister')."
						WHERE COD_VISITA = '" . $parametros['COD_VISITA'] . "'");

		$DB3 -> trans_complete();
		if ($DB3 -> trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}

}
