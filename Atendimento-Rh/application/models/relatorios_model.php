<?php
class Relatorios_Model extends CI_Model  {
    
	function __construct()
    {
        parent::__construct();
		$this->load->database();
		
    }
	
	function atendimento($parametros){
		
		$DB2 = $this->load->database('funcio', TRUE);
		$sql = "SELECT F.NUM_MATRICULA MATRICULA, 
					   F.NOM_COMPLETO FUNCIONARIO, 
					   UF.DEN_UNI_FUNCIO SETOR, 
					   C.DEN_CARGO CARGO, 
					   ERA.DATA_INI_ATENDIMENTO DATA_INICIO, 
					   ERTA.DEN_TIPO_ATENDIMENTO TIPO,
					   ERA.DEN_ATENDIMENTO ASSUNTO, 
					   ERA.OBSERVACAO_ATENDIMENTO OBSERVACOES, 
					   CASE ERA.STATUS_ATENDIMENTO 
					   WHEN 1 THEN 'EM ANDAMENTO' 
					   WHEN 2 THEN 'CONCLUÍDO' 
					   WHEN 3 THEN 'CANCELADO' 
					   END STATUS , 
					   ERA.DATA_FIM_ATENDIMENTO DATA_FINAL, 
					   FUN.NOM_COMPLETO ATENDENTE   
				FROM ESMALTEC.ESM_RH_ATENDIMENTO ERA, 
					LOGIX.FUNCIONARIO F,
					LOGIX.FUNCIONARIO FUN, 
					ESMALTEC.ESM_RH_TIPO_ATENDIMENTO ERTA, 
					LOGIX.UNIDADE_FUNCIONAL UF, 
					LOGIX.CARGO C
				WHERE ERA.NUM_MATRICULA = F.NUM_MATRICULA 
				AND ERTA.COD_TIPO_ATENDIMENTO = ERA.TIPO_ATENDIMENTO
				AND F.COD_UNI_FUNCIO = UF.COD_UNI_FUNCIO
				AND F.COD_EMPRESA = UF.COD_EMPRESA
				AND F.COD_CARGO = C.COD_CARGO
				AND F.COD_EMPRESA = C.COD_EMPRESA
				AND FUN.NUM_MATRICULA = ERA.ATENDENTE
				AND UF.DAT_VALIDADE_FIM > SYSDATE
				AND C.DAT_VALIDADE_FIM > SYSDATE
				AND F.DAT_DEMIS IS NULL 				
				AND ERA.NIVEL_AREA = ".$this->session->userdata('idLevel')."
				AND TO_CHAR(ERA.DATA_INI_ATENDIMENTO,'DD/MM/YYYY') BETWEEN TO_DATE('".$parametros['DAT_INI']."','DD/MM/YYYY') AND TO_DATE('".$parametros['DAT_FIM']."','DD/MM/YYYY')
				ORDER BY TO_DATE(TO_CHAR(ERA.DATA_INI_ATENDIMENTO,'DD/MM/YYYY HH24:MI:SS'),'DD/MM/YYYY HH24:MI:SS') DESC";
		
		$atendimentos = $DB2->query($sql)->result_array();
		return $atendimentos;
	}
	
	function getGerencia(){
		$DB3 = $this->load->database('funcio', TRUE);
		$sql = "SELECT DISTINCT case den_estr_linprod when 'TODOS' then '' else den_estr_linprod end as den_estr_linprod
                   FROM logix.linha_prod lp, unidade_funcional uf
                  WHERE (   TRIM (TO_CHAR (lp.cod_lin_prod, '09'))
                         || TRIM (TO_CHAR (lp.cod_lin_recei, '09'))
                         || TRIM (TO_CHAR (lp.cod_seg_merc, '09'))
                         || TRIM (TO_CHAR (lp.cod_cla_uso, '09'))
                        ) = TRIM (uf.cod_area_linha)
                  and uf.DAT_VALIDADE_FIM > sysdate
                  order by den_estr_linprod asc";
		$gerencia = $DB3->query($sql)->result_array();
		return $gerencia;
	}
	
}