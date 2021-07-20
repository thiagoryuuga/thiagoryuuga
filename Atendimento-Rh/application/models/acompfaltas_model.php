<?php
class Acompfaltas_Model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this -> load -> database();
		$this->load->library('email');

	}

	function getFuncionario($periodo = "" ,$matricula = "") {
		$DB2 = $this->load->database('funcio', TRUE);
		
		$periodo = str_replace('-','/',$periodo);
				
		$sql = 
										"SELECT DISTINCT F.NUM_MATRICULA, 
                                        F.NOM_COMPLETO, 
                                        UF.DEN_UNI_FUNCIO,
                                        TO_CHAR(F.DAT_ADMIS,'DD/MM/YYYY') DAT_ADMIS, 
                                        CI.DEN_CIDADE,
                                        FI.NUM_TELEF_RES,
                                        FLOOR(FLOOR(MONTHS_BETWEEN(SYSDATE, FI.DAT_NASCIMENTO)) / 12) IDADE,
                                        upper((SELECT LOGIX.DIF_DATA_POR_EXTENSO(F.DAT_ADMIS,SYSDATE) FROM DUAL)) TEMPO_EMPRESA,
										 upper((SELECT LOGIX.DIF_DATA_POR_EXTENSO((select max(dat_alt_cargo) from LOGIX.FICHA_CARGOs LFC where lfc.num_matricula = f.num_matricula),SYSDATE) FROM DUAL)) TEMPO_FUNCAO,
  
										
                                        CASE FI.IES_EST_CIVIL WHEN 'S' THEN 'SOLTEIRO'
                                        WHEN 'C' THEN 'CASADO' 
                                        WHEN 'D' THEN 'DIVORCIADO' 
                                        WHEN 'V' THEN 'VIÚVO' 
                                        WHEN 'M' THEN 'MARITAL'
                                        WHEN 'P' THEN 'SEPARAÇÃO'
                                        WHEN 'U' THEN 'UNIÃO ESTÁVEL'END AS IES_EST_CIVIL,
                                       DECODE((SELECT COUNT(*) FROM LOGIX.FUN_DEFICIENCIA FD WHERE FD.NUM_MATRICULA = F.NUM_MATRICULA),0,'N&Atilde;O',1,'SIM') AS PNE,
                                        (SELECT LOGIX.SITUACAOFUNCIONARIO(F.NUM_MATRICULA) FROM DUAL) AS STATUS,
                                        (SELECT LOGIX.FC_CARGO_INICIAL_FINAL (F.NUM_MATRICULA, 'I', F.COD_EMPRESA ) FROM DUAL) AS CARGO_INICIAL,
                                        (SELECT LOGIX.FC_CARGO_INICIAL_FINAL (F.NUM_MATRICULA, 'F',F.COD_EMPRESA ) FROM DUAL) AS CARGO_ATUAL,
                                        lp.DEN_ESTR_LINPROD,
                                        COUNT(EF.NUM_MATRICULA) AS QTD,
                                       RHU_GRAU_INSTR.DES_GRAU_INSTR 
                                        FROM FUNCIONARIO F
                                        INNER JOIN FUN_INFOR FI ON  F.COD_EMPRESA = FI.COD_EMPRESA AND F.NUM_MATRICULA = FI.NUM_MATRICULA
                                        INNER JOIN RHU_GRAU_INSTR ON RHU_GRAU_INSTR.GRAU_INSTR = FI.COD_GRAU_INSTR 
                                        INNER JOIN ESMALTEC.ESM_RH_FALTAS EF ON EF.NUM_MATRICULA = F.NUM_MATRICULA AND EF.PERIODO = '".utf8_decode($periodo)."'
                                        INNER JOIN UNIDADE_FUNCIONAL UF ON UF.COD_EMPRESA = F.COD_EMPRESA AND UF.COD_UNI_FUNCIO = F.COD_UNI_FUNCIO
                                        INNER JOIN CIDADES CI ON  CI.COD_CIDADE = FI.COD_CIDADE
                                        INNER JOIN LOGIX.LINHA_PROD LP ON (TRIM (TO_CHAR (LP.COD_LIN_PROD, '09'))|| 
                                                                           TRIM (TO_CHAR (LP.COD_LIN_RECEI, '09'))|| 
                                                                           TRIM (TO_CHAR (LP.COD_SEG_MERC, '09'))|| 
                                                                           TRIM (TO_CHAR (LP.COD_CLA_USO, '09'))) = TRIM(UF.COD_AREA_LINHA)
                                        AND UF.DAT_VALIDADE_FIM >= SYSDATE
                                        
                                         AND F.NUM_MATRICULA = '".trim($matricula)."'
                                        GROUP BY F.NUM_MATRICULA,F.NOM_COMPLETO, UF.DEN_UNI_FUNCIO,TO_CHAR(F.DAT_ADMIS,'DD/MM/YYYY'),
                                        CI.DEN_CIDADE,FI.NUM_TELEF_RES,FLOOR(FLOOR(MONTHS_BETWEEN(SYSDATE, FI.DAT_NASCIMENTO)) / 12),
                                        FI.IES_EST_CIVIL, F.COD_EMPRESA,lp.DEN_ESTR_LINPROD,F.DAT_ADMIS,RHU_GRAU_INSTR.DES_GRAU_INSTR";
		// print_r($sql);
		//die();
		$funcio = $DB2->query($sql)->result_array();       
		
		return $funcio;
	}
	
	function getFaltas($periodo = "", $matricula = ""){
		$DB3 = $this->load->database('logix', TRUE);
		
		$faltas = $DB3->query("select NUM_MATRICULA, TO_CHAR(DAT_FALTA,'DD/MM/YYYY') as DAT_FALTA, PERIODO from ESM_RH_FALTAS where PERIODO = '".str_replace('-','/',$periodo)."' and NUM_MATRICULA = '".$matricula."' ORDER BY ESM_RH_FALTAS.DAT_FALTA ASC");
		return $faltas;

	}
	
	function getAcompFaltas($COD_ACOMP_FALTAS = "", $NUM_MATRICULA = "") {
		$DB3 = $this->load->database('logix', TRUE);

		$filtro = "";
		
		if($this->session->userdata('idLevel') == 5){
			$filtro .= " AND MATRICULA_SUPERVISOR = ".$this->session->userdata('idRegister')."";
		}else if($this->session->userdata('idLevel') == 4){
			$filtro .= " AND MATRICULA_ATEND = ".$this->session->userdata('idRegister')."";
		}
		else if($this->session->userdata('idLevel') == 3){
			$filtro .= " AND MATRICULA_ATEND = ".$this->session->userdata('idRegister')."";
		}
		
		if ($COD_ACOMP_FALTAS != "") {
			$filtro .= " AND COD_ACOMP_FALTAS = " . $COD_ACOMP_FALTAS . " ";
		}
		if ($NUM_MATRICULA != "") {
			$filtro .= " AND AF.NUM_MATRICULA = " . $NUM_MATRICULA . " ";
		}
		

		$acompFaltas = $DB3->query("SELECT AF.COD_ACOMP_FALTAS,
									   F.NUM_MATRICULA,
									   F.NOM_COMPLETO,
         							   AF.MATRICULA_SUPERVISOR,
									   AF.SUPERVISOR,
									   AF.MATRICULA_LIDER,
									   AF.LIDER,
									   AF.QTD_FALTAS,
									   AF.DESLIGAMENTO,
									   AF.MOTIVO,
									   AF.JUSTIFICATIVA,
									   AF.TRANSPORTE_EMPRESA,
									   AF.TRANSPORTE_CASA, 
									   AF.TEMPO_TRABALHO,
									   AF.TEMPO_CASA,
									   AF.PORQ_INSATISFACAO,
									   AF.COMENTARIOS,
									   AF.FEEDBACK_SUPERVISOR,
									   AF.PERIODO,
									   AF.DATA_ACOMP,      
									   AF.RENDA,
									   AF.OUTRA_RENDA,
									   AF.NOME_OUTRA_RENDA,
									   AF.VALOR_OUTRA_REMUNERADA,
									   AF.SUSTENTO_AGREGADO,
									   AF.TRATAMENTO_RESPEITO,
									   AF.LUGAR_TRABALHO,
									   AF.PERCEBE_QUALIDADE,
									   AF.COMENTARIO_QUALIDADE,
									   AF.OPINIOES,
									   AF.QUALIDADE,
									   AF.ORIENTACAO,
									   AF.OPORTUNIDADE,
									   AF.REALIZACAO,
									   AF.COMENTARIO_REALIZACAO,
									   AF.SUCESSO,
									   AF.OUTRO_SETOR,
									   AF.COMENTARIO_OUTRO_SETOR,
									   AF.SALARIO,
									   AF.BENEFICIOS,
									   AF.INDICACAO,
									   AF.COMENTARIO_AMIGO,
									   AF.EXEMPLO,
									   AF.IDEIA_BENEFICIO,
									   AF.GOSTA,
									   AF.FAMILIA_EMPRESA,
									   AF.EQUIPAMENTOS,
									   AF.PROTEGIDO,
									   AF.CHEFE_INFORMA,
									   AF.FUTURO,
									   AF.FONTE_INFORMACOES,
									   AF.PROBLEMAS_TRABALHO,
									   AF.DESCONTOS,
									   AF.EMPRESA_MELHORAR,
									   AF.AVALIACAO_TRABALHO,
									   AF.RAZAO_PRINCIPAL,
									   AF.RAZAO_SECUNDARIO,
									   AF.EMPRESA_ANTES,
									   AF.COMENTARIO_EMPRESA_ANTES,
									   AF.ILUMINACAO_VENTILACAO,
									   AF.TEMPERATURA,
									   AF.ESPACO_FISICO,
									   AF.HIGIENE_LIMPEZA,
									   AF.BANHEIROS_VESTIARIOS,
									   AF.ASSISTENCIA_MEDICA,
									   AF.ASSISTENCIA_ODONTOLOGICA,
									   AF.CONVENIOS,
									   AF.ATENDIMENTO_RH,
									   
									   AF.EMPRESTIMOS,
									   AF.LOJINHA,
									   AF.MEDICINA_AMBULATORIO,
									   AF.POSTO_BANCARIO,
									   AF.QUALIDADE_VIDA,
									   AF.RECRUTAMENTO_SELECAO,
									   AF.REFEITORIO,
									   AF.TRANSPORTE,
									   AF.COMENTARIO_REALIZACAO,
									   AF.PORQ_INSATISFACAO,
									   AF.COMENTARIO_OPINIAO_FUNCIONARIO,
									   AF.MATRICULA_ATEND,
									   AF.COMENTARIO_INSATISFACAO,
									   AF.COMENTARIO_BENEFICIO,
									   AF.BENEFICIO_SALARIO
									   FROM ESMALTEC.ESM_RH_ACOMP_FALTAS AF,
									   LOGIX.FUNCIONARIO F
									   WHERE AF.NUM_MATRICULA = F.NUM_MATRICULA
									    " . $filtro." AND ROWNUM < 100")->result_array();

		return $acompFaltas;
	}
	
	
	function verificFaltas($NUM_MATRICULA = "", $PERIODO = "") {
		$DB3 = $this->load->database('logix', TRUE);

		$filtro = "";
		
		if($this->session->userdata('idLevel') == 5){
			$filtro .= " AND MATRICULA_SUPERVISOR = ".$this->session->userdata('idRegister')."";
		}else if($this->session->userdata('idLevel') == 4){
			$filtro .= " AND MATRICULA_ATEND = ".$this->session->userdata('idRegister')."";
		}
		else if($this->session->userdata('idLevel') == 2){
			$filtro .= " AND MATRICULA_ATEND = ".$this->session->userdata('idRegister')."";
		}
		
		if ($NUM_MATRICULA != "") {
			$filtro .= " AND AF.NUM_MATRICULA = " . $NUM_MATRICULA . " ";
		}
		if ($PERIODO != "") {
			$per = explode('-',$PERIODO);
			$PERIODO = $per[0].'/'.$per[1];
			$filtro .= " AND AF.PERIODO = '" . $PERIODO . "' ";
		}
		

		$verificFaltas = $DB3->query("select AF.COD_ACOMP_FALTAS,
											   F.NUM_MATRICULA,
											   F.NOM_COMPLETO, 
											   AF.MATRICULA_SUPERVISOR,
											   AF.SUPERVISOR, 
											   AF.MATRICULA_LIDER,
											   AF.LIDER, 
											   AF.QTD_FALTAS, 
											   AF.DESLIGAMENTO,
											   AF.MOTIVO, 
											   AF.JUSTIFICATIVA, 
											   AF.SUGESTOES, 
											   AF.PERIODO, 
											   AF.DATA_ACOMP, 
											   AF.MATRICULA_ATEND,
											   AF.FEEDBACK_SUPERVISOR,
											   AF.INSAT_CHEFIA,
											   AF.PORQ_INSATISFACAO,
											   AF.BENEFICIO_SALARIO											   
										from ESMALTEC.ESM_RH_ACOMP_FALTAS AF,
											 LOGIX.FUNCIONARIO F
										WHERE AF.NUM_MATRICULA = F.NUM_MATRICULA
									    " . $filtro." AND ROWNUM < 100");

		return $verificFaltas;
	}

	function incluir($parametros) {
		
		$DB3 = $this->load->database('logix', TRUE);

		$DB3->trans_start();
					
		$supervisor  = explode(' - ', $parametros['SUPERVISOR']);
		$lider  = explode(' - ', $parametros['LIDER']);
		
		if(isset($parametros['data_cadastro']))
		{
			$data = $parametros['data_cadastro']." 10:00:00";
		}
		else
		{
			$data = 'sysdate';
		}
		
		
								   
								   $DB3->query("insert into ESM_RH_ACOMP_FALTAS
                                  (COD_ACOMP_FALTAS,
                                   NUM_MATRICULA,
                                   MATRICULA_SUPERVISOR,
                                   SUPERVISOR, 
                                   MATRICULA_LIDER,
                                   LIDER, 
                                   QTD_FALTAS,
                                   DESLIGAMENTO, 
                                   MOTIVO, 
                                   JUSTIFICATIVA,
								   TRANSPORTE_EMPRESA,
								   TRANSPORTE_CASA, 
                                   TEMPO_TRABALHO,
								   TEMPO_CASA,
                                   FEEDBACK_SUPERVISOR, 
                                   PERIODO, 
                                   DATA_ACOMP,
                                   COMENTARIOS,
                                   PORQ_INSATISFACAO, 
                                   RENDA,
                                   VALOR_OUTRA_REMUNERADA,
                                   SUSTENTO_AGREGADO,
                                    TRATAMENTO_RESPEITO,
                                    LUGAR_TRABALHO,
                                    PERCEBE_QUALIDADE,
                                    OPINIOES,
                                    QUALIDADE,
                                    ORIENTACAO,
                                    OPORTUNIDADE,
                                    REALIZACAO,
                                    SUCESSO,
                                    OUTRO_SETOR,
                                    SALARIO,
                                    BENEFICIOS,
                                    INDICACAO,
                                    EXEMPLO,
                                    IDEIA_BENEFICIO,
                                    GOSTA,
                                    FAMILIA_EMPRESA,
                                    EQUIPAMENTOS,
                                    PROTEGIDO,
                                    CHEFE_INFORMA,
                                    FUTURO,
                                    FONTE_INFORMACOES,
                                    PROBLEMAS_TRABALHO,
                                    DESCONTOS,
                                    EMPRESA_MELHORAR,
                                    AVALIACAO_TRABALHO,
                                    RAZAO_PRINCIPAL,
                                    RAZAO_SECUNDARIO,
                                    EMPRESA_ANTES,
                                    COMENTARIO_EMPRESA_ANTES,
                                    ILUMINACAO_VENTILACAO,
                                    TEMPERATURA,
                                    ESPACO_FISICO,
                                    HIGIENE_LIMPEZA,
                                    BANHEIROS_VESTIARIOS,
                                    COMENTARIO_INSATISFACAO,
                                    ASSISTENCIA_MEDICA,
                                    ASSISTENCIA_ODONTOLOGICA,
                                    ATENDIMENTO_RH,
                                    CONVENIOS,
									EMPRESTIMOS,
                                    LOJINHA,
                                    MEDICINA_AMBULATORIO,
                                    POSTO_BANCARIO,
                                    QUALIDADE_VIDA,
									RECRUTAMENTO_SELECAO,
                                    REFEITORIO,
                                    TRANSPORTE,
									NOME_OUTRA_RENDA,
									COMENTARIO_OPINIAO_FUNCIONARIO,
									COMENTARIO_QUALIDADE,
									COMENTARIO_REALIZACAO,
									COMENTARIO_OUTRO_SETOR,
									COMENTARIO_AMIGO,
									COMENTARIO_BENEFICIO,
									BENEFICIO_SALARIO,
                                    MATRICULA_ATEND )
                                    values(
									ESM_RH_ACOMP_FALTAS_SEQ.nextval, 
                                   '".$parametros['NUM_MATRICULA']."',
                                   '".$supervisor[1]."', 
                                   '".utf8_decode($supervisor[0])."',
                                   '".$lider[1]."',
                                   '".utf8_decode($lider[0])."',
                                   ".$parametros['QTD_FALTAS'].",
                                   '".$parametros['DESLIGAMENTO']."',
                                   '".utf8_decode($parametros['MOTIVO'])."',
                                   '".utf8_decode($parametros['JUSTIFICATIVA'])."',
								   '".utf8_decode($parametros['TRANSPORTE_EMPRESA'])."',
								   '".utf8_decode($parametros['TRANSPORTE_CASA'])."',
                                   '".utf8_decode($parametros['TEMPO_TRABALHO'])."',
								   '".utf8_decode($parametros['TEMPO_CASA'])."',
                                   '".utf8_decode($parametros['FEEDBACK_SUPERVISOR'])."',
                                   '".utf8_decode($parametros['PERIODO'])."','".
								   $data."',
                                   '".utf8_decode($parametros['COMENTARIOS'])."',
								   '".utf8_decode($parametros['PORQ_INSATISFACAO'])."',
                                   '".$parametros['RENDA']."',
                                   '".$parametros['OUTRA_RENDA']."',
                                   '".$parametros['SUSTENTO_AGREGADO']."',
                                   '".$parametros['TRATAMENTO_RESPEITO']."',
                                   '".$parametros['LUGAR_TRABALHO']."',
                                   '".$parametros['PERCEBE_QUALIDADE']."',
                                   '".$parametros['OPINIOES']."',
                                   '".$parametros['QUALIDADE']."',
                                   '".$parametros['ORIENTACAO']."',
                                   '".$parametros['OPORTUNIDADE']."',
                                   '".$parametros['REALIZACAO']."',
                                   '".$parametros['SUCESSO']."',
                                   '".$parametros['OUTRO_SETOR']."',
                                   '".$parametros['SALARIO']."',
                                   '".$parametros['BENEFICIOS']."',
                                   '".$parametros['INDICACAO']."',
                                   '".$parametros['EXEMPLO']."',
                                   '".$parametros['IDEIA_BENEFICIO']."',
                                   '".$parametros['GOSTA']."',
                                   '".$parametros['FAMILIA_EMPRESA']."',
                                   '".$parametros['EQUIPAMENTOS']."',
                                   '".$parametros['PROTEGIDO']."',
                                   '".$parametros['CHEFE_INFORMA']."',
                                   '".$parametros['FUTURO']."',
                                   '".$parametros['FONTE_INFORMACOES']."',
                                   '".$parametros['PROBLEMAS_TRABALHO']."',
                                   '".$parametros['DESCONTOS']."',
                                   '".$parametros['EMPRESA_MELHORAR']."',
                                   '".$parametros['AVALIACAO_TRABALHO']."',
                                   '".$parametros['RAZAO_PRINCIPAL']."',
                                   '".$parametros['RAZAO_SECUNDARIO']."',
                                   '".$parametros['EMPRESA_ANTES']."',
                                   '".$parametros['COMENTARIO_EMPRESA_ANTES']."',
                                   '".$parametros['ILUMINACAO_VENTILACAO']."',
                                   '".$parametros['TEMPERATURA']."',
                                   '".$parametros['ESPACO_FISICO']."',
                                   '".$parametros['HIGIENE_LIMPEZA']."',
                                   '".$parametros['BANHEIROS_VESTIARIOS']."',
                                   '".$parametros['COMENTARIO_INSATISFACAO']."',
                                   '".$parametros['ASSISTENCIA_MEDICA']."',
                                   '".$parametros['ASSISTENCIA_ODONTOLOGICA']."',
								   '".$parametros['ATENDIMENTO_RH']."',
                                   '".$parametros['CONVENIOS']."',
								   '".$parametros['EMPRESTIMOS']."',
                                   '".$parametros['LOJINHA']."',
                                   '".$parametros['MEDICINA_AMBULATORIO']."',
                                   '".$parametros['POSTO_BANCARIO']."',
                                   '".$parametros['QUALIDADE_VIDA']."',
                                   '".$parametros['RECRUTAMENTO_SELECAO']."',
                                   '".$parametros['REFEITORIO']."',
                                   '".$parametros['TRANSPORTE']."',
								   '".$parametros['NOME_OUTRA_RENDA']."',
								   '".$parametros['COMENTARIO_OPINIAO_FUNCIONARIO']."',
								   '".$parametros['COMENTARIO_QUALIDADE']."',
								   '".$parametros['COMENTARIO_REALIZACAO']."',
								   '".$parametros['COMENTARIO_OUTRO_SETOR']."',
								   '".$parametros['COMENTARIO_AMIGO']."',
								   '".$parametros['COMENTARIO_BENEFICIO']."',
								   '".$parametros['BENEFICIO_SALARIO']."',
								   '".$this->session->userdata('idRegister')."')");
									
			
		$this->permissao($supervisor[1]);
		
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

		$supervisor  = explode(' - ', $parametros['SUPERVISOR']);
		$lider  = explode(' - ', $parametros['LIDER']);
		
		
						/*$DB3->query("UPDATE ESM_RH_ACOMP_FALTAS SET			
			                         MATRICULA_LIDER='".$lider[1]."',
	                                 LIDER='".utf8_decode($lider[0])."',
	                                 QTD_FALTAS=".$parametros['QTD_FALTAS'].",
	                                 DESLIGAMENTO='".$parametros['DESLIGAMENTO']."',
									 MOTIVO='".utf8_decode($parametros['MOTIVO'])."',
	                                 JUSTIFICATIVA='".utf8_decode($parametros['JUSTIFICATIVA'])."',
	                                 SUGESTOES='".utf8_decode($parametros['SUGESTOES'])."',
	                                 FEEDBACK_SUPERVISOR='".utf8_decode($parametros['FEEDBACK_SUPERVISOR'])."',
	                                 PERIODO='".utf8_decode($parametros['PERIODO'])."',
	                                 DATA_ACOMP=sysdate,
	                                 INSAT_CHEFIA='".$parametros['INSAT_CHEFIA']."',
	                                 PORQ_INSATISFACAO='".utf8_decode($parametros['PORQ_INSATISFACAO'])."',
	                                 COMENTARIOS='".$parametros['COMENTARIOS']."',
	                                 RENDA='".$parametros['RENDA']."',
	                                 OUTRA_RENDA='".$parametros['OUTRA_RENDA']."',
	                                 VALOR_OUTRA_REMUNERADA='".$parametros['VALOR_OUTRA_REMUNERADA']."',
	                                 POSSUI_OUTRA_RENDA='".$parametros['POSSUI_OUTRA_RENDA']."',
	                                    RENDA_TOTAL='".$parametros['RENDA_TOTAL']."',
	                                    SUSTENTO_AGREGADO='".$parametros['SUSTENTO_AGREGADO']."',
	                                    TRANSPORTE_EMPRESA='".$parametros['TRANSPORTE_EMPRESA']."',
	                                    TRANSPORTE_CASA='".$parametros['TRANSPORTE_CASA']."',
	                                    TEMPO_TRABALHO='".$parametros['TEMPO_TRABALHO']."',
	                                    TEMPO_CASA='".$parametros['TEMPO_CASA']."',
	                                    TRATAMENTO_RESPEITO='".$parametros['TRATAMENTO_RESPEITO']."',
	                                    LUGAR_TRABALHO='".$parametros['LUGAR_TRABALHO']."',
	                                    COMPROMISSO='".$parametros['COMPROMISSO']."',
	                                    OPINIOES='".$parametros['OPINIOES']."',
	                                    QUALIDADE='".$parametros['QUALIDADE']."',
	                                    ORIENTACAO='".$parametros['ORIENTACAO']."',
	                                    OPORTUNIDADE='".$parametros['OPORTUNIDADE']."',
	                                    RECONHECIMENTO='".$parametros['RECONHECIMENTO']."',
	                                    REALIZACAO='".$parametros['REALIZACAO']."',
	                                    SUCESSO='".$parametros['SUCESSO']."',
	                                    OUTRO_SETOR='".$parametros['OUTRO_SETOR']."',
	                                    SALARIO='".$parametros['SALARIO']."',
	                                    BENEFICIOS='".$parametros['BENEFICIOS']."',
	                                    INDICACAO='".$parametros['INDICACAO']."',
	                                    EXEMPLO='".$parametros['EXEMPLO']."',
	                                    IDEIA_BENEFICIO='".$parametros['IDEIA_BENEFICIO']."',
	                                    GOSTA='".$parametros['GOSTA']."',
	                                    FAMILIA_EMPRESA='".$parametros['FAMILIA_EMPRESA']."',
	                                    EQUIPAMENTOS='".$parametros['EQUIPAMENTOS']."',
	                                    PROTEGIDO='".$parametros['PROTEGIDO']."',
	                                    CHEFE_INFORMA='".$parametros['CHEFE_INFORMA']."',
	                                    FUTURO='".$parametros['FUTURO']."',
	                                    FONTE_INFORMACOES='".$parametros['FONTE_INFORMACOES']."',
	                                    PROBLEMAS_TRABALHO='".$parametros['PROBLEMAS_TRABALHO']."',
	                                    DESCONTOS='".$parametros['DESCONTOS']."',
	                                    EMPRESA_MELHORAR='".$parametros['EMPRESA_MELHORAR']."',
	                                    AVALIACAO_TRABALHO='".$parametros['AVALIACAO_TRABALHO']."',
	                                    RESULTADO_EMPRESA='".$parametros['RESULTADO_EMPRESA']."',
	                                    RAZAO_SALARIO='".$parametros['RAZAO_PRINCIPAL']."',
	                                    RAZAO_ESTABILIDADE='".$parametros['RAZAO_SECUNDARIO']."',
	                                    EMPRESA_ANTES='".$parametros['EMPRESA_ANTES']."',
	                                    COMENTARIO_EMPRESA_ANTES='".$parametros['COMENTARIO_EMPRESA_ANTES']."',
	                                    ILUMINACAO_VENTILACAO='".$parametros['ILUMINACAO_VENTILACAO']."',
	                                    TEMPERATURA='".$parametros['TEMPERATURA']."',
	                                    ESPACO_FISICO='".$parametros['ESPACO_FISICO']."',
	                                    HIGIENE_LIMPEZA='".$parametros['HIGIENE_LIMPEZA']."',
	                                    INSTALACOES_SANITARIAS='".$parametros['INSTALACOES_SANITARIAS']."',
	                                    INSATISFACAO_RECONHECIMENTO='".$parametros['INSATISFACAO_RECONHECIMENTO']."',
	                                    INSATISFACAO_SALARIO='".$parametros['INSATISFACAO_PRINCIPAL']."',
	                                    COMENTARIO_INSATISFACAO='".$parametros['COMENTARIO_SECUNDARIO']."',
	                                    ASSISTENCIA_MEDICA='".$parametros['ASSISTENCIA_MEDICA']."',
	                                    ASSISTENCIA_ODONTOLOGICA='".$parametros['ASSISTENCIA_ODONTOLOGICA']."',
	                                    ATENDIMENTO_RH='".$parametros['ATENDIMENTO_RH']."',
	                                    EMPRESTIMOS='".$parametros['EMPRESTIMOS']."',
	                                    LOJINHA='".$parametros['LOJINHA']."',
	                                    MEDICINA_AMBULATORIO='".$parametros['MEDICINA_AMBULATORIO']."',
	                                    POSTO_BANCARIO='".$parametros['POSTO_BANCARIO']."',
	                                    QUALIDADE_VIDA='".$parametros['QUALIDADE_VIDA']."',
	                                    RECRUTAMENTO_SELECAO='".$parametros['RECRUTAMENTO_SELECAO']."',
	                                    REFEITORIO='".$parametros['REFEITORIO']."',
	                                    TRANSPORTE='".$parametros['TRANSPORTE']."',
	                                    MATRICULA_ATEND = ".$this->session->userdata('idRegister')."		
	                                    WHERE COD_ACOMP_FALTAS = '" . $parametros['COD_ACOMP_FALTAS'] . "'");*/
										
										if(isset($parametros['data_cadastro']))
		{
			$data = $parametros['data_cadastro']." 10:00:00";
		}
		else
		{
			$data = 'sysdate';
		}
										
										$DB3->query("UPDATE ESM_RH_ACOMP_FALTAS SET   
													NUM_MATRICULA='".$parametros['NUM_MATRICULA']."',
													MATRICULA_SUPERVISOR='".$supervisor[1]."',
													SUPERVISOR='".utf8_decode($supervisor[0])."',
													MATRICULA_LIDER='".$lider[1]."',
													LIDER='".utf8_decode($lider[0])."',
													NOME_OUTRA_RENDA='".$parametros['NOME_OUTRA_RENDA']."',
													QTD_FALTAS='".$parametros['QTD_FALTAS']."',
													DESLIGAMENTO='".$parametros['DESLIGAMENTO']."',
													MOTIVO='".utf8_decode($parametros['MOTIVO'])."',
													JUSTIFICATIVA='".utf8_decode($parametros['JUSTIFICATIVA'])."',
													TRANSPORTE_EMPRESA='".utf8_decode($parametros['TRANSPORTE_EMPRESA'])."',
													TRANSPORTE_CASA='".utf8_decode($parametros['TRANSPORTE_CASA'])."',
													TEMPO_TRABALHO='".utf8_decode($parametros['TEMPO_TRABALHO'])."',
													TEMPO_CASA='".utf8_decode($parametros['TEMPO_CASA'])."',
													FEEDBACK_SUPERVISOR='".utf8_decode($parametros['FEEDBACK_SUPERVISOR'])."',
													PERIODO='".utf8_decode($parametros['PERIODO'])."',
													DATA_ACOMP='".$data."',
													COMENTARIOS='".utf8_decode($parametros['COMENTARIOS'])."',
													PORQ_INSATISFACAO='".utf8_decode($parametros['PORQ_INSATISFACAO'])."',
													RENDA='".$parametros['RENDA']."',
													VALOR_OUTRA_REMUNERADA='".$parametros['VALOR_OUTRA_REMUNERADA']."',
													OUTRA_RENDA='".$parametros['OUTRA_RENDA']."',
													SUSTENTO_AGREGADO='".$parametros['SUSTENTO_AGREGADO']."',
													TRATAMENTO_RESPEITO='".$parametros['TRATAMENTO_RESPEITO']."',
													LUGAR_TRABALHO='".$parametros['LUGAR_TRABALHO']."',
													PERCEBE_QUALIDADE='".$parametros['PERCEBE_QUALIDADE']."',
													OPINIOES='".$parametros['OPINIOES']."',
													QUALIDADE='".$parametros['QUALIDADE']."',
													ORIENTACAO='".$parametros['ORIENTACAO']."',
													OPORTUNIDADE='".$parametros['OPORTUNIDADE']."',
													REALIZACAO='".$parametros['REALIZACAO']."',
													SUCESSO='".$parametros['SUCESSO']."',
													OUTRO_SETOR='".$parametros['OUTRO_SETOR']."',
													SALARIO='".$parametros['SALARIO']."',
													BENEFICIOS='".$parametros['BENEFICIOS']."',
													INDICACAO='".$parametros['INDICACAO']."',
													EXEMPLO='".$parametros['EXEMPLO']."',
													IDEIA_BENEFICIO='".$parametros['IDEIA_BENEFICIO']."',
													GOSTA='".$parametros['GOSTA']."',
													FAMILIA_EMPRESA='".$parametros['FAMILIA_EMPRESA']."',
													EQUIPAMENTOS='".$parametros['EQUIPAMENTOS']."',
													PROTEGIDO='".$parametros['PROTEGIDO']."',
													CHEFE_INFORMA='".$parametros['CHEFE_INFORMA']."',
													FUTURO='".$parametros['FUTURO']."',
													FONTE_INFORMACOES='".$parametros['FONTE_INFORMACOES']."',
													PROBLEMAS_TRABALHO='".$parametros['PROBLEMAS_TRABALHO']."',
													DESCONTOS='".$parametros['DESCONTOS']."',
													EMPRESA_MELHORAR='".$parametros['EMPRESA_MELHORAR']."',
													AVALIACAO_TRABALHO='".$parametros['AVALIACAO_TRABALHO']."',
													RAZAO_PRINCIPAL='".$parametros['RAZAO_PRINCIPAL']."',
													RAZAO_SECUNDARIO='".$parametros['RAZAO_SECUNDARIO']."',
													EMPRESA_ANTES='".$parametros['EMPRESA_ANTES']."',
													COMENTARIO_EMPRESA_ANTES='".$parametros['COMENTARIO_EMPRESA_ANTES']."',
													ILUMINACAO_VENTILACAO='".$parametros['ILUMINACAO_VENTILACAO']."',
													TEMPERATURA='".$parametros['TEMPERATURA']."',
													ESPACO_FISICO='".$parametros['ESPACO_FISICO']."',
													HIGIENE_LIMPEZA='".$parametros['HIGIENE_LIMPEZA']."',
													BANHEIROS_VESTIARIOS='".$parametros['BANHEIROS_VESTIARIOS']."',
													COMENTARIO_INSATISFACAO='".$parametros['COMENTARIO_INSATISFACAO']."',
													ASSISTENCIA_MEDICA='".$parametros['ASSISTENCIA_MEDICA']."',
													ASSISTENCIA_ODONTOLOGICA='".$parametros['ASSISTENCIA_ODONTOLOGICA']."',
													ATENDIMENTO_RH='".$parametros['ATENDIMENTO_RH']."',
													CONVENIOS='".$parametros['CONVENIOS']."',
													LOJINHA='".$parametros['LOJINHA']."',
													MEDICINA_AMBULATORIO='".$parametros['MEDICINA_AMBULATORIO']."',
													POSTO_BANCARIO='".$parametros['POSTO_BANCARIO']."',
													QUALIDADE_VIDA='".$parametros['QUALIDADE_VIDA']."',
													RECRUTAMENTO_SELECAO='".$parametros['RECRUTAMENTO_SELECAO']."',
													REFEITORIO='".$parametros['REFEITORIO']."',
													TRANSPORTE='".$parametros['TRANSPORTE']."',
													COMENTARIO_OPINIAO_FUNCIONARIO='".$parametros['COMENTARIO_OPINIAO_FUNCIONARIO']."',
													COMENTARIO_QUALIDADE='".$parametros['COMENTARIO_QUALIDADE']."',
													COMENTARIO_REALIZACAO='".$parametros['COMENTARIO_REALIZACAO']."',
													COMENTARIO_OUTRO_SETOR='".$parametros['COMENTARIO_OUTRO_SETOR']."',
													BENEFICIO_SALARIO='".$parametros['BENEFICIO_SALARIO']."',
													COMENTARIO_AMIGO='".$parametros['COMENTARIO_AMIGO']."',
													MATRICULA_ATEND = ".$this->session->userdata('idRegister')."		
	                                    WHERE COD_ACOMP_FALTAS = '" . $parametros['COD_ACOMP_FALTAS'] . "'");
														


		$DB3 -> trans_complete();
		if ($DB3 -> trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}
	
	function deletar($COD_ACOMP_FALTAS){
		$DB3 = $this -> load -> database('logix', TRUE);

		$DB3 -> trans_start();
		
		$DB3->query("DELETE FROM ESM_RH_ACOMP_FALTAS WHERE COD_ACOMP_FALTAS = ".$COD_ACOMP_FALTAS."");
		
		$DB3 -> trans_complete();
		if ($DB3 -> trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}
	
	function getSupervisor($nome)
	{	
		//conecta o banco de dados UsersMaster
		$DB1 = $this->load->database('UsersMaster', TRUE);
		
		//consulta que retorna o lider
		$DB1->distinct();
		$DB1->select('Users.Name, Users.idRegister'); 
		$DB1->from('Users'); 
		$DB1->where('Users.DateExpire IS NULL');
		$DB1->like('Users.Name', strtoupper($nome));
		$DB1->order_by('Users.Name');


		$sup= $DB1->get()->result_array();  
		
		//@return variavel que retorna o valor $pro 
		return $sup;
		
	} 
	
	function getLider($nome)
	{	

		$DB3 = $this->load->database('funcio', TRUE);
		
		$lid = $DB3->query("SELECT DISTINCT 
									NOM_COMPLETO, 
									NUM_MATRICULA 
							FROM FUNCIONARIO 
							WHERE DAT_DEMIS IS NULL 
							AND NOM_COMPLETO LIKE '%". strtoupper($nome)."%' 
							ORDER BY NOM_COMPLETO")->result_array();   

		
		//@return variavel que retorna o valor $pro 
		return $lid;
		
	} 
	
	function permissao($matricula){
		$DB1 = $this->load->database('UsersMaster', TRUE);
		
		$DB1->trans_start();
		
		$DB1->distinct();
		$DB1->select('Users.idUser'); 
		$DB1->from('Users'); 
		$DB1->where('Users.DateExpire IS NULL');
		$DB1->where('Users.idRegister', $matricula);

		$id = $DB1->get()->result_array(); 
		
		$DB1->where('idUser',$id[0]['idUser']);
		$DB1->where('idSystem',60);
		$DB1->where('idLevel', 5);
		$permissao = $DB1->get('UserSystemsPermissions')->num_rows();
		
		if($permissao == 0){
		
			$acesso = array('idUser' => $id[0]['idUser'],
							'idSystem' => 60,
							'idLevel' => 5,
							'isBlocked' => 0);
			
			$DB1->insert('UserSystemsPermissions',$acesso);
		}
		
		$DB1->trans_complete();
		if($DB1->trans_status() === FALSE) {
			$retorno = false;
		} else {
			$retorno = true;
		}

		return $retorno;
	}
	
	function historicoQtdFaltas($periodo = "", $matricula = ""){
		
		$DB3 = $this->load->database('funcio', TRUE);
		
		$per = explode('-',$periodo);
		
		$sql = "select  NVL(sum(case mes when 'Janeiro' then qtd_tipo end),0) Janeiro,
						NVL(sum(case mes when 'Fevereiro' then qtd_tipo end),0) Fevereiro,
						NVL(sum(case mes when 'Março' then qtd_tipo end),0) Marco,
						NVL(sum(case mes when 'Abril' then qtd_tipo end),0) Abril,
						NVL(sum(case mes when 'Maio' then qtd_tipo end),0) Maio,
						NVL(sum(case mes when 'Junho' then qtd_tipo end),0) Junho,
						NVL(sum( case mes when 'Julho' then qtd_tipo end),0) Julho,
						NVL(sum(case mes when 'Agosto' then qtd_tipo end),0) Agosto,
						NVL(sum(case mes when 'Setembro' then qtd_tipo end),0) Setembro,
						NVL(sum(case mes when 'Outubro' then qtd_tipo end),0) Outubro,
						NVL(sum(case mes when 'Novembro' then qtd_tipo end),0) Novembro,
						NVL(sum(case mes when 'Dezembro' then qtd_tipo end),0) Dezembro
				from (SELECT count(EF.DAT_FALTA ) AS QTD_TIPO, 
							 SUBSTR (EF.PERIODO,1,INSTR (EF.PERIODO, '/') - 1) AS mes 
					  FROM esmaltec.ESM_RH_FALTAS EF 
					  WHERE EF.NUM_MATRICULA = '".$matricula."' 
					  and to_char(EF.DAT_FALTA,'yyyy') = '".$per[1]."' 
					  group by  SUBSTR (EF.PERIODO,1,INSTR (EF.PERIODO, '/') - 1))  QTD";
		
		$historico = $DB3->query($sql)->result_array();
		
		return $historico;
	}
	
	function historicoDatasFaltas($periodo = "", $matricula = ""){
		$DB3 = $this->load->database('funcio', TRUE);
		
		$per = explode('-',$periodo);
		
		$sql = "SELECT to_char(EF.DAT_FALTA,'DD/MM/YYYY') DAT_FALTA  
					  FROM esmaltec.ESM_RH_FALTAS EF 
					  WHERE EF.NUM_MATRICULA = '".$matricula."' 
					  and to_char(EF.DAT_FALTA,'yyyy') = '".$per[1]."' ";
					  
		$historicoDatas = $DB3->query($sql)->result_array();
		
		return  $historicoDatas;
	}
	
	function historicoMedidas($periodo = "", $matricula = ""){
		$DB3 = $this->load->database('lgx', TRUE);
		
		$per = explode('-',$periodo);
		
		$sql = "SELECT trim(OQUE)oque,
						 (select LOGIX.ADVERT_POR_MES_MATRICULA ('".$matricula."','".$per[1]."',null, 'ADVER') from dual ) as advertencia,
				(select LOGIX.ADVERT_POR_MES_MATRICULA ('".$matricula."' ,'".$per[1]."',null, 'SUSPEN') from dual ) as suspensao,
				SUM (DECODE (mes, 1, QTD_TIPO, 0)) JANEIRO,
				SUM (DECODE (mes, 2, QTD_TIPO, 0)) FEVEREIRO,
				SUM (DECODE (mes, 3, QTD_TIPO, 0)) MARCO,
				SUM (DECODE (mes, 4, QTD_TIPO, 0)) ABRIL,
				SUM (DECODE (mes, 5, QTD_TIPO, 0)) MAIO,
				SUM (DECODE (mes, 6, QTD_TIPO, 0)) JUNHO,
				SUM (DECODE (mes, 7, QTD_TIPO, 0)) JULHO,
				SUM (DECODE (mes, 8, QTD_TIPO, 0)) AGOSTO,
				SUM (DECODE (mes, 9, QTD_TIPO, 0)) SETEMBRO,
				SUM (DECODE (mes, 10,QTD_TIPO, 0)) OUTUBRO,
				SUM (DECODE (mes, 11,QTD_TIPO, 0)) NOVEMBRO,
				SUM (DECODE (mes, 12,QTD_TIPO, 0)) DEZEMBRO,
				COUNT (*) TOTAL_FALTAS
				FROM ((SELECT count(ad.OQUE ) AS QTD_TIPO,
						substr(ad.oque,1,1) oque,
						to_number(to_char((select LOGIX.GET_PERIODO_FOLHA (quando_dt) from dual),'mm')) as mes
						FROM logix.esm_intranet_rh_advertencias  ad
						WHERE ad.NUM_MATRICULA = '".$matricula."'
						and to_char(ad.QUANDO_DT,'yyyy') =  '".$per[1]."'
						and UPPER(CONVERT (AD.DESCRICAO , 'US7ASCII', 'WE8ISO8859P1')) = 'DESIDIA'
					   group by to_number(TO_CHAR(quando_dt,'mm')), oque,quando_dt, TO_CHAR(quando_dt,'mm/yyyy'))
				)
				GROUP BY  oque";
		$historicoMedidas = $DB3->query($sql)->result_array();
		
		//print_r($historicoMedidas);
		//die();
		
		return $historicoMedidas;
	}
	
	
	function historicoQtdMedidas($periodo = "", $matricula = ""){
		$DB3 = $this->load->database('lgx', TRUE);
		if(substr_count($periodo, '-') > 0 ){
			$per = explode('-',$periodo);
			$ano = $per[1];
		}else{
			$ano = $periodo;
		}
		
		$sql = "SELECT trim(OQUE)oque,
				COUNT (*) TOTAL_FALTAS
				FROM ((SELECT count(ad.OQUE ) AS QTD_TIPO,
						substr(ad.oque,1,1) oque,
						to_number(to_char((select LOGIX.GET_PERIODO_FOLHA (quando_dt) from dual),'mm')) as mes
						FROM logix.esm_intranet_rh_advertencias  ad
						WHERE ad.NUM_MATRICULA = '".$matricula."'
						and to_char(ad.QUANDO_DT,'yyyy') =  '".$ano."'
						and UPPER(CONVERT (AD.DESCRICAO , 'US7ASCII', 'WE8ISO8859P1')) = 'DESIDIA'
					   group by to_number(TO_CHAR(quando_dt,'mm')), oque,quando_dt, TO_CHAR(quando_dt,'mm/yyyy'))
				)
				GROUP BY  oque";
		$historicoMedidas = $DB3->query($sql)->result_array();
		
		//print_r($historicoMedidas);
		//die();
		
		return $historicoMedidas;
	}
	
	function numEntrevistasPendentes($periodo = ""){
		$DB3 = $this->load->database('logix', TRUE);
		
		$sqlNUmEntre = "select count(f.DAT_FALTA),
							   f.NUM_MATRICULA   
						from esmaltec.ESM_RH_FALTAS f  
						where f.periodo = '".$periodo."'  
						and not exists (select 1 from esmaltec.ESM_RH_ACOMP_FALTAS af where af.PERIODO = f.PERIODO  and af.NUM_MATRICULA =  f.NUM_MATRICULA  )
						having count(f.DAT_FALTA) >= 3
						group by f.NUM_MATRICULA";
		
		$numEntrevis = $DB3->query($sqlNUmEntre)->num_rows();
		
		return $numEntrevis;	
	}
	
	function retornaEmail($matricula = ""){
		$DB1 = $this->load->database('UsersMaster', TRUE);
		$sqlEmail = "SELECT Email FROM UsersMaster.Users  where idRegister = '".$matricula."'";
		$email = $DB1->query($sqlEmail)->result_array();
		return $email[0]['Email'];
	}
	
	function enviarEmail($periodo = ""){
		//$this->load->view('email.php');
		
		$DB3 = $this->load->database('funcio', TRUE);
		$DB2 = $this->load->database('logix', TRUE);
			
		$sqlListaEmail = "select matricula_supervisor 
						from ESMALTEC.ESM_RH_ACOMP_FALTAS AF
						where AF.PERIODO = '".$periodo."'
						and  AF.EMAIL_ENVIADO = 'N' 
						group by matricula_supervisor";
		
		$supervisores = $DB2->query($sqlListaEmail)->result_array();	
				
		foreach($supervisores as $row){
				
			$sqlFun = "select  F.NUM_MATRICULA, F.NOM_COMPLETO
                        from ESMALTEC.ESM_RH_ACOMP_FALTAS AF, 
                             LOGIX.FUNCIONARIO F
                        where F.NUM_MATRICULA = AF.NUM_MATRICULA
                        and AF.PERIODO = '".$periodo."'
                        and email_enviado = 'N'
                        and matricula_supervisor = '".$row['MATRICULA_SUPERVISOR']."'";
			$fun = $DB2->query($sqlFun)->result_array();
			
			$funcionario = '<table align="center" width="650" style="margin:10px; padding:10px;" border="1" cellpadding="0" cellspacing="0">
								<tr style="color:#01539F; font-weight:bold;">
										<td>Matricula</td>
										<td>Nome do Funcionário</td>
								</tr>';
			
			foreach($fun as $f){
				$funcionario .= '<tr><td>'.$f['NUM_MATRICULA'].'</td><td>'.$f['NOM_COMPLETO'].'</td></tr>';
			}
			
			$funcionario .= '</table>';
		
			
			$para = $this->retornaEmail($row['MATRICULA_SUPERVISOR']);
			$mensagem =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							<html xmlns="http://www.w3.org/1999/xhtml">
							<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							<title>Untitled Document</title>
							</head>
							
							<body style="text-align:center;" marginwidth="690px">
							<div style="border:1px solid #000; width:690px; margin:0px; font-family:Verdana, Geneva, sans-serif;">
							<table width="690px" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td width="600" align="center"><div style="color:#01539F; font-size:38px; font-weight:bold;">Feedback Funcionario</div></td>
									<td width="90"><img src="'.base_url().'images/parceiros_negocio.png"></td>
								</tr>
								<tr>
									<td colspan="2">
										<p style="color:#01539F; font-size:16px; text-align:justify; margin:10px;">Existe no Sistema de Atendimento do RH, uma avaliação a ser feita em relação ao absenteismo da competêcia de <u>'.$periodo.'</u>. Relativo ao seguintes Funcionários.</p>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<p style="font-size:16px; margin:10px;" align="center">
											'.$funcionario.'
										</p>        
									</td>
								</tr>
								<tr>
									<td colspan="2">
											 <p style="margin:10px; text-align:left; font-size:16px;"><a href="'.base_url().'">Clique aqui para acessar o sistema.</a></p>
									 </td>
									
								</tr>
								<tr>
									<td colspan="2" height="57px" valign="bottom"><img src="'.base_url().'images/barra_email.png">			</td>
								</tr>
							</table>
							</div>
							</body>
							</html>';
			
			
			
			
			$config['mailtype'] = 'html'; 
			$this->email->initialize($config);
			$this->email->from('null@intranet.esmaltec.com.br', 'Sistema de Atendimento');
			$this->email->to($para); 
			$this->email->cc('vivia@intranet.esmaltec.com.br'); 
			$this->email->bcc('fran@intranet.esmaltec.com.br'); 
		
			$this->email->subject('Feedback de Acompanhamento de Faltas');
			$this->email->message($mensagem);	
			$this->email->send();
			
			$sqlUpdate = "update ESMALTEC.ESM_RH_ACOMP_FALTAS set email_enviado = 'S' where matricula_supervisor = '".$row['MATRICULA_SUPERVISOR']."' 
							and periodo ='".$periodo."'";
			$DB2->query($sqlUpdate);
		}
		
	}
	
	
	

}
