<?php
class Importfaltas_Model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this -> load -> database();
		$this->load->library('Spreadsheet_Excel_Reader');

	}

	function importar($files, $parametros){
		
		$DB3 = $this->load->database('logix',TRUE);
		
		$DB3->trans_start();
		
		$data = new Spreadsheet_Excel_Reader($_FILES['arquivo']['tmp_name']);
		 
		for( $i=2; $i <= $data->rowcount($sheet_index=0); $i++ ){
			$dados = explode(" ", $data->val($i, 7));
			//print_r($dados[0].$dados[2]);
			if($dados[0] == "FALTAS" && $dados[2] == "JUSTIFICADA"){
			//print_r(utf8_decode($data->val($i, 7)));
			$dat = explode( "/", $data->val($i, 3));
			$d = $dat[1].'/'.$dat[0].'/'.$dat[2];       
				$sqlSel = "select * from ESM_RH_FALTAS where NUM_MATRICULA = ".$data->val($i, 1)." and DAT_FALTA = TO_DATE('".$d."','DD/MM/YY') and PERIODO = '".$parametros['PERIODO']."'";
				
				$num = $DB3->query($sqlSel)->num_rows();
		
				if($num == 0){
					$sql = "INSERT INTO ESM_RH_FALTAS VALUES(".$data->val($i, 1).",TO_DATE('".$d."','DD/MM/YY'),'".$parametros['PERIODO']."')";
					//print_r($sql);
					$DB3->query($sql);
					//print_r('entrei');
					//die();
				}
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
