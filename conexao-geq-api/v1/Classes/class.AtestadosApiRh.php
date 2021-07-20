<?php 

require_once 'class.Oracle.php';
require_once 'class.OTRS.php';
require_once 'class.Usuario.php';

//require_once('../Framework/Framework.php');

define('API_ADDRESS','https://'.$_SERVER['SERVER_NAME'].'/');
define('CURRENT_API_VERSION','v1');
class AtestadosApiRh
{
    

 function __construct() {
		// N.A
	}   

    public function getAtestadosPorMatricula($matricula)
    {     
               
                $sql = "SELECT DISTINCT T.ID ID, 
                T.MATRICULA MATRICULA,
                T.NM_FUNC NM_FUNC, 
                T.COD_CHAMADO COD_CHAMADO,
                T.TIPO TIPO,
                T.NOME_CONTATO NOME_CONTATO, 
                T.TELEFONE_CONTATO TELEFONE_CONTATO, 
                T.CID CID,
                CASE WHEN(UPPER(AC.STATUS)<>'CANCELADO')
                    THEN ''
                    ELSE
                    AC.JUSTIFICATIVA
                    END MOTIVO_CANCELAMENTO, 
                 T.MOTIVO_AFASTAMENTO MOTIVO_AFASTAMENTO, 
                 T.MEDICO_ATENDIMENTO MEDICO_ATENDIMENTO, 
               T.MEDICO_CRM MEDICO_CRM, 
               T.DATA_INICIO DATA_INICIO, 
               T.DATA_FIM DATA_FIM,
                T.DATA_PERICIA DATA_PERICIA, 
                T. DATA_RETORNO DATA_RETORNO,
                T. DATA_REGISTRO DATA_REGISTRO, 
                UPPER(T.PAGAMENTO) PAGAMENTO, 
                T.QTD_TEMPO QTD_TEMPO, 
                T.UNID_TEMPO UNID_TEMPO,
                T.PATH_IMAGEM PATH_IMAGEM,
                nvl(APS.ID,5) COD_STATUS,
                nvl(UPPER(AC.STATUS),'EM ESPERA') STATUS,
                T.NUM_DOCUMENTO NUM_DOCUMENTO,
                T.LAST_UPDATE LAST_UPDATE,
                T.HASH HASH
            FROM(
            SELECT
                D.ID ID, 
                D.MATRICULA MATRICULA,
                FI.NM_FUNC NM_FUNC, 
                nvl(ADC.COD_CHAMADO_OTRS,'Aguardando Chamado') COD_CHAMADO,
               D.TIPO TIPO,
                D.NOME_CONTATO NOME_CONTATO, 
                D.TELEFONE_CONTATO TELEFONE_CONTATO, 
                D.CID CID, 
                D.MOTIVO_AFASTAMENTO MOTIVO_AFASTAMENTO, 
                D.MEDICO_ATENDIMENTO MEDICO_ATENDIMENTO, 
                D.MEDICO_CRM MEDICO_CRM, 
                TO_CHAR(D.DATA_INICIO,'DD/MM/YYYY') DATA_INICIO, 
                TO_CHAR(D.DATA_FIM,'DD/MM/YYYY') DATA_FIM,
                TO_CHAR(D.DATA_PERICIA,'DD/MM/YYYY') DATA_PERICIA, 
                TO_CHAR(D.DATA_RETORNO,'DD/MM/YYYY') DATA_RETORNO,
                TO_CHAR(D.DATA_REGISTRO,'DD/MM/YYYY HH24:MI') DATA_REGISTRO, 
                D.PAGAMENTO PAGAMENTO, 
                D.QTD_TEMPO QTD_TEMPO, 
                D.UNID_TEMPO UNID_TEMPO,
                '".API_ADDRESS.CURRENT_API_VERSION."/atestado/foto/'||D.HASH PATH_IMAGEM,
                D.NUM_DOCUMENTO NUM_DOCUMENTO,
                TO_CHAR(ADC.DATA_ATUALIZACAO,'DD/MM/YYYY') LAST_UPDATE,
                D.HASH HASH
                FROM API_DOCUMENTOS D
            INNER JOIN RH_FUNCIONARIO_IMPORT FI ON D.MATRICULA = FI.CD_ESTAB||FI.MATRICULA
            LEFT JOIN API_DOCUMENTO_CHAMADO ADC ON ADC.ID_APP = D.ID 
            LEFT JOIN API_CHAMADOS AC ON AC.ID = ADC.ID
            WHERE TRIM(D.MATRICULA) = TRIM('".$matricula."')
            AND (ADC.MATRICULA = TRIM('".$matricula."'))
            AND ADC.DATA_GRAVACAO BETWEEN ADD_MONTHS(SYSDATE,-3) AND SYSDATE
            ORDER BY D.ID DESC)  T
            LEFT JOIN API_CHAMADOS AC
            ON AC.COD_CHAMADO = T.COD_CHAMADO
            LEFT JOIN API_STATUS APS
            ON UPPER(APS.NM_STATUS) = UPPER(AC.STATUS)
            order by id desc";
        $db = new Oracle();
         $qtd = $db->executeQueryCount($sql);
		if($qtd == 0)
		{           
            return $dados_resultSet = array();
        }
        
        else{
            $resultSet = $db->executeQuery($sql);
            $dados_resultSet = $db->parseJSONObjectId($resultSet);
            return  json_decode($dados_resultSet);

        }       

    }

    public function getAtestadosPorGerencia($cod_gerencia,$access_token)
    {  
        $db = new Oracle();
       $usuario = new Usuario();
       $matricula = $usuario->getMatriculaGerente($access_token);
       
        $sql = "SELECT T.ID ID, 
                T.MATRICULA MATRICULA,
                T.NM_FUNC NM_FUNC, 
                T.COD_CHAMADO COD_CHAMADO,
                T.TIPO TIPO,
                T.NOME_CONTATO NOME_CONTATO, 
                T.TELEFONE_CONTATO TELEFONE_CONTATO, 
                T.CID CID,
                CASE WHEN(UPPER(AC.STATUS)<>'CANCELADO')
                    THEN ''
                    ELSE
                    AC.JUSTIFICATIVA
                    END MOTIVO_CANCELAMENTO, 
                 T.MOTIVO_AFASTAMENTO MOTIVO_AFASTAMENTO, 
                 T.MEDICO_ATENDIMENTO MEDICO_ATENDIMENTO, 
               T.MEDICO_CRM MEDICO_CRM, 
               T.DATA_INICIO DATA_INICIO, 
               T.DATA_FIM DATA_FIM,
                T.DATA_PERICIA DATA_PERICIA, 
                T. DATA_RETORNO DATA_RETORNO,
                T. DATA_REGISTRO DATA_REGISTRO, 
                UPPER(T.PAGAMENTO) PAGAMENTO, 
                T.QTD_TEMPO QTD_TEMPO, 
                T.UNID_TEMPO UNID_TEMPO,
                T.PATH_IMAGEM PATH_IMAGEM,
                nvl(APS.ID,5) COD_STATUS,
                nvl(UPPER(AC.STATUS),'EM ESPERA') STATUS,
                T.NUM_DOCUMENTO NUM_DOCUMENTO,
                T.LAST_UPDATE LAST_UPDATE,
                T.HASH HASH
            FROM(
            SELECT
                
                D.ID ID, 
                D.MATRICULA MATRICULA,
                FI.NM_FUNC NM_FUNC, 
               ADC.COD_CHAMADO_OTRS COD_CHAMADO,
               D.TIPO TIPO,
                D.NOME_CONTATO NOME_CONTATO, 
                D.TELEFONE_CONTATO TELEFONE_CONTATO, 
                D.CID CID, 
                D.MOTIVO_AFASTAMENTO MOTIVO_AFASTAMENTO, 
                D.MEDICO_ATENDIMENTO MEDICO_ATENDIMENTO, 
                D.MEDICO_CRM MEDICO_CRM, 
                TO_CHAR(D.DATA_INICIO,'DD/MM/YYYY') DATA_INICIO, 
                TO_CHAR(D.DATA_FIM,'DD/MM/YYYY') DATA_FIM,
                TO_CHAR(D.DATA_PERICIA,'DD/MM/YYYY') DATA_PERICIA, 
                TO_CHAR(D.DATA_RETORNO,'DD/MM/YYYY') DATA_RETORNO,
                TO_CHAR(D.DATA_REGISTRO,'DD/MM/YYYY HH24:MI') DATA_REGISTRO, 
                D.PAGAMENTO PAGAMENTO, 
                D.QTD_TEMPO QTD_TEMPO, 
                D.UNID_TEMPO UNID_TEMPO,
                '".API_ADDRESS.CURRENT_API_VERSION."/atestado/foto/'||D.HASH PATH_IMAGEM,
                D.NUM_DOCUMENTO NUM_DOCUMENTO,
                TO_CHAR(ADC.DATA_ATUALIZACAO,'DD/MM/YYYY') LAST_UPDATE,
                D.HASH HASH
                FROM API_DOCUMENTOS D
            INNER JOIN RH_FUNCIONARIO_IMPORT FI ON D.MATRICULA = FI.CD_ESTAB||FI.MATRICULA
            LEFT JOIN API_DOCUMENTO_CHAMADO ADC ON ADC.ID_APP = D.ID 
            LEFT JOIN API_CHAMADOS AC ON AC.ID = ADC.ID
            WHERE FI.CD_CCUSTO ='".$cod_gerencia."'
            and FI.CD_ESTAB||FI.MATRICULA <> '".$matricula."'
            AND ADC.NOTIFICACAO = 'S'
            AND ADC.COD_CHAMADO_OTRS IS not NULL
            AND ADC.DATA_GRAVACAO BETWEEN ADD_MONTHS(SYSDATE,-3) AND SYSDATE
            ORDER BY D.ID DESC)  T
            LEFT JOIN API_CHAMADOS AC
            ON AC.COD_CHAMADO = T.COD_CHAMADO
            LEFT JOIN API_STATUS APS
            ON UPPER(APS.NM_STATUS) = UPPER(AC.STATUS)"." UNION ".
            "SELECT  T.ID ID, 
            T.MATRICULA MATRICULA,
            T.NM_FUNC NM_FUNC, 
            T.COD_CHAMADO COD_CHAMADO,
            T.TIPO TIPO,
            T.NOME_CONTATO NOME_CONTATO, 
            T.TELEFONE_CONTATO TELEFONE_CONTATO, 
            T.CID CID,
            CASE WHEN(UPPER(AC.STATUS)<>'CANCELADO')
                THEN ''
                ELSE
                AC.JUSTIFICATIVA
                END MOTIVO_CANCELAMENTO, 
             T.MOTIVO_AFASTAMENTO MOTIVO_AFASTAMENTO, 
             T.MEDICO_ATENDIMENTO MEDICO_ATENDIMENTO, 
           T.MEDICO_CRM MEDICO_CRM, 
           T.DATA_INICIO DATA_INICIO, 
           T.DATA_FIM DATA_FIM,
            T.DATA_PERICIA DATA_PERICIA, 
            T. DATA_RETORNO DATA_RETORNO,
            T. DATA_REGISTRO DATA_REGISTRO, 
            UPPER(T.PAGAMENTO) PAGAMENTO, 
            T.QTD_TEMPO QTD_TEMPO, 
            T.UNID_TEMPO UNID_TEMPO,
            T.PATH_IMAGEM PATH_IMAGEM,
            nvl(APS.ID,5) COD_STATUS,
            nvl(UPPER(AC.STATUS),'EM ESPERA') STATUS,
            T.NUM_DOCUMENTO NUM_DOCUMENTO,
            T.LAST_UPDATE LAST_UPDATE,
            T.HASH HASH
        FROM(
        SELECT
            D.ID ID, 
            D.MATRICULA MATRICULA,
            FI.NM_FUNC NM_FUNC, 
            nvl(ADC.COD_CHAMADO_OTRS,'Aguardando Chamado') COD_CHAMADO,
           D.TIPO TIPO,
            D.NOME_CONTATO NOME_CONTATO, 
            D.TELEFONE_CONTATO TELEFONE_CONTATO, 
            D.CID CID, 
            D.MOTIVO_AFASTAMENTO MOTIVO_AFASTAMENTO, 
            D.MEDICO_ATENDIMENTO MEDICO_ATENDIMENTO, 
            D.MEDICO_CRM MEDICO_CRM, 
            TO_CHAR(D.DATA_INICIO,'DD/MM/YYYY') DATA_INICIO, 
            TO_CHAR(D.DATA_FIM,'DD/MM/YYYY') DATA_FIM,
            TO_CHAR(D.DATA_PERICIA,'DD/MM/YYYY') DATA_PERICIA, 
            TO_CHAR(D.DATA_RETORNO,'DD/MM/YYYY') DATA_RETORNO,
            TO_CHAR(D.DATA_REGISTRO,'DD/MM/YYYY HH24:MI:SS') DATA_REGISTRO, 
            D.PAGAMENTO PAGAMENTO, 
            D.QTD_TEMPO QTD_TEMPO, 
            D.UNID_TEMPO UNID_TEMPO,
            '".API_ADDRESS.CURRENT_API_VERSION."/atestado/foto/'||D.HASH PATH_IMAGEM,
            D.NUM_DOCUMENTO NUM_DOCUMENTO,
            TO_CHAR(ADC.DATA_ATUALIZACAO,'DD/MM/YYYY') LAST_UPDATE,
            D.HASH HASH
            FROM API_DOCUMENTOS D
        INNER JOIN RH_FUNCIONARIO_IMPORT FI ON D.MATRICULA = FI.CD_ESTAB||FI.MATRICULA
        LEFT JOIN API_DOCUMENTO_CHAMADO ADC ON ADC.ID_APP = D.ID 
        LEFT JOIN API_CHAMADOS AC ON AC.ID = ADC.ID
        WHERE TRIM(D.MATRICULA) = TRIM('".$matricula."')
        AND (ADC.MATRICULA = TRIM('".$matricula."') or ADC.MATRICULA IS NULL)
        AND ADC.DATA_GRAVACAO BETWEEN ADD_MONTHS(SYSDATE,-3) AND SYSDATE
        ORDER BY D.ID DESC)  T
        LEFT JOIN API_CHAMADOS AC
        ON AC.COD_CHAMADO = T.COD_CHAMADO
        LEFT JOIN API_STATUS APS
        ON UPPER(APS.NM_STATUS) = UPPER(AC.STATUS) order by id desc";
           
         $qtd = $db->executeQueryCount($sql);
		if($qtd == 0)
		{
            
            return $dados_resultSet = array();
        }
        
        else{
            $resultSet = $db->executeQuery($sql);
            $dados_resultSet = $db->parseJSONObjectId($resultSet);
            return  json_decode($dados_resultSet);

        }       

    }

    
    
    

	function getNotificacoesPorMatricula($tokenId)
	{
        $db = new Oracle();
        
        $matricula = $this->getMatryculaByToken($tokenId);

        $sql = "select  id_notificacao id,
        id_app id_chamado, 
       nvl(id_status,5) cd_status,
       nvl(nm_status,'EM ESPERA') nm_status,
      api_notification.matricula,
       chamado ticket,
       to_char(hora_envio,'DD/MM/YYYY HH24:MI') data_abertura,
       nvl(nm_status,'EM ESPERA') status,
      '".API_ADDRESS.CURRENT_API_VERSION."/atestado/foto/'||hash path_imagem,
        mensagem informacao
        from API_NOTIFICATION
        left join api_status aps 
        on aps.id = id_status
        left join API_DOCUMENTO_CHAMADO adc ON
        CHAMADO = adc.cod_chamado_otrs
        left join API_DOCUMENTOS d
        on adc.ID_APP = d.id
        where api_notification.MATRICULA = '".$matricula."'
        AND api_notification.NOTIFICACAO = 'S'
        and (adc.MATRICULA = API_NOTIFICATION.MATRICULA or (adc.matricula is null))
        order by id_notificacao desc ";
				                
               
				$qtd = $db->executeQueryCount($sql);

                if($qtd == 0)
		{
          
            return $dados_resultSet = array();
        }
        else{
             $resultSet = $db->executeQuery($sql);
            $dados_resultSet = $db->parseJSONObjectId($resultSet);
            return  json_decode($dados_resultSet);
        }
				

    }
    
    function getNotificacoesPorGerencia($cod_gerencia, $access_token)
	{
        $db = new Oracle();
        
        $usuario = new Usuario();
        $matricula = $usuario->getMatriculaGerente($access_token);
        
       

                            $sql="select id_notificacao id,
                            id_app id_chamado, 
                           nvl(id_status,5) cd_status,
                           nvl(nm_status,'EM ESPERA') nm_status,
                          api_notification.matricula,
                           chamado ticket,
                           to_char(hora_envio,'DD/MM/YYYY HH24:MI') data_abertura,
                           nvl(nm_status,'EM ESPERA') status,
                          '".API_ADDRESS.CURRENT_API_VERSION."/atestado/foto/'||hash path_imagem,
                            mensagem informacao
                            from API_NOTIFICATION
                            left join api_status aps 
                            on aps.id = id_status
                            left join API_DOCUMENTO_CHAMADO adc ON
                            CHAMADO = adc.cod_chamado_otrs
                            left join API_DOCUMENTOS d
                            on adc.ID_APP = d.id
                            where api_notification.MATRICULA = '".$matricula."'
                            AND api_notification.NOTIFICACAO = 'S'
                            and (adc.MATRICULA = API_NOTIFICATION.MATRICULA or (adc.matricula is null))
                            order by id_notificacao desc";


				$qtd = $db->executeQueryCount($sql);

                if($qtd == 0)
		{ 
            return $dados_resultSet = array();
        }
        else{
             $resultSet = $db->executeQuery($sql);
            $dados_resultSet = $db->parseJSONObjectId($resultSet);
            return  json_decode($dados_resultSet);
        }				

	}

    function DesalibitaNotificacoes($lista_id_notificacoes)
    {
        $array_notificacoes = json_decode($lista_id_notificacoes,true);
        if (is_array($array_notificacoes)) {
            $db = new Oracle();
			foreach ( $array_notificacoes as $array_notificacoes) {
                $sql = "UPDATE API_NOTIFICATION SET NOTIFICACAO = 'N' WHERE TRIM(ID_NOTIFICACAO) = TRIM('".$array_notificacoes['id']."')";
                $db->executeUpdate($sql);
            }
            $retorno = array();
            $retorno['mensagem'] ="Registros atualizados";
			
        }
        else{
            $retorno = array();
            $retorno['mensagem'] ="Erro ao processar os registros";
        }
        $json_data[] = $retorno;
        return $json_data;
          
    }

    function HalibitaNotificacoes($id_notificacao)
    {
        $db = new Oracle();
        $sql = "UPDATE API_NOTIFICATION SET NOTIFICACAO = 'S' WHERE ID_NOTIFICACAO = '".$id_notificacao."'";
        $db->executeUpdate($sql);
    }
    

    function consultaImagem($hash)
    {
        $db = new Oracle();
        $sql = "SELECT ARQUIVO ARQUIVO,NUM_DOCUMENTO NUM_DOCUMENTO FROM RHATESTADO.API_DOCUMENTOS WHERE HASH ='".$hash."'";

        $resultSet = $db->executeQueryWithArrayReturn($sql);
            
           return  base64_decode($resultSet[0][0]);
    }   

    public function getProximoIdAtestado()
    {
        $db = new Oracle();
        $sql = 'SELECT API_DOCUMENTOS_SEQ.NEXTVAL FROM DUAL';
        $proximoID = $db->executeQueryWithArrayReturn($sql);
        return($proximoID[0][0]);

    }
   

    public function insereNovo($info)
    {
        
        if(empty($info['matricula']))
        {
           // $data = array('message'=>'Usuario invalido','results'=>array('resource'=>'API User Authentication','code'=>401));
           //      $this->securityApp->response()->status(401); 
        }
        else
        {
            if($info['tipo']=='ATESTADO')
            {
                $tipo = 'ATESTADO';
            }
            else {
                $tipo = utf8_decode('DECLARAÇÃO');
            }
        $sql = "INSERT INTO RHATESTADO.API_DOCUMENTOS VALUES
            (API_DOCUMENTOS_SEQ.NEXTVAL, 
            '".$info['matricula']."',         
            '".$tipo."',         
            '".utf8_decode($info['contato'])."',        
            '".$info['telefone']."',       
            '".$info['cid']."',               
            '".utf8_decode($info['afastamento'])."',      
            '".utf8_decode($info['medico_afastamento'])."', 
            '".$info['medico_crm']."',         
            '".$info['data_inicio']."',        
            '".$info['data_fim']."',           
            '".$info['data_pericia']."',      
            '".$info['data_retorno']."',      
            '".$info['pagamento']."',         
            '".$info['qtd_tempo']."',         
            '".$info['unid_tempo']."',        
            '".$info['num_documento']."',     
            NULL,
            '".$info['hash']."',
            SYSDATE)
            RETURN ID INTO :id";
            
            $db = new Oracle();
            $result = $db->executeUpdateID($sql);
            
                       
            $file = chunk_split($info['documento'],4000,';;;');
            $split_chunk = (explode(';;;',$file));



                for ($i=0; $i < sizeof($split_chunk); $i++ )
                {
                    $sql_grava_clob = " UPDATE RHATESTADO.API_DOCUMENTOS SET ARQUIVO =(SELECT TO_CLOB(ARQUIVO) || TO_CLOB('".$split_chunk[$i]."') FROM 
                    API_DOCUMENTOS WHERE ID = '".$result."') WHERE ID = '".$result."'";
                    
                    $db->executeUpdate($sql_grava_clob);  
                }
           
           // $data[] = array('message'=>'Informacao gravada','results'=>array('resource'=>'Informacoes gravadas','code'=>200));
            
            return $result;
        }       
    }
    

    public function insereChamado($id,$matricula)
    {
        $db = new Oracle();
         $sql ="INSERT INTO API_DOCUMENTO_CHAMADO VALUES (API_DOCUMENTO_CHAMADO_SEQ.NEXTVAL,'".$id."','',SYSDATE,'','S','".$matricula."')";
         $result = $db->executeQuery($sql);  

         $usuario = new Usuario();

         $sql_supervisor="SELECT * FROM (            
            SELECT FI.CD_ESTAB||FI.MATRICULA MATRICULA FROM RH_FUNCIONARIO_IMPORT FI WHERE TRIM(CD_CCUSTO) = 
                     (SELECT TRIM(CD_CCUSTO) CD_CCUSTO FROM RH_FUNCIONARIO_IMPORT FI WHERE FI.CD_ESTAB||FI.MATRICULA = '".$matricula."')
                        AND (FI.NM_FUNCAO like('SUPV%')
                             OR FI.NM_FUNCAO like('SUPERV%')
                             OR FI.NM_FUNCAO like('SUPERVISOR%')
                             OR FI.NM_FUNCAO like('COO%')
                        ))
              WHERE MATRICULA <> '".$matricula."'";

        /* $sql_supervisor = "SELECT FI.CD_ESTAB||FI.MATRICULA MATRICULA FROM RH_FUNCIONARIO_IMPORT FI WHERE TRIM(CD_CCUSTO) = 
         (SELECT TRIM(CD_CCUSTO) CD_CCUSTO FROM RH_FUNCIONARIO_IMPORT FI WHERE FI.CD_ESTAB||FI.MATRICULA = '".$matricula."')
            AND (FI.NM_FUNCAO like('SUPV%')
                 OR FI.NM_FUNCAO like('SUPERV%')
                 OR FI.NM_FUNCAO like('SUPERVISOR%')
                 OR FI.NM_FUNCAO like('COO%')
            )";*/

            $result_supervisor = $db->executeQueryWithArrayReturn($sql_supervisor);

            for($i = 0 ; $i < sizeof($result_supervisor); $i++)
            { 
               
                
                 $sql2 ="INSERT INTO API_DOCUMENTO_CHAMADO VALUES (API_DOCUMENTO_CHAMADO_SEQ.NEXTVAL,'".$id."','',SYSDATE,'','S','".$result_supervisor[$i][0]."')";
                 $result = $db->executeQuery($sql2);
                
            
            }
            
            
            //TESTE
            $mensagem = iconv('UTF-8','ISO-8859-1','Recebemos sua solicitação. Continue acompanhando o seu status pelo aplicativo');
            $insere_api_notification = "INSERT INTO API_NOTIFICATION (ID_NOTIFICACAO, MATRICULA, CHAMADO, MENSAGEM, HORA_ENVIO, CONTADOR, NOTIFICACAO,PENDENTE_ENVIO,ID_STATUS) VALUES
            (API_NOTIFICATION_SEQ.NEXTVAL,'".$matricula."',NULL,'".$mensagem."',SYSDATE,0,'S',1,5)";

            $result = $db->executeQuery($insere_api_notification);
            //TESTE


         $retorno = array();
         $retorno['mensagem_chamado'] = "Chamado gravado no sistema!";
         $json_data[] = $retorno;
         return json_encode($json_data);     

    }

    public function atualizaAparelho($info)
    {
        $db = new Oracle();
        $sql = "UPDATE API_USER SET DEVICETYPE = '".$info['tipo']."', DEVICEID = '".$info['aparelho']."' WHERE TOKENID = '".$info['user']."'";
        
        $result = $db->executeUpdate($sql);

        $sql_busca_id = "SELECT API_USERID FROM API_USER WHERE TRIM(TOKENID) = TRIM('".$info['user']."')";
        $cod_usuario = $db->executeQueryWithArrayReturn($sql_busca_id);

        $sql_atuliza_user_device = "UPDATE API_USERDEVICES SET DEVICEUID = '".$info['aparelho']."' WHERE API_USERID = '".$cod_usuario[0][0]."'";

        $result_update = $db->executeUpdate($sql_atuliza_user_device);        

    }

    public function getMatryculaByToken($tokenId)
    {
        $db = new Oracle();

        $sql = "SELECT USERNAME FROM API_USER WHERE TRIM(TOKENID) = TRIM('".$tokenId."')";
        $matricula = $db->executeQueryWithArrayReturn($sql);
        return $matricula[0][0];
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
}
?>