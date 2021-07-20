<?php

class Atestado_Model extends CI_Model {

  function __construct() {
    parent::__construct();
    $this->load->database();
  }

  function getFuncionario($matricula = "") {
    $DB2 = $this->load->database('funcio', TRUE);



    $sql = "SELECT DISTINCT F.NUM_MATRICULA, 
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
                                        WHEN 'V' THEN 'VIÃšVO' 
                                        WHEN 'M' THEN 'MARITAL'
                                        WHEN 'P' THEN 'SEPARAÃ‡ÃƒO'
                                        WHEN 'U' THEN 'UNIÃƒO ESTÃ?VEL'END AS IES_EST_CIVIL,
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
                                        LEFT JOIN ESMALTEC.ESM_RH_FALTAS EF ON EF.NUM_MATRICULA = F.NUM_MATRICULA 
                                        INNER JOIN UNIDADE_FUNCIONAL UF ON UF.COD_EMPRESA = F.COD_EMPRESA AND UF.COD_UNI_FUNCIO = F.COD_UNI_FUNCIO
                                        INNER JOIN CIDADES CI ON  CI.COD_CIDADE = FI.COD_CIDADE
                                        INNER JOIN LOGIX.LINHA_PROD LP ON (TRIM (TO_CHAR (LP.COD_LIN_PROD, '09'))|| 
                                                                           TRIM (TO_CHAR (LP.COD_LIN_RECEI, '09'))|| 
                                                                           TRIM (TO_CHAR (LP.COD_SEG_MERC, '09'))|| 
                                                                           TRIM (TO_CHAR (LP.COD_CLA_USO, '09'))) = TRIM(UF.COD_AREA_LINHA)
                                        AND UF.DAT_VALIDADE_FIM >= SYSDATE
                                        
                                         AND F.NUM_MATRICULA = '" . trim($matricula) . "'
                                        GROUP BY F.NUM_MATRICULA,F.NOM_COMPLETO, UF.DEN_UNI_FUNCIO,TO_CHAR(F.DAT_ADMIS,'DD/MM/YYYY'),
                                        CI.DEN_CIDADE,FI.NUM_TELEF_RES,FLOOR(FLOOR(MONTHS_BETWEEN(SYSDATE, FI.DAT_NASCIMENTO)) / 12),
                                        FI.IES_EST_CIVIL, F.COD_EMPRESA,lp.DEN_ESTR_LINPROD,F.DAT_ADMIS,RHU_GRAU_INSTR.DES_GRAU_INSTR";
    // print_r($sql);
    //die();
    $funcio = $DB2->query($sql)->result_array();

    return $funcio;
  }

  function getFaltas($matricula = "") {
    $DB3 = $this->load->database('logix', TRUE);

    $faltas = $DB3->query("select NUM_MATRICULA, TO_CHAR(DAT_FALTA,'DD/MM/YYYY') as DAT_FALTA, PERIODO from ESM_RH_FALTAS where NUM_MATRICULA = '" . $matricula . "' ORDER BY ESM_RH_FALTAS.DAT_FALTA ASC");
    return $faltas;
  }

  function getAtestado($matricula) {
    $DB2 = $this->load->database('funcio', TRUE);
    $filtro_sql = "";
    $inicio = $fim = "";
    if ($inicio != "" && preg_match("/^(0[0-9]|1[0,1,2])\/\d{4}$/", $inicio)) {
      $filtro_sql .= " AND ";
    }
    if ($fim != "" && preg_match("/^(0[0-9]|1[0,1,2])\/\d{4}$/", $fim)) {
      $filtro_sql .= " AND ";
    }

    try {
      $atestado = $DB2->query("SELECT * FROM logix.esm_sispro_afastamento WHERE (CD_PALEG_AFAST = '20' OR CD_PALEG_AFAST = '21' OR CD_PALEG_AFAST = '24') AND CD_PAFUN_CONTR = '" . trim($matricula) . "' ORDER BY DT_INI_AFAST ASC")->result_array();
      if (!$atestado) {
        throw new Exception('Não foi possível consultar a base de dados dos atestados!');
      }
      if (count($atestado) == 0) {
        throw new Exception('Não existem atestados!');
      }

      $array_atestados = array();
      foreach ($atestado as $row) {
        $array = array();
        $saida = $row['DT_INI_AFAST'];
        $retorno = $row['DT_RET_AFAST'];
        $observacao = trim($row['AN_OBS_AFAST']);


        $data_saida = DateTime::createFromFormat('d/m/Y H:i:s', $saida);
        $data_retorno = DateTime::createFromFormat('d/m/Y H:i:s', $retorno);

        $intervalo = $data_retorno->diff($data_saida);
        $qtd_dias = ($intervalo->days < 0) ? 0 : $intervalo->days;
        $periodo = $this->getPeriodo($data_saida);
        $array['saida'] = $data_saida->format('d/m/Y');
        $array['retorno'] = $data_retorno->format('d/m/Y');
        $array['qtd_dia'] = $qtd_dias;
        $array['periodo'] = $periodo;

        $cid_descricao = '';
        if ($observacao != '') {
          if (strlen($observacao) == 3) {
            $cid = $observacao;

            $cid_descricao = $this->getCID($cid);
            $capitulo_descricao = $this->getCapituloCID($cid);
            $array['capitulo_descricao'] = $capitulo_descricao;
            $array['cid'] = $cid;
            $array['cid_descricao'] = $cid_descricao;
            
            $array['crm'] = '-';
            $array['medico'] = '-';
          } else if (strlen($observacao) == 4) {
            $cid = $observacao;
            $cid_descricao = $this->getCID($cid);
            $capitulo_descricao = $this->getCapituloCID($cid);
            $array['cid'] = $cid;
            $array['cid_descricao'] = $cid_descricao;
            $array['capitulo_descricao'] = $capitulo_descricao;
            $array['crm'] = '-';
            $array['medico'] = '-';
          } else if (substr_count($observacao, ';') > 0) {
            $explode = explode(';', $observacao);
            $cid = (isset($explode[0])) ? $explode[0] : '';
            $crm = (isset($explode[1])) ? $explode[1] : '';
            $medico = (isset($explode[2])) ? $explode[2] : '';
			$posto = (isset($explode[3])) ? $explode[3] : '';
            $cid_descricao = $this->getCID($cid);
            $capitulo_descricao = $this->getCapituloCID($cid);
            $array['cid'] = $cid;
            $array['cid_descricao'] = $cid_descricao;
            $array['capitulo_descricao'] = $capitulo_descricao;
            $array['crm'] = $crm;
            $array['medico'] = $medico;
			$array['posto'] = $posto;
          } else {
            $cid = substr($observacao, 0, 4);
            if (preg_match("/^[A-Z]\d{2,3}$/", $cid)) {
              $cid_descricao = $this->getCID($cid);
              $capitulo_descricao = $this->getCapituloCID($cid);
              $array['cid'] = $cid;
              $array['cid_descricao'] = $cid_descricao;
              $array['capitulo_descricao'] = $capitulo_descricao;
              $array['crm'] = '-';
              $array['medico'] = '-';
            } else {
              $cid_descricao = '-';
              $array['cid'] = '-';
              $array['cid_descricao'] = '-';
              $array['capitulo_descricao'] = '-';
              $array['crm'] = '-';
              $array['medico'] = '-';
            }
          }
        } else {
          $array['cid'] = '-';
          $array['cid_descricao'] = '-';
          $array['capitulo_descricao'] = '-';
          $array['crm'] = '-';
          $array['medico'] = '-';
        }
        $array_atestados[] = $array;
      }//Fim do foreach
      //var_dump($array_atestados);
      return $array_atestados;
    } catch (Exception $ex) {
      return $ex->getMessage();
    }
  }

  function getAtestadoPorPeriodo($periodo) {
    $DB2 = $this->load->database('funcio', TRUE);
    $filtro_sql = "";

    if ($periodo != "") {
      $filtro_sql = " AND EXTRACT(YEAR FROM DT_INI_AFAST) = '" . $periodo . "' ";
    }
    try {

      $atestadoFuncionario = $DB2->query("SELECT distinct(CD_PAFUN_CONTR) as MATRICULA FROM logix.esm_sispro_afastamento 
			WHERE (CD_PALEG_AFAST = '20' OR CD_PALEG_AFAST = '21' OR CD_PALEG_AFAST = '24') 
			AND EXTRACT(YEAR FROM DT_INI_AFAST) = '" . $periodo . "' AND ROWNUM < 6 ")->result_array();
      if (!$atestadoFuncionario) {
        throw new Exception('Não foi possível consultar a base de dados dos atestados!');
      }
      if (count($atestadoFuncionario) == 0) {
        throw new Exception('Não existem nenhum funcionário!');
      }



      $array_funcionario = array();
      $i = 0;
      foreach ($atestadoFuncionario as $row) {

        //if($i < 100){
        $i++;
        $matricula = $row["MATRICULA"];
        $funcionario = $this->getFuncionario($matricula);
        $atestado = $DB2->query("SELECT EXTRACT(month FROM DT_INI_AFAST) as MES, count(CD_PAFUN_CONTR) as QTD 
					FROM logix.esm_sispro_afastamento WHERE (CD_PALEG_AFAST = '20' OR CD_PALEG_AFAST = '21' OR CD_PALEG_AFAST = '24') 
					AND CD_PAFUN_CONTR = '" . trim($matricula) . "' AND EXTRACT(YEAR FROM DT_INI_AFAST) = '" . $periodo . "'
					group by EXTRACT(month FROM DT_INI_AFAST) order by MES")->result_array();

        if (count($atestado) > 0) {
          $array_funcionario[$matricula]["atestado"] = $atestado;
          $array_funcionario[$matricula]["objeto"] = $funcionario;
        }
        //}
      }

      return $array_funcionario;
    } catch (Exception $ex) {
      return $ex->getMessage();
    }
  }

  public function getCID($cid) {
    $DB3 = $this->load->database('cid', TRUE);


    $cid = trim($cid);
    $cid = str_replace('.', '', $cid);
    $rows = '';
    if (strlen($cid) == 3) {

      $sql = "SELECT DESCRICAO FROM cid_10_categoria WHERE CAT = '" . $cid . "'";

      $rows = $DB3->query($sql)->result_array();
    } else if (strlen($cid) == 4) {
      $sql = "SELECT DESCRICAO FROM cid_10_subcategoria WHERE SUBCAT = '" . $cid . "'";
      $rows = $DB3->query($sql)->result_array();
    }
    $cid_descricao = 'Sem Descrição';
    if (count($rows) > 0 && is_array($rows)) {
      foreach ($rows as $row) {
        $cid_descricao = $row['DESCRICAO'];
      }
    } else {
      $cid_descricao = 'Sem Descrição';
    }
    return $cid_descricao;
  }
  
  
  public function getCapituloCID($cid) {
    $DB3 = $this->load->database('cid', TRUE);


    $cid = trim($cid);
    $cid = str_replace('.', '', $cid);
    $rows = '';
    if (strlen($cid) == 3) {

      $sql = "SELECT c.DESCRICAO FROM cid_10_capitulo c INNER JOIN cid_10_categoria_fk cat ON cat.NUMCAP = c.NUMCAP
WHERE cat.CAT = '" . $cid . "'";

      $rows = $DB3->query($sql)->result_array();
    } else if (strlen($cid) == 4) {
      $sql = "SELECT c.DESCRICAO FROM cid_10_capitulo c INNER JOIN cid_10_subcategoria_fk cat ON cat.NUMCAP = c.NUMCAP
WHERE cat.SUBCAT = '" . $cid . "'";
      $rows = $DB3->query($sql)->result_array();
    }
    $cid_descricao = 'Sem Descrição';
    if (count($rows) > 0 && is_array($rows)) {
      foreach ($rows as $row) {
        $cid_descricao = $row['DESCRICAO'];
      }
    } else {
      $cid_descricao = 'Sem Descrição';
    }
    return $cid_descricao;
  }

  private function getPeriodo($inicio) {
    $mes = 0;
    if ($inicio->format('d') >= 11) {
      $mes = ($inicio->format('m') == 12) ? 1 : $inicio->format('m') + 1;
    } else if ($inicio->format('d') < 11) {
      $mes = $inicio->format('m');
    }
    return $this->getMes($mes) . '/' . $inicio->format('Y');
  }

  private function getMes($mes) {
    switch ($mes) {
      case 1: return 'Janeiro';
        break;
      case 2: return 'Fevereiro';
        break;
      case 3: return 'Março';
        break;
      case 4: return 'Abril';
        break;
      case 5: return 'Maio';
        break;
      case 6: return 'Junho';
        break;
      case 7: return 'Julho';
        break;
      case 8: return 'Agosto';
        break;
      case 9: return 'Setembro';
        break;
      case 10: return 'Outubro';
        break;
      case 11: return 'Novembro';
        break;
      case 12: return 'Dezembro';
        break;
      default: return 'Sem Período';
        break;
    }
  }

  function historicoQtdFaltas($matricula = "") {

    $DB3 = $this->load->database('funcio', TRUE);



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
					  WHERE EF.NUM_MATRICULA = '" . $matricula . "' 
					  
					  group by  SUBSTR (EF.PERIODO,1,INSTR (EF.PERIODO, '/') - 1))  QTD";

    $historico = $DB3->query($sql)->result_array();

    return $historico;
  }

  function historicoDatasFaltas($matricula = "") {
    $DB3 = $this->load->database('funcio', TRUE);



    $sql = "SELECT to_char(EF.DAT_FALTA,'DD/MM/YYYY') DAT_FALTA  
					  FROM esmaltec.ESM_RH_FALTAS EF 
					  WHERE EF.NUM_MATRICULA = '" . $matricula . "' 
					   ";

    $historicoDatas = $DB3->query($sql)->result_array();

    return $historicoDatas;
  }

  function historicoMedidas($matricula = "") {
    $DB3 = $this->load->database('lgx', TRUE);



    $sql = "SELECT trim(OQUE)oque,
						 (select LOGIX.ADVERT_POR_MES_MATRICULA ('" . $matricula . "',null,null, 'ADVER') from dual ) as advertencia,
				(select LOGIX.ADVERT_POR_MES_MATRICULA ('" . $matricula . "' ,null,null, 'SUSPEN') from dual ) as suspensao,
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
						WHERE ad.NUM_MATRICULA = '" . $matricula . "'
						
						and UPPER(CONVERT (AD.DESCRICAO , 'US7ASCII', 'WE8ISO8859P1')) = 'DESIDIA'
					   group by to_number(TO_CHAR(quando_dt,'mm')), oque,quando_dt, TO_CHAR(quando_dt,'mm/yyyy'))
				)
				GROUP BY  oque";
    $historicoMedidas = $DB3->query($sql)->result_array();

    //print_r($historicoMedidas);
    //die();

    return $historicoMedidas;
  }
  
  function gerarPDF($parametros)
  {
	 $this->load->helper('download');
	 $this->load->library('tcpdf/TCPDF');
	 
	 $pdf = new TCPDF();
	 //print_r($pdf);
	$pdf->SetFont('helvetica', '', 14, '', true);
	 $pdf->AddPage('P');
	
	$file =  $pdf->writeHTML($parametros);
	force_download($pdf->Output('teste.pdf','I'));	
	 
	  
  }

  function getAtestado2($data_inicio, $data_fim, $demitido, $afastamento) {
    $DB2 = $this->load->database('funcio', TRUE);
    $filtro_sql = "";
    $inicio = $fim = "";
    if ($data_inicio != "") {
      $filtro_sql .= " AND S.DT_INI_AFAST >= TO_DATE('$data_inicio','DD/MM/YYYY') ";
    }
    if ($data_fim != "") {
      $filtro_sql .= " AND S.DT_INI_AFAST <= TO_DATE('$data_fim','DD/MM/YYYY')";
    }
    if ($afastamento == "0") {
      $filtro_sql .= " AND (CD_PALEG_AFAST = '20' OR CD_PALEG_AFAST = '21' OR CD_PALEG_AFAST = '24') ";
    } else {
      $filtro_sql .= " AND (CD_PALEG_AFAST = '$afastamento') ";
    }
    if ($demitido == 'nao') {
      $filtro_sql .= " AND F.DAT_DEMIS IS NULL ";
    }
   
    try {
      $atestado = $DB2->query("SELECT F.NUM_MATRICULA, F.NOM_COMPLETO, UF.DEN_UNI_FUNCIO, C.DEN_CARGO, AN.DEN_ESTR_LINPROD AS GERENCIA, TO_CHAR(S.DT_INI_AFAST,'DD/MM/YYYY') AS DATA_INICIO, TO_CHAR(S.DT_RET_AFAST,'DD/MM/YYYY') AS DATA_FIM, S.AN_OBS_AFAST, S.CD_PALEG_AFAST FROM logix.esm_sispro_afastamento S, logix.FUNCIONARIO F, logix.UNIDADE_FUNCIONAL UF, logix.CARGO C, logix.esm_area_negocio AN
WHERE S.CD_PAFUN_CONTR = F.NUM_MATRICULA AND UF.COD_EMPRESA = F.COD_EMPRESA AND UF.COD_UNI_FUNCIO = F.COD_UNI_FUNCIO 
AND C.COD_CARGO = F.COD_CARGO
AND uf.DAT_VALIDADE_FIM > sysdate
AND c.DAT_VALIDADE_FIM > sysdate
AND TRIM(AN.COD_AREA_LINHA) = TRIM(UF.COD_AREA_LINHA) $filtro_sql
group by f.num_matricula, f.nom_completo, uf.den_uni_funcio, c.den_cargo,
         an.den_estr_linprod , s.dt_ini_afast, s.dt_ret_afast,
         s.an_obs_afast,
         s.cd_paleg_afast
ORDER BY S.DT_INI_AFAST ASC, F.NOM_COMPLETO ASC")->result_array();
      if (!$atestado) {
        throw new Exception('Não foi possível consultar a base de dados dos atestados!');
      }
      if (count($atestado) == 0) {
        throw new Exception('Não existem atestados!');
      }

      $array_atestados = array();
      foreach ($atestado as $row) {
        $array = array();
        $saida = $row['DATA_INICIO'];
        $retorno = $row['DATA_FIM'];
        $observacao = trim($row['AN_OBS_AFAST']);
        $paleg = trim($row['CD_PALEG_AFAST']);
        switch ($paleg) {
          case '20': $paleg_nome = 'ACIDENTE DE TRABALHO';
            break;
          case '21': $paleg_nome = 'AFASTAMENTO DOENÇA';
            break;
          case '24': $paleg_nome = 'LICENÇA MATERNIDADE';
            break;
          default: $paleg_nome = 'SEM DESCRIÇÃO';
            break;
        }

        $data_saida = DateTime::createFromFormat('d/m/Y', $saida);
        $data_retorno = DateTime::createFromFormat('d/m/Y', $retorno);


        $intervalo = $data_retorno->diff($data_saida);
        $qtd_dias = ($intervalo->days < 0) ? 0 : $intervalo->days;
        $periodo = $this->getPeriodo($data_saida);
        $array['saida'] = $data_saida->format('d/m/Y');
        $array['retorno'] = $data_retorno->format('d/m/Y');
        $array['qtd_dia'] = $qtd_dias;
        $array['periodo'] = $periodo;
        $array['matricula'] = $row['NUM_MATRICULA'];
        $array['nome'] = $row['NOM_COMPLETO'];
        $array['setor'] = $row['DEN_UNI_FUNCIO'];
        $array['cargo'] = $row['DEN_CARGO'];
        $array['gerencia'] = $row['GERENCIA'];
        $array['afastamento'] = $paleg_nome;

        $cid_descricao = '';
        if ($observacao != '') {
          if (strlen($observacao) == 3) {
            $cid = $observacao;

            $cid_descricao = $this->getCID($cid);
            $capitulo_descricao = $this->getCapituloCID($cid);
            $array['capitulo_descricao'] = $capitulo_descricao;
            $array['cid'] = $cid;
            $array['cid_descricao'] = $cid_descricao;
            $array['crm'] = '-';
            $array['medico'] = '-';
          } else if (strlen($observacao) == 4) {
            $cid = $observacao;
            $cid_descricao = $this->getCID($cid);
            $capitulo_descricao = $this->getCapituloCID($cid);
            $array['capitulo_descricao'] = $capitulo_descricao;
            $array['cid'] = $cid;
            $array['cid_descricao'] = $cid_descricao;
            $array['crm'] = '-';
            $array['medico'] = '-';
          } else if (substr_count($observacao, ';') > 0) {
            $explode = explode(';', $observacao);
            $cid = (isset($explode[0])) ? $explode[0] : '';
            $crm = (isset($explode[1])) ? $explode[1] : '';
            $medico = (isset($explode[2])) ? $explode[2] : '';
            $cid_descricao = $this->getCID($cid);
            $capitulo_descricao = $this->getCapituloCID($cid);
            $array['capitulo_descricao'] = $capitulo_descricao;
            $array['cid'] = $cid;
            $array['cid_descricao'] = $cid_descricao;
            $array['crm'] = $crm;
            $array['medico'] = $medico;
          } else {
            $cid = substr($observacao, 0, 4);
            if (preg_match("/^[A-Z]\d{2,3}$/", $cid)) {
              $cid_descricao = $this->getCID($cid);
              $capitulo_descricao = $this->getCapituloCID($cid);
              $array['capitulo_descricao'] = $capitulo_descricao;
              $array['cid'] = $cid;
              $array['cid_descricao'] = $cid_descricao;
              $array['crm'] = '-';
              $array['medico'] = '-';
            } else {
              $cid_descricao = '-';
              $array['cid'] = '-';
              $array['cid_descricao'] = '-';
              $array['capitulo_descricao'] = '-';
              $array['crm'] = '-';
              $array['medico'] = '-';
            }
          }
        } else {
          $array['cid'] = '-';
          $array['cid_descricao'] = '-';
          $array['capitulo_descricao'] = '-';
          $array['crm'] = '-';
          $array['medico'] = '-';
        }
        $array_atestados[] = $array;
      }//Fim do foreach
      //var_dump($array_atestados);
      return $array_atestados;
    } catch (Exception $ex) {
      return $ex->getMessage();
    }
  }
  
  function getAtestado3($data_inicio, $data_fim, $demitido, $afastamento) {
    $DB2 = $this->load->database('funcio', TRUE);
    $filtro_sql = "";
    $inicio = $fim = "";
    if ($data_inicio != "") {
      $filtro_sql .= " AND S.DT_INI_AFAST >= TO_DATE('$data_inicio','DD/MM/YYYY') ";
    }
    if ($data_fim != "") {
      $filtro_sql .= " AND S.DT_INI_AFAST <= TO_DATE('$data_fim','DD/MM/YYYY')";
    }
    if ($afastamento == "0") {
      $filtro_sql .= " AND (CD_PALEG_AFAST = '20' OR CD_PALEG_AFAST = '21' OR CD_PALEG_AFAST = '24') ";
    } else {
      $filtro_sql .= " AND (CD_PALEG_AFAST = '$afastamento') ";
    }
    if ($demitido == 'nao') {
      $filtro_sql .= " AND F.DAT_DEMIS IS NULL ";
    }
    //echo '<script>window.alert("' . $filtro_sql . '")</script>';
    try {
      $atestado = $DB2->query("SELECT F.NUM_MATRICULA, F.NOM_COMPLETO, UF.DEN_UNI_FUNCIO, C.DEN_CARGO, AN.DEN_ESTR_LINPROD AS GERENCIA, TO_CHAR(S.DT_INI_AFAST,'DD/MM/YYYY') AS DATA_INICIO, TO_CHAR(S.DT_RET_AFAST,'DD/MM/YYYY') AS DATA_FIM, S.AN_OBS_AFAST, S.CD_PALEG_AFAST FROM logix.esm_sispro_afastamento S, logix.FUNCIONARIO F, logix.UNIDADE_FUNCIONAL UF, logix.CARGO C, logix.esm_area_negocio AN
WHERE S.CD_PAFUN_CONTR = F.NUM_MATRICULA AND UF.COD_EMPRESA = F.COD_EMPRESA AND UF.COD_UNI_FUNCIO = F.COD_UNI_FUNCIO 
AND C.COD_CARGO = F.COD_CARGO
AND uf.DAT_VALIDADE_FIM > sysdate
AND c.DAT_VALIDADE_FIM > sysdate
AND TRIM(AN.COD_AREA_LINHA) = TRIM(UF.COD_AREA_LINHA) $filtro_sql
ORDER BY S.DT_INI_AFAST ASC, F.NOM_COMPLETO ASC")->result_array();
      if (!$atestado) {
        throw new Exception('Não foi possível consultar a base de dados dos atestados!');
      }
      if (count($atestado) == 0) {
        throw new Exception('Não existem atestados!');
      }

      $array_atestados = array();
      foreach ($atestado as $row) {
        $array = array();
        $saida = $row['DATA_INICIO'];
        $retorno = $row['DATA_FIM'];
        $observacao = trim($row['AN_OBS_AFAST']);
        $paleg = trim($row['CD_PALEG_AFAST']);
        switch ($paleg) {
          case '20': $paleg_nome = 'ACIDENTE DE TRABALHO';
            break;
          case '21': $paleg_nome = 'AFASTAMENTO DOENÇA';
            break;
          case '24': $paleg_nome = 'LICENÇA MATERNIDADE';
            break;
          default: $paleg_nome = 'SEM DESCRIÇÃO';
            break;
        }

        $data_saida = DateTime::createFromFormat('d/m/Y', $saida);
        $data_retorno = DateTime::createFromFormat('d/m/Y', $retorno);


        $intervalo = $data_retorno->diff($data_saida);
        $qtd_dias = ($intervalo->days < 0) ? 0 : $intervalo->days;
        $periodo = $this->getPeriodo($data_saida);
        $array['saida'] = $data_saida->format('d/m/Y');
        $array['retorno'] = $data_retorno->format('d/m/Y');
        $array['qtd_dia'] = $qtd_dias;
        $array['periodo'] = $periodo;
        $array['matricula'] = $row['NUM_MATRICULA'];
        $array['nome'] = $row['NOM_COMPLETO'];
        $array['setor'] = $row['DEN_UNI_FUNCIO'];
        $array['cargo'] = $row['DEN_CARGO'];
        $array['gerencia'] = $row['GERENCIA'];
        $array['afastamento'] = $paleg_nome;

        $cid_descricao = '';
        if ($observacao != '') {
          if (strlen($observacao) == 3) {
            $cid = $observacao;

            $cid_descricao = $this->getCID($cid);
            $capitulo_descricao = $this->getCapituloCID($cid);
            $array['capitulo_descricao'] = $capitulo_descricao;
            $array['cid'] = $cid;
            $array['cid_descricao'] = $cid_descricao;
            $array['crm'] = '-';
            $array['medico'] = '-';
          } else if (strlen($observacao) == 4) {
            $cid = $observacao;
            $cid_descricao = $this->getCID($cid);
            $capitulo_descricao = $this->getCapituloCID($cid);
            $array['capitulo_descricao'] = $capitulo_descricao;
            $array['cid'] = $cid;
            $array['cid_descricao'] = $cid_descricao;
            $array['crm'] = '-';
            $array['medico'] = '-';
          } else if (substr_count($observacao, ';') > 0) {
            $explode = explode(';', $observacao);
            $cid = (isset($explode[0])) ? $explode[0] : '';
            $crm = (isset($explode[1])) ? $explode[1] : '';
            $medico = (isset($explode[2])) ? $explode[2] : '';
            $cid_descricao = $this->getCID($cid);
            $capitulo_descricao = $this->getCapituloCID($cid);
            $array['capitulo_descricao'] = $capitulo_descricao;
            $array['cid'] = $cid;
            $array['cid_descricao'] = $cid_descricao;
            $array['crm'] = $crm;
            $array['medico'] = $medico;
          } else {
            $cid = substr($observacao, 0, 4);
            if (preg_match("/^[A-Z]\d{2,3}$/", $cid)) {
              $cid_descricao = $this->getCID($cid);
              $capitulo_descricao = $this->getCapituloCID($cid);
              $array['capitulo_descricao'] = $capitulo_descricao;
              $array['cid'] = $cid;
              $array['cid_descricao'] = $cid_descricao;
              $array['crm'] = '-';
              $array['medico'] = '-';
            } else {
              $cid_descricao = '-';
              $array['cid'] = '-';
              $array['cid_descricao'] = '-';
              $array['capitulo_descricao'] = '-';
              $array['crm'] = '-';
              $array['medico'] = '-';
            }
          }
        } else {
          $array['cid'] = '-';
          $array['cid_descricao'] = '-';
          $array['capitulo_descricao'] = '-';
          $array['crm'] = '-';
          $array['medico'] = '-';
        }
        $array_atestados[] = $array;
      }//Fim do foreach
      //var_dump($array_atestados);
      return $array_atestados;
    } catch (Exception $ex) {
      return $ex->getMessage();
    }
  }

}
