<?php

class SOA {	


	private $db 	   = NULL;
    private $bloqueio  = NULL; 
    private $mensagem  = "";   
	private $expiracao = "";

	function __construct($bloqueio) {       
        $this->bloqueio = $bloqueio;
		$this->db = new Oracle();
	}

    function mensagem() { 
        return $this->mensagem;
    }
	
   function dataExpiracao() { 
        return $this->expiracao;
    }	

    function verificaLogin($string, $tagname)
    {
        $pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
        preg_match($pattern, $string, $matches);
        
        if ($matches[1] == "OK" ) 
            return true;
        return false;
    }
	
    function verificaDataExpiracao($string, $tagname)
    {
        $pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
        preg_match($pattern, $string, $matches);
        
        if ($matches[1] != "" ) 
            return $matches[1];
        return "";
    }	
 
	function autenticacao($username, $password) {
		
        /* 
		// AUTENTICACAO SEM O SOA
        $this->mensagem = "Ok";
        return true;*/
        
        
		
		$local = getenv('SOA_HOST');

       	$Dominio     = "edsonqueiroz.com.br";
	    $Host        = "$local.$Dominio";
	    $SOPAUrl     = "https://$Host/osb/SN/loginService/v0100?wsdl"; 
	    $SOAPAction  = "http://www.$Dominio/NEG/srvLogin/v0100/wsdl/autenticacaoLogin";        
       
        $ADUser = $username;  
        $ADPass = $password;

        // xml post structure
       	$xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
           xmlns:v01="http://www.geq.com.br/NEG/srvLogin/v0100" 
           xmlns:v1="http://www.geq.com.br/INF/MD/Global/MsgHeader/v1" 
           xmlns:v11="http://www.geq.com.br/INF/MD/Global/Map/v1"
            xmlns:v12="http://www.geq.com.br/INF/MC/Common/v1" 
            xmlns:v13="http://www.geq.com.br/INF/MD/Global/DataTypes/v1" 
            xmlns:wsdl="http://www.geq.com.br/NEG/srvLogin/v0100/wsdl">
                <soapenv:Header>
                <wsse:Security soapenv:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                    <wsse:UsernameToken wsu:Id="UsernameToken-4FE14FF8B285FA87AF15051490664841">
                        <wsse:Username>usrsoadws@geq.com.br</wsse:Username>
                        <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">DjX@i29J$2017</wsse:Password>
                        <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">B+bffEMN71ChEYED1UiibQ==</wsse:Nonce>
                        <wsu:Created>2017-09-11T16:57:46.480Z</wsu:Created>
                    </wsse:UsernameToken>
                </wsse:Security>
                <v1:msgHeader>
                </v1:msgHeader>
               </soapenv:Header>
               <soapenv:Body>
                    <wsdl:GEQLoginRequest>
                       <!--Optional:-->
                        <v01:matricula>'.$ADUser.'</v01:matricula>
                        <!--Optional:-->
                        <v01:senha>'.$ADPass.'</v01:senha>
                    </wsdl:GEQLoginRequest>    
                </soapenv:Body>
            </soapenv:Envelope>';   

           $headers = array(
                        "Host: $Host",
                        "Proxy-Connection: Keep-Alive",
                        "User-Agent: Apache-HttpClient/4.1.1 (java 1.5)",
                        "SOAPAction: $SOAPAction",
                        "Accept-Encoding: gzip,deflate",
                        "Content-Type: text/xml",                                                
                        "Content-length: ".strlen($xml_post_string)
                    ); 

                   
            $url = $SOPAUrl;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);                         
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);  
            curl_setopt($ch, CURLOPT_POST, 1);       
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        

            $result = curl_exec($ch);
            $err = curl_error($ch);
            
           
			

            try
            {
                if ($this->verificaLogin($result,'v01:resultStatus')) {            
                        
						$this->mensagem  = "Ok";
						$this->expiracao = $this->verificaDataExpiracao($result,'v01:pwdExpiration');
						
                        return true;
						
                 } else {
                                    
                    $result_bloq = $this->bloqueio->adiciona($ADUser);
					
                    if ($result_bloq == true) {
                        $this->mensagem = "Ops! Usuário e senha não conferem";                        
                    }            
					
                    if ($result_bloq == false) 
                        $this->mensagem = "Usuario bloqueado";
                        return false;
                    }
					
            } 
            catch(Exception $e)
            {
                 
                $this->mensagem ='Problema de autenticação';
				
                return false;

            }

	}

}
?>