<?php
class Afastamento_Model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this -> load -> database();

	}

	function getFuncionario($COD_ATEND_SOCIAL) {
		$DB3 = $this->load->database('logix', TRUE);
		
		$NUM_MATRICULA = $DB3->query("SELECT NUM_MATRICULA FROM ESMALTEC.ESM_RH_ATEND_SOCIAL WHERE COD_ATEND_SOCIAL= ".$COD_ATEND_SOCIAL."")->result_array();		
		
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
										            AND F.NUM_MATRICULA = trim('".$NUM_MATRICULA[0]['NUM_MATRICULA']."')")->result_array();
		return $funcio;
	}
	
	
	function getAfastamentos($COD_ATEND_SOCIAL = "") {
		$DB3 = $this -> load -> database('logix', TRUE);

		$afastamentos = $DB3->query("SELECT F.NUM_MATRICULA,
										   F.NOM_COMPLETO,
										   EA.COD_AFASTAMENTO,
										   EA.NUM_BENEFICIO,
										   EA.OBSERVACOES,
										   ASO.COD_ATEND_SOCIAL
									  FROM ESMALTEC.ESM_RH_AFASTAMENTO EA,
										   ESMALTEC.ESM_RH_ATEND_SOCIAL ASO,
										   LOGIX.FUNCIONARIO F
									 WHERE ASO.COD_ATEND_SOCIAL = EA.COD_ATEND_SOCIAL 
									   AND ASO.NUM_MATRICULA = F.NUM_MATRICULA
										AND ASO.COD_ATEND_SOCIAL = " . $COD_ATEND_SOCIAL . " ")->result_array();
		return $afastamentos;
	}
	
	function getPericias($COD_AFASTAMENTO = "") {
		$DB3 = $this->load->database('logix', TRUE);

		$pericias = $DB3->query("SELECT EP.COD_AFASTAMENTO, EP.COD_PERICIA, EP.DATA_CONSECAO_INDEFERIMENTO,
									    EP.DATA_PERICIA, EP.DECISAO, EP.NUM_PERICIA, EP.OBSERVACAO
								   FROM ESM_RH_PERICIA EP
								  WHERE EP.COD_AFASTAMENTO = ".$COD_AFASTAMENTO." order by NUM_PERICIA asc")->result_array();
		return $pericias;
	}

	function incluir($parametros) {

		$DB3 = $this->load->database('logix', TRUE);

		$DB3->trans_start();

		$DB3->query("INSERT INTO ESM_RH_AFASTAMENTO(COD_AFASTAMENTO, COD_ATEND_SOCIAL, NUM_BENEFICIO, OBSERVACOES, DATA_ATEND, ATENDENTE) 
					 VALUES(ESM_RH_AFASTAMENTO_SEQ.NEXTVAL, '". $parametros['COD_ATEND_SOCIAL'] . "','" . $parametros['NUM_BENEFICIO'] . "','" . utf8_decode($parametros['OBSERVACOES']) . "',SYSDATE, ".$this->session->userdata('idRegister').")");
		//print_r($sql);
		//die();
		
		$COD_AFASTAMENTO = $DB3->query("SELECT MAX(COD_AFASTAMENTO) COD_AFASTAMENTO FROM ESM_RH_AFASTAMENTO")->result_array();
		$this->incluirPericia($COD_AFASTAMENTO[0]["COD_AFASTAMENTO"], $parametros);
		
		$DB3 -> trans_complete();
		if ($DB3 -> trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}
	
	function incluirPericia($COD_AFASTAMENTO, $parametros) {

		$DB3 = $this->load->database('logix', TRUE);

		$DB3->trans_start();
		
		for($i=0;$i<sizeof($parametros['NUM_PERICIA']);$i++){
		
		$DB3->query("INSERT INTO ESM_RH_PERICIA(COD_PERICIA, COD_AFASTAMENTO, NUM_PERICIA, DATA_PERICIA, DATA_CONSECAO_INDEFERIMENTO, OBSERVACAO, DECISAO) 
					 VALUES(ESM_RH_PERICIA_SEQ.NEXTVAL, '". $COD_AFASTAMENTO . "','" . $parametros['NUM_PERICIA'][$i] . "','" . $parametros['DATA_PERICIA'][$i] . "', '" . $parametros['DATA_CONSECAO_INDEFERIMENTO'][$i]."' , '" . utf8_decode($parametros['OBSERVACAO'][$i])."', '" . $parametros['DECISAO'][$i]."')");
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
		$DB3 = $this -> load -> database('logix', TRUE);

		$DB3 -> trans_start();

		$DB3 -> query("UPDATE ESM_RH_AFASTAMENTO 
						SET NUM_BENEFICIO = '" . $parametros['NUM_BENEFICIO'] . "', 
						OBSERVACOES = '" . utf8_decode($parametros['OBSERVACOES']) . "',
						ATENDENTE = ".$this->session->userdata('idRegister')."
						WHERE COD_AFASTAMENTO = '" . $parametros['COD_AFASTAMENTO'] . "'");

			$this->alterarPericia($parametros);
		$DB3 -> trans_complete();
		if ($DB3 -> trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}
	
	function alterarPericia($parametros) {
		$DB3 = $this -> load -> database('logix', TRUE);

		$DB3->trans_start();
		$codigos = "";

		for($i=0;$i<sizeof($parametros['NUM_PERICIA']);$i++){
			if($parametros['COD_PERICIA'][$i] != ""){
				$DB3->query("UPDATE ESM_RH_PERICIA SET NUM_PERICIA = '".$parametros['NUM_PERICIA'][$i]."', DATA_PERICIA = '".$parametros['DATA_PERICIA'][$i]."',  DATA_CONSECAO_INDEFERIMENTO = '".$parametros['DATA_CONSECAO_INDEFERIMENTO'][$i]."', OBSERVACAO = '" . utf8_decode($parametros['OBSERVACAO'][$i])."', DECISAO = '" . $parametros['DECISAO'][$i]."' where COD_PERICIA = ". $parametros['COD_PERICIA'][$i]." and COD_AFASTAMENTO = '".$parametros['COD_AFASTAMENTO']."' ");
								
				$codigos .= $parametros['COD_PERICIA'][$i].',';
			}
		}
		
		$sql = "DELETE FROM ESM_RH_PERICIA WHERE COD_AFASTAMENTO = ".$parametros['COD_AFASTAMENTO']." AND COD_PERICIA NOT IN (".substr($codigos,0,-1).")";
		$DB3->query($sql);
		
		for($j=0;$j<sizeof($parametros['NUM_PERICIA']);$j++){
			if($parametros['COD_PERICIA'][$j] == ""){
				$DB3->query("INSERT INTO ESM_RH_PERICIA(COD_PERICIA, COD_AFASTAMENTO, NUM_PERICIA, DATA_PERICIA, DATA_CONSECAO_INDEFERIMENTO, OBSERVACAO, DECISAO) 
					 VALUES(ESM_RH_PERICIA_SEQ.NEXTVAL, '". $parametros['COD_AFASTAMENTO'] . "','" . $parametros['NUM_PERICIA'][$j] . "','" . $parametros['DATA_PERICIA'][$j] . "', '" . $parametros['DATA_CONSECAO_INDEFERIMENTO'][$j]."' , '" . utf8_decode($parametros['OBSERVACAO'][$j])."', '" . $parametros['DECISAO'][$j]."')");
			}
		}

		$DB3 -> trans_complete();
		if ($DB3 -> trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}

}
