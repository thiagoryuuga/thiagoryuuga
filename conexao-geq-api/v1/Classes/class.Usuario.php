<?php
require_once('class.Security.php');
require_once('class.Oracle.php');
require_once('class.Bloqueio.php');

/**Classe de acesso ao banco de dados oracle, que implementa a interface acessodata */
class Usuario
{		
	private $bloqueio  = NULL; 
    public $mensagem  = "Ok";   
	public $expiracao = "";
	public $dbOracle = NULL;

	function __construct()
	{
		$dbOracle = new Oracle();
	}

	function getOracle()
	{
		return $this->dbOracle;
	}

	function mensagem() { 
		return $this->mensagem;	 
	}	
	
	
   function dataExpiracao() { 
        return $this->expiracao;
    }

	function getData() {
		return array('nome'=>$this->user,'pass'=>$this->pass);
	}

	function getDadosFuncionario($matricula)
	{
		$sql ="SELECT   COUNT(FI.MATRICULA) RESULTADOS,  
							FI.CD_EMPRESA CD_EMPRESA,  
							FI.NM_EMPRESA NM_EMPRESA,
							FI.CD_ESTAB CD_ESTAB,      
							FI.NM_ESTAB NM_ESTAB,          
							FILIAL FILIAL,
							FI.MATRICULA MATRICULA,       
							FI.NM_FUNC NM_FUNC,     
							FI.CD_CCUSTO CD_CCUSTO,
							FI.NM_CCUSTO NM_CCUSTO,	
							FI.CD_FUNCAO CD_FUNCAO,    
							FI.NM_FUNCAO NM_FUNCAO,
							SITUACAO SITUACAO,
							CASE WHEN (FI.NM_FUNCAO like('SUPV%')
										OR FI.NM_FUNCAO like('SUPERV%')
										OR FI.NM_FUNCAO like('SUPERVISOR%')
										OR FI.NM_FUNCAO like('COO%')
										/*OR (SUBSTR('".$matricula."',0,3)||SUBSTR('".$matricula."',-6) = '750371415')*/
										/*OR (SUBSTR('".$matricula."',0,3)||SUBSTR('".$matricula."',-6) = '750370663')*/)
							THEN 'GERENCIAL'
							ELSE 'OPERACIONAL' END NIVEL,
							FI.DT_DEMISSAO DT_DEMISSAO,
							FI.DT_IMPORTACAO DT_IMPORTACAO,
							FP.DIAS_AFASTAMENTO AFASTAMENTO_INSS,
							FP.PRAZO_ENVIO PRAZO_ENVIO_ATESTADO,
							FP.PRAZO_ENTREGA PRAZO_ENTREGA_ORIGINAL,
							FP.DIA_FECHAMENTO_1 DIA_FECHAMENTO_1,
							FP.DIA_FECHAMENTO_2 DIA_FECHAMENTO_2,
							TRIM(AU.TOKENID) USERTOKEN,
							CASE WHEN (AU.DEVICEID IS NULL) 
							THEN 'FALSE'
							WHEN (AU.DEVICEID IS NOT NULL)
							THEN  'TRUE' END NOTIFICACOES_HABILITADAS,
						    AU.COUNTLASTLOGIN CONTADOR_LOGIN,
						    '('||substr(TELEFONE,1,2)||') *****-'||substr(TELEFONE,-4) TELEFONE
						FROM RHATESTADO.RH_FUNCIONARIO_IMPORT FI
							INNER JOIN RH_FILIAL F
							ON F.COD_FILIAL = FI.CD_ESTAB
							INNER JOIN RH_EMPRESA E
							ON E.COD_EMPRESA = F.COD_EMPRESA 
							INNER JOIN RH_FILIAL_PARAMETRO FP 
							ON FP.ID_FILIAL = F.ID_FILIAL
							LEFT JOIN API_USER AU
							ON FI.CD_ESTAB||FI.MATRICULA = AU.USERNAME
							INNER JOIN RH_FUNCIONARIO_CONTATO FC
    						ON FC.MATRICULA = AU.USERNAME             
						WHERE FI.MATRICULA = SUBSTR('".$matricula."',-6) AND CD_ESTAB = SUBSTR('".$matricula."',0,3)
						AND E.EMPRESA_ATIVO ='A'
						AND F.ATIVO = 'A'
						AND FI.SITUACAO NOT IN ('DEMITIDO')
						GROUP BY CD_EMPRESA, NM_EMPRESA,  CD_ESTAB,  NM_ESTAB, FILIAL, FI.MATRICULA,
								NM_FUNC,  CD_CCUSTO, NM_CCUSTO, CD_FUNCAO, NM_FUNCAO,  SITUACAO, 
								DT_DEMISSAO, DT_IMPORTACAO, FP.DIAS_AFASTAMENTO, FP.PRAZO_ENVIO, 
								FP.PRAZO_ENTREGA, CASE WHEN (AU.DEVICEID IS NULL) 
								THEN 'FALSE'
								WHEN (AU.DEVICEID IS NOT NULL)
								THEN  'TRUE' END, AU.TOKENID,
								DIA_FECHAMENTO_1,
								DIA_FECHAMENTO_2,
								AU.COUNTLASTLOGIN,
								FC.TELEFONE";
		 $db = new Oracle();
		 $qtd = $db->executeQueryCount($sql);


		if($qtd == 0)
		{
			
			return $resultSet = array();

		}
		else
		{
			$resultSet = $db->executeQuery($sql);
			$dados_resultSet = $db->parseJSONObjectId($resultSet);
			//$dados_resultSet['usuario_bloqueado'] = $this->getBloqueado($matricula);
			return  json_decode($dados_resultSet);
		}
		
		
        
	}

	function getMatriculaGerente($access_token)
	{
		$db = new Oracle();
		$sql_get_supervisor="SELECT USERNAME FROM API_USER WHERE TRIM(TOKENID) = TRIM('".$access_token."')";
		$matricula = $db->executeQueryWithArrayReturn($sql_get_supervisor)[0][0];
		return $matricula;
	}

	function getFeriados($access_token)
	{
	$db = new Oracle();
	$dados_empresa = $this->getEmpresaFilial($access_token);
	$feriados_empresa = array();
	$sql_lista_feriados = "SELECT DISTINCT DT_FERIADO DT_FERIADO, TIPO TIPO FROM RH_FERIADO
	  WHERE ESTABELECIMENTO = '".$dados_empresa[0][0]."' AND FILIAL = '".$dados_empresa[0][1]."' ORDER BY 
	 substr(DT_FERIADO,4,2)asc, 
	  substr(DT_FERIADO,1,2) asc,
        substr(DT_FERIADO,-4) asc";
	 $resultSet = $db->executeQueryWithArrayReturn($sql_lista_feriados);
	// $dados_resultSet = $db->parseJSONObjectId($resultSet);

	for($i=0; $i < sizeof($resultSet); $i++)
	{
		
		$sql_get_descricao ="SELECT substr('".$resultSet[$i][0]."',1,2) DIA_FERIADO,
										substr('".$resultSet[$i][0]."',4,2) MES_FERIADO,
										substr('".$resultSet[$i][0]."',-4) ANO_FERIADO,
										'".$resultSet[$i][0]."' DT_FERIADO,
										ESTABELECIMENTO cd_estab,
										FILIAL filial,
										'F' TIPO, 
										DESC_FERIADO DESC_FERIADO								
										FROM RH_FERIADO 
										WHERE ESTABELECIMENTO = '".$dados_empresa[0][0]."' 
										AND FILIAL = '".$dados_empresa[0][1]."' 
										AND trim(dt_feriado) = '".$resultSet[$i][0]."' and rownum < 2";
										$dados_descricao = $db->executeQueryWithArrayReturn($sql_get_descricao);
										$feriados_empresa[$i]['hash'] = hash('md5',$dados_descricao[0][0].$dados_descricao[0][1].$dados_descricao[0][2].$dados_descricao[0][3]);
										$feriados_empresa[$i]['dia_feriado'] = $dados_descricao[0][0];
										$feriados_empresa[$i]['mes_feriado'] = $dados_descricao[0][1];
										$feriados_empresa[$i]['ano_feriado'] = $dados_descricao[0][2];
										$feriados_empresa[$i]['dt_feriado'] = $dados_descricao[0][3];
										$feriados_empresa[$i]['cd_estab'] = $dados_descricao[0][4];
										$feriados_empresa[$i]['filial'] = $dados_descricao[0][5];
										$feriados_empresa[$i]['desc_feriado'] = utf8_encode($dados_descricao[0][7]);
										
								
								
	}
		 
  	return $feriados_empresa;
	}

	function getEmpresaFilial($access_token)
	{
		$db = new Oracle();
		$sql_get_matricula = "SELECT USERNAME FROM API_USER WHERE TRIM(TOKENID) = TRIM('".$access_token."')";
		$matricula = $db->executeQueryWithArrayReturn($sql_get_matricula)[0][0];

		$sql_get_empresa = "SELECT CD_ESTAB, FILIAL FROM RH_FUNCIONARIO_IMPORT WHERE CD_ESTAB||MATRICULA = '".$matricula."'";
		$empresa_filial = $db->executeQueryWithArrayReturn($sql_get_empresa);
		return $empresa_filial;
	}

	function getMatriculaByHash($value)
	{
		
		$db = new Oracle();
		$sql_get_matricula = "SELECT USERNAME FROM API_USER WHERE TRIM(TOKENID) = TRIM('".$value."')";
		$matricula = $db->executeQueryWithArrayReturn($sql_get_matricula)[0][0];

		return $matricula;
	}

	function getUsuarioPorGerencia($cd_ccusto)
	{
		
		
		 $sql = "SELECT COUNT(FI.MATRICULA) RESULTADOS,  
						FI.CD_EMPRESA CD_EMPRESA,  
						FI.NM_EMPRESA NM_EMPRESA,
						FI.CD_ESTAB CD_ESTAB,      
						FI.NM_ESTAB NM_ESTAB,          
						FILIAL FILIAL,
						FI.MATRICULA MATRICULA,       
						FI.NM_FUNC NM_FUNC,     
						FI.CD_CCUSTO CD_CCUSTO,
						FI.NM_CCUSTO NM_CCUSTO,	
						FI.CD_FUNCAO CD_FUNCAO,    
						FI.NM_FUNCAO NM_FUNCAO,
						SITUACAO SITUACAO,
						CASE WHEN (FI.NM_FUNCAO like('SUPV%')
									OR FI.NM_FUNCAO like('SUPERV%')
									OR FI.NM_FUNCAO like('SUPERVISOR%')
									OR FI.NM_FUNCAO like('COO%')
									/*OR (FI.CD_ESTAB||FI.MATRICULA = '750371415')*/
									/*OR (FI.CD_ESTAB||FI.MATRICULA = '750370663')*/)
						THEN 'GERENCIAL'
						ELSE 'OPERACIONAL' END NIVEL,
						FI.DT_DEMISSAO DT_DEMISSAO,
						FI.DT_IMPORTACAO DT_IMPORTACAO,
						FP.DIAS_AFASTAMENTO AFASTAMENTO_INSS,
						FP.PRAZO_ENVIO PRAZO_ENVIO_ATESTADO,
						FP.PRAZO_ENTREGA PRAZO_ENTREGA_ORIGINAL,
						AU.TOKENID USERTOKEN,
            CASE WHEN (AU.DEVICEID IS NULL) 
            THEN 'FALSE'
            WHEN (AU.DEVICEID IS NOT NULL)
            THEN  'TRUE' END NOTIFICACOES_HABILITADAS,
			AU.COUNTLASTLOGIN CONTADOR_LOGIN
					FROM RHATESTADO.RH_FUNCIONARIO_IMPORT FI
						INNER JOIN RH_FILIAL F
						ON F.COD_FILIAL = FI.CD_ESTAB
						INNER JOIN RH_EMPRESA E
							ON E.COD_EMPRESA = F.COD_EMPRESA 
							INNER JOIN RH_FILIAL_PARAMETRO FP 
							ON FP.ID_FILIAL = F.ID_FILIAL						
          LEFT JOIN API_USER AU
          ON FI.CD_ESTAB||FI.MATRICULA = AU.USERNAME                  
					WHERE FI.CD_CCUSTO = '".$cd_ccusto."'
						  GROUP BY CD_EMPRESA, NM_EMPRESA,  CD_ESTAB,  NM_ESTAB,    FILIAL,  MATRICULA,
								NM_FUNC,  CD_CCUSTO, NM_CCUSTO, CD_FUNCAO, NM_FUNCAO,   SITUACAO, 
								DT_DEMISSAO, DT_IMPORTACAO, FP.DIAS_AFASTAMENTO, FP.PRAZO_ENVIO, 
								FP.PRAZO_ENTREGA,AU.DEVICEID, AU.TOKENID, AU.COUNTLASTLOGIN"; 
		 $db = new Oracle();
		 $qtd = $db->executeQueryCount($sql);


		if($qtd == 0)
		{			
			return $resultSet = array();
		}
		else
		{
			$resultSet = $db->executeQuery($sql);
			$dados_resultSet = $db->parseJSONObjectId($resultSet);			
			return  json_decode($dados_resultSet);
		}
		
	}

	function getComplementoUsuario($matricula)
	{

		$dbOracle = new Oracle();
		$sql = "SELECT FI.NUM_PIS,TO_CHAR(FI.DT_NASCIMENTO,'DD/MM/YYYY') DT_NASCIMENTO,FI.CPF FROM API_TOKEN_RESET TR
		INNER JOIN  RH_FUNCIONARIO_IMPORT FI
		ON TR.MATRICULA = FI.CD_ESTAB||FI.MATRICULA
		WHERE FI.SITUACAO NOT IN ('DEMITIDO') AND TR.MATRICULA = '".$matricula."'";
	
	    $dados = $dbOracle->executeQueryWithArrayReturn($sql)[0];
		return $dados;
	}

	
	function parametrosEmpresa($matricula)
	{
		$sql="SELECT     
				F.COD_FILIAL COD_FILIAL,
				E.NOME_EMPRESA NOME_EMPRESA,  
				F.NOM_FILIAL NOM_FILIAL,
				E.ID_EMPRESA ID_EMPRESA,
				E.COD_EMPRESA COD_EMPRESA,
				FP.DIAS_AFASTAMENTO AFASTAMENTO_INSS,
				FP.PRAZO_ENVIO PRAZO_ENVIO_ATESTADO,
				FP.PRAZO_ENTREGA PRAZO_ENTREGA_ORIGINAL 
		FROM RH_FILIAL F
		INNER JOIN RH_EMPRESA E
		ON E.COD_EMPRESA = F.COD_EMPRESA 
		INNER JOIN RH_FILIAL_PARAMETRO FP 
		ON FP.ID_FILIAL = F.ID_FILIAL 
		WHERE F.COD_FILIAL = SUBSTR('".$matricula."',0,3)";

		

		$db = new Oracle();
		$qtd = $db->executeQueryCount($sql);

		if($qtd == 0)
		{
			return $dados_resultSet = array();
		}
		else 
		{
			
			$resultSet = $db->executeQuery($sql);
			$dados_resultSet = $db->parseJSONObjectId($resultSet);
			return json_decode($dados_resultSet);
		}
		
	}	

	function getProximoIdAparelho()
	{
		$db = new Oracle();
		$sql = "SELECT RHATESTADO.API_USER_SEQ.NEXTVAL FROM DUAL ";
		$result = $db->executeQueryWithArrayReturn($sql);
		return $result[0];
	}

	function registraAparelho($seq,$deviceId,$matricula,$brand,$device_type,$os_version,$app_version,$app_name)
	{
		$db = new Oracle();
		$sql_lista_device = "SELECT DEVICEID FROM RHATESTADO.API_USER WHERE USERNAME = '".$matricula."'";
		$resultSetDevice = $db->executeQueryWithArrayReturn($sql_lista_device);
		if(empty($resultSetDevice))
		{
			$cod_gerado = SHA1($matricula.date('dmYHis').$seq);
			
			$sql = "INSERT INTO RHATESTADO.API_USER (API_USERID,USERNAME,PASSWORD,TOKENID,DEVICEID,DEVICETYPE,DATECREATED) VALUES('".$seq."','".$matricula."','123','".$cod_gerado."','".$deviceId."','".$device_type."',SYSDATE)";
			
			$resultSet = $db->executeQuery($sql);
			
			$sql_info_aparelho = "INSERT INTO RHATESTADO.API_USERDEVICES 
											  (												  
											   API_USERDEVICEID,
											   API_USERID,
											   APPNAME,
											   APPVERSION,
											   DEVICETOKEN,
											   DEVICEUID,
											   DEVICENAME,
											   DEVICEMODEL,
											   DEVICEVERSION,
											   PUSHBADGE,
											   PUSHALERT,
											   PUSHSOUND,
											   DEVELOPMENT,
											   STATUS,
											   CREATED
											   )
									  VALUES (RHATESTADO.API_USERDEVICES_SEQ.NEXTVAL,
											  '".$seq."',
											  '".$app_name."',
											  '".$app_version."',
											  '".$cod_gerado."',
											  '".$deviceId."',
											  ' ',
											  ' ',
											  ' ',
											  0,
											  ' ',
											  ' ',
											  ' ',
											  'A',											  
											  SYSDATE
											  )";

											  
			$resultSetAparelho = $db->executeQuery($sql_info_aparelho);

			//$retorno = array();
			//$retorno['message'] = $deviceId. ' Registrado com sucesso';
			$message = $deviceId. ' Registrado com sucesso';

		}

		else
		{
			$refresh_token = SHA1($matricula.date('dmYHis').$seq);
			$sql_getUserId = "SELECT API_USERID FROM RHATESTADO.API_USER WHERE USERNAME = '".$matricula."'";
			$resultSetId = $db->executeQueryWithArrayReturn($sql_getUserId);
			$sql = "UPDATE RHATESTADO.API_USER SET DEVICEID = '".$deviceId."', DEVICETYPE = '".$device_type."', DATELASTLOGIN = SYSDATE, TOKENID = '".$refresh_token."', COUNTLASTLOGIN = COUNTLASTLOGIN + 1 WHERE USERNAME = '".$matricula."'";
			$resultSet = $db->executeQuery($sql);
		
			$sql_atualiza_device = "UPDATE API_USERDEVICES SET DEVICETOKEN = '".$refresh_token."', DEVICEUID = '".$deviceId."', APPVERSION = '".$app_version."',MODIFIED = SYSDATE WHERE API_USERID = '".$resultSetId[0][0]."'";
			$db->executeUpdate($sql_atualiza_device);
			//$retorno = array();
			//$retorno['message'] = $deviceId. ' Atualizado com sucesso';		
			$message = $deviceId. ' Atualizado com sucesso';

		}
		return $message;		
		//return $retorno;
		 
	}

	function insereBloqueio($matricula)
	{
		$db = new Oracle();
		$sql_busca_nome = "SELECT NM_FUNC nome_funcionario FROM RH_FUNCIONARIO_IMPORT WHERE MATRICULA = SUBSTR('".$matricula."',-6) AND CD_ESTAB =SUBSTR('".$matricula."',0,3) ";
		$resultSet = $db->executeQuery($sql_busca_nome);
		$dados_resultSet = json_decode($db->parseJSONObjectId($resultSet),true);
		
		$sql_conta_bloqueio = "SELECT MATRICULA TOTAL FROM RHATESTADO.RH_BLOQUEIOS WHERE MATRICULA = '".$matricula."'";
		$qtd = $db->executeQueryCount($sql_conta_bloqueio);
		
		if($qtd == 0)
		{
			$sql = "INSERT INTO RHATESTADO.RH_BLOQUEIOS VALUES(RH_BLOQUEIO_SEQ.NEXTVAL,'".$matricula."',SYSDATE,'".$dados_resultSet[0]['nome_funcionario']."')";
			$resultSet2 = $db->executeQuery($sql);

			$sql_bloq = "INSERT INTO RHATESTADO.RH_LOG VALUES (RH_LOG_SEQ.NEXTVAL,'".$matricula."',SYSDATE,' ',SYSDATE,'BLOQUEIO POR SENHA INCORRETA')";

			$resultSet3 = $db->executeQuery($sql_bloq);

			$retorno[] = array();
			$retorno['Info'] = 'Novo bloqueio adicionado';
		   
		}
		else 
		{
			$retorno['Info'] = 'Bloqueio ja existente';
		}
		return (json_encode($retorno));
	}

	function limpaBloqueio($matricula)
	{
		$db = new Oracle();

		$sql_conta_bloqueio = "SELECT CONTADOR FROM RHATESTADO.RH_FUNCIONARIO WHERE MATRICULA = '".$matricula."'";

		$resultSetContagem = $db->executeQueryWithArrayReturn($sql_conta_bloqueio);

		

		$sql = "UPDATE RHATESTADO.RH_FUNCIONARIO SET CONTADOR = 0, ULTIMO_BLOQUEIO = SYSDATE WHERE MATRICULA = '".$matricula."'";
		$resultSet = $db->executeUpdate($sql);

		$sql_limpa_bloqueio = "DELETE FROM RHATESTADO.RH_BLOQUEIOS WHERE MATRICULA = '".$matricula."'";

		$db->executeUpdate($sql);
		$db->executeQuery($sql_limpa_bloqueio);

		if(!empty($resultSetContagem) && $resultSetContagem[0][0]  == 6) //Insere no log apenas se estava bloqueado por excesso de erros
		{
			$sql_insere_msg_desbloqueio = "INSERT INTO RH_LOG VALUES(RH_LOG_SEQ.NEXTVAL,'".$matricula."',SYSDATE,'".$matricula."',SYSDATE,'DESBLOQUEIO POR SENHA CORRETA')";
			$db->executeQuery($sql_insere_msg_desbloqueio);
		}
		
		return true;
	}

	function getBloqueado($matricula)
	{
		$sql = "SELECT matricula,dt_bloqueio FROM RH_BLOQUEIOS WHERE MATRICULA = '".$matricula."'";
		$db = new Oracle();
		 $qtd = $db->executeQueryCount($sql);
		if($qtd == 0)
		{
			$mensagem_bloqueio='Usuario desbloqueado';
		}
		else
		{
			$mensagem_bloqueio = 'usuario bloqueado';
		}
		return $mensagem_bloqueio;
	}

	function getAparelho($matricula)
	{

		$db = new Oracle();
		$sql_lista_device = "SELECT DEVICEID, DEVICETYPE, TOKENID FROM RHATESTADO.API_USER WHERE USERNAME = '".$matricula."'";
		$resultSetDevice = $db->executeQueryWithArrayReturn($sql_lista_device);
		
		return $resultSetDevice;
	}

	function badgeCounter($deviceToken)
	{
		
		//SOMA O VALOR DAS BADGES DE NOTIFICAÇÃO, ATUALIZA O CONTADOR E RETORNA O VALOR PARA EXIBIÇÃO NO ICONE DO APARELHO
		$db = new Oracle();

		$sql_busca_dados = "SELECT COUNT(API_USERDEVICEID) FROM API_USERDEVICES WHERE TRIM(DEVICETOKEN) =TRIM('".$deviceToken."')";
		$contagem = $db->executeQueryWithArrayReturn($sql_busca_dados);

		

		if($contagem[0][0] == 0)
		{
			
			$this->InsereDados($deviceToken);
		}

		$counter = "SELECT (PUSHBADGE+1) FROM API_USERDEVICES WHERE DEVICETOKEN = '".trim($deviceToken)."'";
		$resultSetCounter = $db->executeQueryWithArrayReturn($counter);
		$sql_soma_badge = "UPDATE API_USERDEVICES SET PUSHBADGE = '".$resultSetCounter[0][0]."' WHERE trim(DEVICETOKEN) = '".trim($deviceToken)."'";
		$db->executeUpdate($sql_soma_badge);
		return $resultSetCounter[0][0];
	}

	function InsereDados($deviceToken)
	{
		$db = new Oracle();

		echo $sql_dados = "SELECT API_USERID, DEVICEID, TOKENID FROM API_USER WHERE TRIM(TOKENID) = TRIM('".$deviceToken."')";
		$dados_encontrados = $db->executeQueryWithArrayReturn($sql_dados);
         
		$insertDados = "INSERT INTO RHATESTADO.API_USERDEVICES 
		(												  
		 API_USERDEVICEID,
		 API_USERID,
		 APPNAME,
		 APPVERSION,
		 DEVICETOKEN,
		 DEVICEUID,
		 DEVICENAME,
		 DEVICEMODEL,
		 DEVICEVERSION,
		 PUSHBADGE,
		 PUSHALERT,
		 PUSHSOUND,
		 DEVELOPMENT,
		 STATUS,
		 CREATED
		 )
VALUES (RHATESTADO.API_USERDEVICES_SEQ.NEXTVAL,
		TRIM('".$dados_encontrados[0][0]."'),
		'".iconv('UTF-8','ISO-8859-1','Conexão GEQ')."',
		'1.0.0',
		TRIM('".$dados_encontrados[0][2]."'),
		TRIM('".$dados_encontrados[0][1]."'),
		' ',
		' ',
		' ',
		0,
		' ',
		' ',
		' ',
		'A',											  
		SYSDATE
		)";

		$dados = $db->executeQuery($insertDados);
	}

	function badgeReset($deviceToken)
	{
		$db = new Oracle();
		$sql = "UPDATE API_USERDEVICES SET PUSHBADGE = 0 WHERE trim(DEVICETOKEN) = '".trim($deviceToken)."'";
		$db->executeUpdate($sql);
		$retorno = array('Mensagem' => 'Contador Redefinido');
		return $retorno;
	}
function getIdStatusAtestado($value)
 {
     switch(trim($value))
     {
         case 'Em Espera':
         return 5;
         break;
         case 'Aprovado':
         return 6;
         break;
         case 'Original Pendente':
         return 7;
         break;
         case 'Cancelado':
         return 8;
         break;
         default:
         return 5;
     }
 }

 function getNome($matricula)
 {
	 $db = new Oracle();
	 $sql = "SELECT SUBSTR(FI.NM_FUNC,1,40) FROM RH_FUNCIONARIO_IMPORT FI WHERE FI.SITUACAO NOT IN ('DEMITIDO') AND FI.CD_ESTAB||FI.MATRICULA = '".$matricula."'";
	 $dados = $db->executeQueryWithArrayReturn($sql);
	 return $dados[0][0];
 }

 function valida_usuario($matricula)
 {

	$db = new Oracle();
	$sql = "SELECT COUNT(FI.MATRICULA) FROM RHATESTADO.RH_FUNCIONARIO_IMPORT FI WHERE FI.SITUACAO NOT IN ('DEMITIDO') AND FI.CD_ESTAB||FI.MATRICULA = '".$matricula."'";
	$dados = $db->executeQueryWithArrayReturn($sql);
	if($dados[0][0] > 0)
	{
		return true;
	}
	else{
		return false;
	}
	
	
 }

 function valida_info_usuario($info)
 {

	 $dbOracle = new Oracle();
	 $dados = array();
		$sql_cpf = "SELECT CPF FROM RHATESTADO.RH_FUNCIONARIO_IMPORT WHERE SITUACAO NOT IN ('DEMITIDO') AND CPF ='".$info['num_cpf']."'";
		$dados['cpf'] = $dbOracle->executeQueryCount($sql_cpf);
		
			if($dados['cpf'] > 0)
			{
				$dados['cpf'] = true;
			}
			else
			{
				$dados['cpf'] = false;
			}

	 $sql_pis = "SELECT NUM_PIS FROM RHATESTADO.RH_FUNCIONARIO_IMPORT WHERE SITUACAO NOT IN ('DEMITIDO') AND NUM_PIS ='".$info['num_pis']."'";
	 $dados['num_pis'] = $dbOracle->executeQueryCount($sql_pis);
		if($dados['num_pis'] > 0)
		{
			$dados['num_pis'] = true;
		}
		else
		{
			$dados['num_pis'] = false;
		}
	 $sql_nascimento = "SELECT DT_NASCIMENTO FROM RHATESTADO.RH_FUNCIONARIO_IMPORT WHERE SITUACAO NOT IN ('DEMITIDO') AND CPF ='".$info['num_cpf']."' AND TO_CHAR(DT_NASCIMENTO,'DD/MM/YYYY') ='".$info['dt_nascimento']."'";
	 $dados['nascimento'] = $dbOracle->executeQueryCount($sql_nascimento);
		if($dados['nascimento'] > 0 )
		{
			$dados['nascimento'] = true;
		}
		else
		{
			$dados['nascimento'] = false;
		}

	 return $dados;

	 	
 }

 function registraUsuario($value)
 {
	 $dbOracle = new Oracle();
	 $sql = "SELECT COUNT(CD_ESTAB||MATRICULA) AS FUNCIONARIO,CD_ESTAB||MATRICULA MATRICULA FROM RH_FUNCIONARIO_IMPORT WHERE SITUACAO NOT IN ('DEMITIDO') AND CPF = '".$value['num_cpf']."' AND NUM_PIS = '".$value['num_pis']."' 
	 AND TO_CHAR(DT_NASCIMENTO,'DD/MM/YYYY') ='".$value['dt_nascimento']."' GROUP BY CD_ESTAB||MATRICULA";
	$pesquisa = $dbOracle->executeQueryWithArrayReturn($sql);

	if(empty($pesquisa))
	{
		$pesquisa = 0;
	}
	else{
		$pesquisa = $dbOracle->executeQueryWithArrayReturn($sql)[0];
		$value['matricula'] = $pesquisa[1];
		$this->cadastraDados($value);
	}
	

	if($pesquisa > 0)
	{
		$funcionario[0]=true;
		$funcionario[1]=$pesquisa[1];
		return $funcionario;
	}
	else
	{
		
		$funcionario[0]=false;
		$funcionario[1]=null;
		return $funcionario;
	}	
	
 }

 function atualizaSenha($user,$pass)
 {
	 $dbOracle = new Oracle();
	 $senha = $this->geraSenha($user,$pass);
	
	 $sql = "UPDATE API_USER SET PASSWORD = '".$senha."' WHERE USERNAME = '".$user."'";
	 $dbOracle->executeUpdate($sql);

 }

 function bloqueiaToken($value)
 {
	$dbOracle = new Oracle(); 
	$sql = "UPDATE API_TOKEN_RESET SET IS_VALID = 'N' WHERE MATRICULA = '".$value."' AND IS_VALID ='S' ";
	$update = $dbOracle->executeUpdate($sql);
	
	return true;
}

 function autenticacao($user,$pass)
 {
	 $bloqueio = new Bloqueio();
	 $dbOracle = new Oracle();

	 $senha = $this->geraSenha($user,$pass);
	 $sql = "SELECT USERNAME FROM API_USER WHERE USERNAME = '".$user."' AND PASSWORD ='".$senha."' 
	 and USERNAME not in (SELECT matricula from RH_BLOQUEIOS where matricula = '".$user."')";

	 $usuario = $dbOracle->executeQueryWithArrayReturn($sql);
	
	 
	 if (!empty($usuario))
	 {
		//$this->expiracao = "";		
		return true;		
	 } 
	 else 
	 {
		 $result_bloq = $bloqueio->adiciona($user);
		if ($result_bloq == true)
		{
			$this->mensagem = "Ops! Usuário e senha não conferem";
		}
		if ($result_bloq == false) 
		$this->mensagem = "Usuario bloqueado";
		return false;
	}

 }

 function cadastraDados($array)
 {
	 
	 $dbOracle = new Oracle();
	 $operacao = $this->valida_contatos($array['matricula']);
	 
	 if($operacao =='insert')
	 {
		$sql = "INSERT INTO RH_FUNCIONARIO_CONTATO VALUES ('".$array['matricula']."','".$array['telefone']."','".$array['email']."',SYSDATE, NULL)";
	 }

	 else
	 {
		$sql = "UPDATE RH_FUNCIONARIO_CONTATO SET  TELEFONE ='".$array['telefone']."' , EMAIL ='".$array['email']."', ULTIMA_ALTERACAO = SYSDATE 
		WHERE MATRICULA = '".$array['matricula']."'";
	 }
	 
	 $gravacao = $dbOracle->executeQuery($sql);
      return true;

	 
 }

 function comparaContatos($info)
 {
	 $dbOracle = new Oracle();
	 $sql = "SELECT TELEFONE FROM RH_FUNCIONARIO_CONTATO WHERE MATRICULA ='".$info['matricula']."'";
	 $cadastrado = $dbOracle->executeQueryWithArrayReturn($sql)[0];
	 
	 if(trim($cadastrado[0]) == trim($info['telefone']))
	 {
		 return false;
	 }
	 else
	 {
		 return true;
	 }
 }

 function geraSenha($user,$pass)
 {
	 $dbOracle = new Oracle();
	$keyId = $dbOracle->executeQueryWithArrayReturn("SELECT ACCESS_KEYID FROM API_SYSTEM")[0][0];
	
	$passHash = sha1($pass . $keyId . $user);

	return $passHash;
 }

 
 function valida_contatos($value)
 {
	 $dbOracle = new Oracle();
	 $sql = "SELECT MATRICULA FROM RH_FUNCIONARIO_CONTATO WHERE MATRICULA ='".$value."'";
	 $resultado = $dbOracle->executeQueryCount($sql);

	 if($resultado > 0)
	 {
		 return 'update';
	 }
	 else
	 {
		return 'insert';
	 }

 }

 function getUsuarioPorCPF($value)
 {
	 $extra = "";
	 if(!empty($value['num_pis']))
	 {
		$extra = "CPF = '".$value['num_cpf']."' AND NUM_PIS = '".$value['num_pis']."' 
		          AND TO_CHAR(DT_NASCIMENTO,'DD/MM/YYYY') ='".$value['dt_nascimento']."' AND FI.SITUACAO NOT IN ('DEMITIDO')";
	 }
	 else
	 {
		$extra = "CPF = '".$value['num_cpf']."' AND TO_CHAR(DT_NASCIMENTO,'DD/MM/YYYY') ='".$value['dt_nascimento']."' AND FI.SITUACAO NOT IN ('DEMITIDO')";
	 }
	 $dbOracle = new Oracle();
	
	$sql ="SELECT FI.CD_ESTAB||FI.MATRICULA MATRICULA 
	FROM RH_FUNCIONARIO_IMPORT FI
	INNER JOIN API_USER AU
	ON FI.CD_ESTAB||FI.MATRICULA = AU.USERNAME
	WHERE ".$extra." 
	GROUP BY CD_ESTAB||MATRICULA";
	
	$resultado = $dbOracle->executeQueryWithArrayReturn($sql);
	
	if(!empty($resultado))
	{
		$valores[0] = true;
		$valores[1] = $resultado[0][0];
		return $valores;
	}
	else
	{
		$valores[0] = false;
		$valores[1] = null;
		return $valores;
	}
	
  }

  function getNovoUsuarioPorCPF($value)
  {
	  $extra = "";
	  if(!empty($value['num_pis']))
	  {
		 $extra = " FI.SITUACAO NOT IN ('DEMITIDO') AND CPF = '".$value['num_cpf']."' AND NUM_PIS = '".$value['num_pis']."' 
				   AND TO_CHAR(DT_NASCIMENTO,'DD/MM/YYYY') ='".$value['dt_nascimento']."'";
	  }
	  else
	  {
		 $extra = " FI.SITUACAO NOT IN ('DEMITIDO') AND CPF = '".$value['num_cpf']."' AND TO_CHAR(DT_NASCIMENTO,'DD/MM/YYYY') ='".$value['dt_nascimento']."'";
	  }
	  $dbOracle = new Oracle();
	 
	 $sql ="SELECT FI.CD_ESTAB||FI.MATRICULA MATRICULA 
	 FROM RH_FUNCIONARIO_IMPORT FI 
	 WHERE ".$extra." 
	 GROUP BY CD_ESTAB||MATRICULA";
 
	 
	 $resultado = $dbOracle->executeQueryWithArrayReturn($sql);
	 
	 if(!empty($resultado))
	 {
		 $valores[0] = true;
		 $valores[1] = $resultado[0][0];
		 return $valores;
	 }
	 else
	 {
		 $valores[0] = false;
		 $valores[1] = null;
		 return $valores;
	 }
	 
   }

   function getMatriculaPorCPF($value)
  {
	  
	  $dbOracle = new Oracle();
	 
	 $sql ="SELECT FI.CD_ESTAB||FI.MATRICULA MATRICULA 
	 FROM RH_FUNCIONARIO_IMPORT FI 
	 WHERE FI.SITUACAO NOT IN ('DEMITIDO') AND CPF ='".$value."'
	 GROUP BY CD_ESTAB||MATRICULA";
 
	 
	 $resultado = $dbOracle->executeQueryWithArrayReturn($sql);
	 
	 if(!empty($resultado))
	 {
		 $valores[0] = true;
		 $valores[1] = $resultado[0][0];
		 return $valores;
	 }
	 else
	 {
		 $valores[0] = false;
		 $valores[1] = null;
		 return $valores;
	 }
	 
   }

   function verifica_empresa_ativa($matricula)
   {
		$dbOracle = new Oracle();

	   $sql = "SELECT F.ATIVO FROM RHATESTADO.RH_FILIAL F
				INNER JOIN RHATESTADO.RH_EMPRESA E
				ON F.COD_EMPRESA = E.COD_EMPRESA
				AND F.COD_FILIAL = SUBSTR('".$matricula."',1,3)
				AND F.ATIVO ='A' AND E.EMPRESA_ATIVO = 'A'";

				
				$resultado = $dbOracle->executeQueryCount($sql);

				if($resultado > 0)
				{
					return true;
				}
				else
				{
					return false;
				}

   }

   function getBloqueioPorCPF($value)
   {
	   
	   $dbOracle = new Oracle();
	  
	  $sql = "SELECT FI.CD_ESTAB||FI.MATRICULA MATRICULA 
	  FROM RH_FUNCIONARIO_IMPORT FI
   		INNER JOIN RH_BLOQUEIOS BLOQUEIOS
  		ON FI.CD_ESTAB||FI.MATRICULA = BLOQUEIOS.MATRICULA
	   WHERE FI.SITUACAO NOT IN ('DEMITIDO') AND CPF = '".$value."' 
	  GROUP BY FI.CD_ESTAB||FI.MATRICULA";
	  
	  $resultado = $dbOracle->executeQueryWithArrayReturn($sql);
	  
	  if(!empty($resultado))
	  {
		  $valores[0] = true;
		  $valores[1] = $resultado[0][0];
		  return $valores;
	  }
	  else
	  {
		  $valores[0] = false;
		  $valores[1] = null;
		  return $valores;
	  }
	  
	}

  function busca_contato($dados)
  {
	$extra = "";
	if(!empty($dados['num_pis']))
	{
	   $extra = " FI.SITUACAO NOT IN ('DEMITIDO') AND CPF = '".$dados['num_cpf']."' AND NUM_PIS = '".$dados['num_pis']."' 
				 AND TO_CHAR(DT_NASCIMENTO,'DD/MM/YYYY') ='".$dados['dt_nascimento']."'";
	}
	else
	{
	   $extra = " FI.SITUACAO NOT IN ('DEMITIDO') AND CPF = '".$dados['num_cpf']."' AND TO_CHAR(DT_NASCIMENTO,'DD/MM/YYYY') ='".$dados['dt_nascimento']."'";
	}

	  $dbOracle = new Oracle();
	
	$sql = "SELECT FC.TELEFONE 
		FROM RH_FUNCIONARIO_IMPORT FI
		INNER JOIN API_USER AU
		ON FI.CD_ESTAB||FI.MATRICULA = AU.USERNAME
		INNER JOIN RH_FUNCIONARIO_CONTATO FC
		ON FC.MATRICULA = FI.CD_ESTAB||FI.MATRICULA
		WHERE ".$extra;		

	
	$telefone = $dbOracle->executeQueryWithArrayReturn($sql)[0][0];
	return $telefone;
  }

  function gerarToken($dados)
  {
	$time = time();
		/*timestamp + access_token como salt*/
	$string = $time.$dados['num_cpf'].$dados['dt_nascimento'];
	 /*gerando sha1(hexadecimal)*/
	$hash =(sha1($string));

	/*reduzindo o hexa para 4 caracteres*/
	$reduzido = substr($hash,0,4); 

	/*convertendo o resultado para retornar apenas numeros*/
	$decimais = hexdec($reduzido);
	
	/*aleatório entre 0 e 9*/
	$complemento = rand(0,9); 

	/*caso o resultado de $decimais seja menor que 6 caracteres, 
	a informação é complementada com o random ao final, 
	senão, o random não é adicionado*/
	$normalizado = str_pad($decimais,6,$complemento,STR_PAD_RIGHT);
	
	$this->registra_token($dados,$normalizado);
	
	return $normalizado;
  }

  function gerarTokenComCelular($dados)
  {
	$time = time();
		/*timestamp + access_token como salt*/
	$string = $time.$dados['telefone'];
	 /*gerando sha1(hexadecimal)*/
	$hash =(sha1($string));

	/*reduzindo o hexa para 4 caracteres*/
	$reduzido = substr($hash,0,4); 

	/*convertendo o resultado para retornar apenas numeros*/
	$decimais = hexdec($reduzido);
	
	/*aleatório entre 0 e 100*/
	$complemento = rand(0,100); 

	/*caso o resultado de $decimais seja menor que 6 caracteres, 
	a informação é complementada com o random ao final, 
	senão, o random não é adicionado*/
	$normalizado = str_pad($decimais,6,$complemento,STR_PAD_RIGHT);
	
	$this->registra_token($dados,$normalizado);
	
	return $normalizado;
  }

  function gerarTokenConfirmacao($dados)
  {	  
	$time = time();
	
	/*timestamp + access_token como salt*/
	if(array_key_exists('matricula',$dados))
	{
		$string = $time.$dados['matricula'].$dados['telefone'];
		
	}
	else{
		$string = $time.$dados['num_cpf'].$dados['telefone'];
		
	}
	

	 /*gerando sha1(hexadecimal)*/
	$hash =(sha1($string));

	/*reduzindo o hexa para 4 caracteres*/
	$reduzido = substr($hash,0,4); 

	/*convertendo o resultado para retornar apenas numeros*/
	$decimais = hexdec($reduzido);
	
	/*aleatório entre 0 e 9*/
	$complemento = rand(0,9); 

	/*caso o resultado de $decimais seja menor que 6 caracteres, 
	a informação é complementada com o random ao final, 
	senão, o random não é adicionado*/
	$normalizado = str_pad($decimais,6,$complemento,STR_PAD_RIGHT);
	
	$this->registra_token($dados,$normalizado);
	
	return $normalizado;
  }

  function gerarResetToken($dados)
  {
	
	$time = time();
	
	$complemento = rand(0,100);

	/*timestamp + access_token como salt*/
	if(array_key_exists('num_cpf',$dados))
	{
		$string = $time.$dados['num_cpf'].$complemento;
	}
	else
	{
		$string = $time.$dados['matricula'].$complemento;
	}
	

	 /*gerando sha1(hexadecimal)*/
	$hash = (sha1($string));

	return $hash;
  }

  function gerarResetTokenComCelular($dados)
  {
	
	$time = time();
	
	$complemento = rand(0,100);

	/*timestamp + access_token como salt*/		
	
		$string = $time.$dados['telefone'].$complemento;
	
	

	 /*gerando sha1(hexadecimal)*/
	$hash = (sha1($string));

	return $hash;
  }

  

  function registra_token($dados,$normalizado)
  {	 
	$dbOracle = new Oracle();
	
	if(array_key_exists("matricula",$dados))
	{
		$matricula = array();
		$matricula[1] = $dados['matricula'];
	}
	else
	{
		$matricula = $this->getNovoUsuarioPorCPF($dados);
		
	}
	
	
	$sql_limite = "SELECT to_CHAR((SYSDATE + INTERVAL '8' HOUR),'DD/MM/YYYY HH24:MI:SS') limite FROM DUAL";
	$hora_limite = $dbOracle->executeQueryWithArrayReturn($sql_limite)[0][0];
	
		$sql = "INSERT INTO API_TOKEN_RESET VALUES (API_TOKEN_RESET_SEQ.NEXTVAL,'".$matricula[1]."','".$normalizado."',SYSDATE, 
		TO_DATE('".$hora_limite."','DD/MM/YYYY HH24:MI:SS'),'S')";		
		$dbOracle->executeQuery($sql);
	
	
	return true;		
  }

  function validaToken($value)
  {
	  $dbOracle = new Oracle();
	  $sql = "SELECT MATRICULA, TOKEN FROM API_TOKEN_RESET WHERE DATA_LIMITE > SYSDATE AND TOKEN = '".$value['token_reset']."' AND IS_VALID = 'S'";
	 	  
	  $result = $dbOracle->executeQueryWithArrayReturn($sql);

	  if(!empty($result))
	  {
		  $informacoes[0] = true;
		  $informacoes[1] = $result[0][0];
		  $informacoes[2] = $result[0][1];
	  }
	  else{
		$informacoes[0] = false;
		$informacoes[1] = null;
		$informacoes[2] = null;
	  }

	  return $informacoes;
  }

  function valida_request($value)
  {
	  
	$dbOracle = new Oracle();
	$sql = "SELECT HASH FROM RH_ENVIO_SMS WHERE HASH = '".$value."'";	
	
	$result = $dbOracle->executeQueryWithArrayReturn($sql);

	if(!empty($result))
	{
		
		return true;
	}
	else{
		
		return false;
	}
  }
  

}

?>