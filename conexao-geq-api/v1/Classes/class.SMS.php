<?php
require_once('class.Oracle.php');
class Sms
{
    
    function __construct()
    {

    }

    function montaMensagem($contato,$mensagem,$hash)
    {
        $dbOracle = new Oracle();
        $sql = "INSERT INTO RH_ENVIO_SMS VALUES(RH_ENVIO_SMS_SEQ.NEXTVAL,'".$contato."','".$mensagem."','N',SYSDATE,NULL,'".$hash."')";
        $dbOracle->executeQuery($sql);
        
        return true;
      
       
    }
} 

?>