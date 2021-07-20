<?php
class Painel_Model extends CI_Model{

    function __construct()
    {
        $this->load->database();
        parent::__construct();
    }

    function veiculos_saida($locais=null)
    {
		if(!empty($locais))
		{
			$extra=" AND LOCAL_CARREGAMENTO = '".$locais."' ";
		}
		else {
			$extra=" ";
		}
        $sql = "SELECT *
		  FROM (  SELECT CARGA.ID_CARGA,UPPER (REPLACE (CARGA.PLACA_VEICULO, ' ', '')) AS VEICULO,
						  TO_CHAR(CARGA.HORA_CARREGAMENTO_FIM,'DD/MM HH24:MI') AS REGISTRO_PORTARIA,
						   ROUND((SYSDATE  - CARGA.HORA_CARREGAMENTO_FIM) * 1440) TEMPO, 
							   SUBSTR(CASE
								  WHEN CARGA.TIPO_TRANSPORTADORA = 'T'
								  THEN
									 (SELECT T.NOME_TRANSPORTADORA AS NOME
										FROM ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
									   WHERE T.ID_TRANSPORTADORA =
												CARGA.ID_TRANSPORTADORA)
								  WHEN CARGA.TIPO_TRANSPORTADORA = 'C'
								  THEN
									 (SELECT CLIENTE.NOME_CLIENTE AS NOME
										FROM ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
									   WHERE CLIENTE.ID_CLIENTE =
												CARGA.ID_TRANSPORTADORA)
							   END,0,10)||'...'
							AS TRANSPORTADORA,
							STATUS,
							CASE WHEN (OBSERVACAO IS NULL)
							      THEN INFORMACOES_FISCAIS
							      WHEN (OBSERVACAO IS NOT NULL)
							      THEN OBSERVACAO ||' - '|| INFORMACOES_FISCAIS 
							END INFORMACOES_FISCAIS,

							HORA_CRIACAO_ROMANEIO_INI,
							ROUND ( (SYSDATE - HORA_CRIACAO_ROMANEIO_INI) * 1440) TEMPO_ROM,
							 TO_CHAR (CARGA.HORA_LIB_FISCAL_FIM, 'DD/MM HH24:MI') HORA_LIB_FISCAL_FIM,
							 ROUND ( (SYSDATE - HORA_LIB_FISCAL_FIM) * 1440) TEMP_SAI,
							 ROUND ( (SYSDATE - HORA_LIB_FISCAL_INI) * 1440) TEMP_FIS,
							 LOCAL_CARREGAMENTO
					FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
						 JOIN ESMALTEC.ESM_LOGISTICA_CIDADES CD ON CD.ID = CARGA.CIDADE
						 JOIN ESMALTEC.ESM_LOGISTICA_ESTADOS EST
							ON EST.ID = CARGA.ESTADO
						 LEFT JOIN ESMALTEC.ESM_LOGISTICA_CLIENTE CLI
							ON CLI.ID_CLIENTE = CARGA.ID_CLIENTE
						 LEFT JOIN ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA TRA
							ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA
				   WHERE STATUS IN ('SAI', 'ROM', 'FIS', 'DIF')".$extra."
				ORDER BY CARGA.HORA_CARREGAMENTO_FIM ASC)";

         $dados =  $this->db->query($sql)->result_array();
         return $dados;
    }

	function conta_veiculos_saida($locais = null)
	{
		if(!empty($locais))
		{
			$extra=" AND LOCAL_CARREGAMENTO = '".$locais."' ";
		}
		else {
			$extra=" ";
		}
		$sql = "SELECT COUNT(*) TOTAL FROM ESMALTEC. ESM_LOGISTICA_CARGA WHERE STATUS IN ('SAI','ROM','FIS','DIF')".$extra;

		$dados_contagem = $this->db->query($sql)->result_array();
		return $dados_contagem[0];
	}

    function veiculos_docas($locais = null)
    {
		if(!empty($locais))
		{
			$extra=" AND LOCAL_CARREGAMENTO = '".$locais."' ";
		}
		else {
			$extra=" ";
		}
        $sql="SELECT *
		  FROM (  SELECT  CARGA.ID_CARGA,UPPER (REPLACE (CARGA.PLACA_VEICULO, ' ', '')) AS VEICULO,
						 CARGA.HORA_CARREGAMENTO_INI AS REGISTRO_PORTARIA,
							   SUBSTR(CASE
								  WHEN CARGA.TIPO_TRANSPORTADORA = 'T'
								  THEN
									 (SELECT T.NOME_TRANSPORTADORA AS NOME
										FROM ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
									   WHERE T.ID_TRANSPORTADORA =
												CARGA.ID_TRANSPORTADORA)
								  WHEN CARGA.TIPO_TRANSPORTADORA = 'C'
								  THEN
									 (SELECT CLIENTE.NOME_CLIENTE AS NOME
										FROM ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
									   WHERE CLIENTE.ID_CLIENTE =
												CARGA.ID_TRANSPORTADORA)
							   END,0,10)||'...'
							AS TRANSPORTADORA,
						 CARGA.DOCA,
						 TO_CHAR(CARGA.HORA_CARREGAMENTO_INI,'DD/MM HH24:MI') AS HORA_ENTRADA,
						 CARGA.LOCAL_CARREGAMENTO
					FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
						 JOIN ESMALTEC.ESM_LOGISTICA_CIDADES CD ON CD.ID = CARGA.CIDADE
						 JOIN ESMALTEC.ESM_LOGISTICA_ESTADOS EST
							ON EST.ID = CARGA.ESTADO
						 LEFT JOIN ESMALTEC.ESM_LOGISTICA_CLIENTE CLI
							ON CLI.ID_CLIENTE = CARGA.ID_CLIENTE
						 LEFT JOIN ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA TRA
							ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA
				   WHERE STATUS IN ('CAR')".$extra."
				ORDER BY CARGA.ID_CARGA ASC)
		 ";

                    $dados = $this->db->query($sql)->result_array();
                    return $dados;
    }
	function conta_veiculos_docas($locais = null)
	{
		if(!empty($locais))
		{
			$extra=" AND LOCAL_CARREGAMENTO = '".$locais."' ";
		}
		else {
			$extra=" ";
		}

		$sql = "SELECT COUNT(*) TOTAL FROM ESMALTEC. ESM_LOGISTICA_CARGA WHERE STATUS IN ('CAR')".$extra;

		$dados_contagem = $this->db->query($sql)->result_array();
		return $dados_contagem[0];
	}

    function veiculos_entrada($locais = null)
    {
		if(!empty($locais))
		{
			$extra=" AND LOCAL_CARREGAMENTO = '".$locais."' ";
		}
		else {
			$extra=" ";
		}

        $sql = "SELECT *
			  FROM (  SELECT CARGA.ID_CARGA,UPPER (REPLACE (CARGA.PLACA_VEICULO, ' ', '')) AS VEICULO,
							 CARGA.HORA_ENTRADA AS REGISTRO_PORTARIA,
								
								   SUBSTR(CASE
									  WHEN CARGA.TIPO_TRANSPORTADORA = 'T'
									  THEN
										 (SELECT T.NOME_TRANSPORTADORA AS NOME
											FROM ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
										   WHERE T.ID_TRANSPORTADORA =
													CARGA.ID_TRANSPORTADORA)
									  WHEN CARGA.TIPO_TRANSPORTADORA = 'C'
									  THEN
										 (SELECT CLIENTE.NOME_CLIENTE AS NOME
											FROM ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
										   WHERE CLIENTE.ID_CLIENTE =
													CARGA.ID_TRANSPORTADORA)
								   END,0,10)||'...'
								AS TRANSPORTADORA,
							  TO_CHAR(CARGA.HORA_ENTRADA,'DD/MM HH24:MI') AS HORA_ENTRADA,
							 CARGA.DOCA,
							 CARGA.LOCAL_CARREGAMENTO
						FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
							 JOIN ESMALTEC.ESM_LOGISTICA_CIDADES CD ON CD.ID = CARGA.CIDADE
							 JOIN ESMALTEC.ESM_LOGISTICA_ESTADOS EST
								ON EST.ID = CARGA.ESTADO
							 LEFT JOIN ESMALTEC.ESM_LOGISTICA_CLIENTE CLI
								ON CLI.ID_CLIENTE = CARGA.ID_CLIENTE
							 LEFT JOIN ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA TRA
								ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA
					   WHERE STATUS IN ('LIB')". $extra. "
					ORDER BY CARGA.HORA_ENTRADA ASC)";

                    $dados = $this->db->query($sql)->result_array();

                    return $dados;
    }

	function conta_veiculos_entrada($locais = null)
	{
		if(!empty($locais))
		{
			$extra=" AND LOCAL_CARREGAMENTO = '".$locais."' ";
		}
		else {
			$extra=" ";
		}

		$sql = "SELECT COUNT(*) TOTAL FROM ESMALTEC. ESM_LOGISTICA_CARGA WHERE STATUS IN ('LIB')".$extra;

		$dados_contagem = $this->db->query($sql)->result_array();
		return $dados_contagem[0];
	}

    function veiculos_chegada($locais = null)
    {
		if(!empty($locais))
		{
			$extra=" AND LOCAL_CARREGAMENTO = '".$locais."' ";
		}
		else {
			$extra=" ";
		}

        $sql = "SELECT ID_CARGA,VEICULO,
			   TO_CHAR (REGISTRO_PORTARIA, 'DD/MM HH24:MI') REGISTRO_PORTARIA,
			   TRANSPORTADORA,
			   LOCAL_CARREGAMENTO
		  FROM (SELECT *
          FROM (  SELECT CARGA.ID_CARGA,UPPER (REPLACE (CARGA.PLACA_VEICULO, ' ', ''))
                            AS VEICULO,
                         TO_DATE (
                               TO_CHAR (data_atual, 'DD/MM/YYYY')
                            || ' '
                            || TO_CHAR (hora_chegada),
                            'DD/MM/YYYY HH24:MI:SS')
                            AS REGISTRO_PORTARIA,
                            
                               SUBSTR(CASE
                                  WHEN CARGA.TIPO_TRANSPORTADORA = 'T'
                                  THEN
                                     (SELECT T.NOME_TRANSPORTADORA AS NOME
                                        FROM ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA T
                                       WHERE T.ID_TRANSPORTADORA =
                                                CARGA.ID_TRANSPORTADORA)
                                  WHEN CARGA.TIPO_TRANSPORTADORA = 'C'
                                  THEN
                                     (SELECT CLIENTE.NOME_CLIENTE AS NOME
                                        FROM ESMALTEC.ESM_LOGISTICA_CLIENTE CLIENTE
                                       WHERE CLIENTE.ID_CLIENTE =
                                                CARGA.ID_TRANSPORTADORA)
                               END,0,10)||'...'
                            AS TRANSPORTADORA,
                         LOCAL_CARREGAMENTO
                    FROM ESMALTEC.ESM_LOGISTICA_CARGA CARGA
                         JOIN ESMALTEC.ESM_LOGISTICA_CIDADES CD
                            ON CD.ID = CARGA.CIDADE
                         JOIN ESMALTEC.ESM_LOGISTICA_ESTADOS EST
                            ON EST.ID = CARGA.ESTADO
                         LEFT JOIN ESMALTEC.ESM_LOGISTICA_CLIENTE CLI
                            ON CLI.ID_CLIENTE = CARGA.ID_CLIENTE
                         LEFT JOIN ESMALTEC.ESM_LOGISTICA_TRANSPORTADORA TRA
                            ON TRA.ID_TRANSPORTADORA = CARGA.ID_TRANSPORTADORA
                   WHERE STATUS IN ('LOG', 'EXP', 'AUT')".$extra."
                ORDER BY REGISTRO_PORTARIA ASC))";

                $dados = $this->db->query($sql)->result_array();
                return $dados;
    }

	function conta_veiculos_chegada($locais = null)
	{
		if(!empty($locais))
		{
			$extra=" AND LOCAL_CARREGAMENTO = '".$locais."' ";
		}
		else {
			$extra=" ";
		}

		$sql = "SELECT COUNT(*) TOTAL FROM ESMALTEC. ESM_LOGISTICA_CARGA WHERE STATUS IN ('LOG','EXP','AUT')".$extra;

		$dados_contagem = $this->db->query($sql)->result_array();
		return $dados_contagem[0];
	}
    
}
?>