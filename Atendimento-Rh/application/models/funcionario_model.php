<?php
class Funcionario_Model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->database();

	}

	function getFuncionario($posts) {
		$DB2 = $this->load->database('funcio', TRUE);
		
		$filtro ="";
		if(trim($posts['NUM_MATRICULA']) !=""){
			$filtro .= "AND F.NUM_MATRICULA = TRIM(UPPER(REPLACE ('".trim($posts['NUM_MATRICULA'])."', '')))";
		}
		if(trim($posts['NOME']) != ""){
			$filtro .= "AND F.NOM_FUNCIONARIO LIKE TRIM(UPPER(REPLACE ('%".trim($posts['NOME'])."%','')))";
		}
		
		$funcio = $DB2->query("SELECT DISTINCT F.NUM_MATRICULA, F.NOM_COMPLETO, UF.DEN_UNI_FUNCIO,
													                C.DEN_CARGO
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
																AND UF.DAT_VALIDADE_FIM > SYSDATE
																AND C.DAT_VALIDADE_FIM > SYSDATE
													            AND C.COD_EMPRESA = F.COD_EMPRESA
													            AND C.COD_CARGO = F.COD_CARGO
													            AND CI.COD_CIDADE = FI.COD_CIDADE
													            AND BA.COD_BAIRRO = FI.COD_BAIRRO
													            AND BA.COD_CIDADE = CI.COD_CIDADE
													            AND F.DAT_DEMIS IS NULL ".$filtro)->result_array();
			return $funcio;
										      
	}
	
	function getTela($tela){
		return $tela;
	}

}
