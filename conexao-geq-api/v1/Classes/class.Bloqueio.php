<?php

class Bloqueio {	
	private $db = NULL;
	private $isBloqueado = false;

	function __construct() {
		$this->db = new Oracle();		
	}

	function verificaBloqueio() {
		return $this->isBloqueado;
	}

	function adiciona($matricula) {

			if (!is_numeric($matricula))
				return false;

			$SQL = "SELECT MATRICULA, CONTADOR FROM RH_FUNCIONARIO where MATRICULA = '".$matricula."'"; 

			$statement = $this->db->executeQuery($SQL);

			$count = -1;

			while ($row = oci_fetch_array($statement, OCI_BOTH)) {
	 			$count = $row['CONTADOR'];
	 		}	   						

			if ($count <= -1) {
				$this->iniciaMonitoramentoBloqueio($matricula);	            
	        } else if ($count >= 0 && $count <= 5) {
				$this->updateMonitoramentoBloqueio($matricula);
	        } else if ($count > 5) {	
	        	$this->isBloqueado = true;
	        	return false;
	        } 

	       return true;

	}

	function iniciaMonitoramentoBloqueio($matricula) {
 
		$SQL = "INSERT INTO RH_FUNCIONARIO (ID,
                            MATRICULA,
                            CONTADOR,
                            ULTIMO_ACESSO)
			   SELECT RH_FUNCIONARIO_SEQ.NEXTVAL,
			          '".$matricula."',
			          1,
			          SYSDATE
			     FROM DUAL
			    WHERE NOT EXISTS
			             (SELECT *
			                FROM RH_FUNCIONARIO
			               WHERE (MATRICULA = '".$matricula."'))";

		$id_result = $this->db->executeUpdate($SQL);	

	}

	function updateMonitoramentoBloqueio($matricula) {

		$SQL = "UPDATE RH_FUNCIONARIO SET CONTADOR = CONTADOR + 1, ULTIMO_ACESSO = SYSDATE WHERE MATRICULA = '".$matricula."'";

		$id_result = $this->db->executeUpdate($SQL);	

	}	

	function atualizaMonitoramentoBloqueio($matricula) {

		$SQL = "UPDATE RH_FUNCIONARIO SET ULTIMO_BLOQUEIO = SYSDATE WHERE MATRICULA = '".$matricula."'";

		$id_result = $this->db->executeUpdate($SQL);	

	}	

}

?>