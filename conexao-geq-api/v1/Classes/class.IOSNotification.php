<?php

class IOSNotification
{
    function __construct()
    {

    }

    function enviaMensagem($dados_encontrados,$params_msg)
    {
       $deviceToken = trim($dados_encontrados[0][2]); // device id
        
        $passphrase = 'master'; // senha do certificado
        //$message = 'Enviado pela Api'; // mensagem
        
         $aContext = array(
             'http' => array(
                 'request_fulluri' => TRUE
                 )
             );
        
        $ctx = stream_context_create( $aContext );
        
        stream_context_set_option($ctx, 'ssl', 'local_cert', '/var/www/certificado/api_rh/development.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        
        //Producao
        //$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        
        // Teste
        $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        
       // echo 'Connected to APNS' . PHP_EOL;
        
        // Payload
        
            $body['aps'] = $params_msg;
        
        // Encode payload JSON
        $payload = json_encode($body, JSON_NUMERIC_CHECK);
        
        // Cria binario
        $msg = chr(0) . pack('n', 32) . pack('H*', trim($deviceToken)) . pack('n', strlen(trim($payload))) . trim($payload);
        
         // Enviar para o servidor e verificar status        

		 
			try {
                $result = fwrite($fp, $msg, strlen($msg));
                //print_r($result);
			} catch (Exception $ex) {
			    sleep(5); //sleep for 5 seconds
                $result = fwrite($fp, $msg, strlen($msg));
                //print_r($result);
			}		 
					 
//           $result = fwrite($fp, $msg, strlen($msg));
		  // fclose($fp);           
        
        if (!$result)
            echo 'Message not delivered' . PHP_EOL;
        else
            echo 'Message successfully delivered' . PHP_EOL;
    }
}
?>