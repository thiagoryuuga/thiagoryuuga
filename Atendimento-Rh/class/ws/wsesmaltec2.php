<?php
require_once('nusoap.php');
class Wsesmaltec{
	
	function Wsesmaltec()
	{
		
	}
	
	function ip($banco, $ambiente){
		if($banco == "mysql" && $ambiente == "prod"){return "192.168.192.6";}
		if($banco == "mysql" && $ambiente == "teste"){return "192.168.192.2";}
		if($banco == "oracle" && $ambiente == "prod"){return "192.168.192.4";}
		if($banco == "oracle" && $ambiente == "teste"){return "192.168.192.10";}
	}
	
	function getWsdl($wsdlCli){
		if($wsdlCli == "1"){
			$wsdl = 'http://ws.esmaltecsa.com.br';				
		}
		else{
			//$wsdl = 'http://192.168.192.95:8080/WSEsmaltec/Principal';	
		}
		$client = new soapclient($wsdl, true);
		return $client;
	}
	
	function gerarRelatorio($banco,$ambiente, $schema, $usuario, $senha, $nomeRelat, $extencao, $paramRelat, $wsdlCli = "1"){
				
		$ip = $this->ip($banco,$ambiente);		
		$paramConnection = array('servidor:'.$banco.'','ipServidor:'.$ip.'','schema:'.$schema.'','usuario:'.$usuario.'','senha:'.$senha.'');
		
		$paramReport = array('extensao:'.$extencao.'','path://var//lib//tomcat6//webapps//reports//','nameReport:'.$nomeRelat.'.jasper');
		

		//$j = 2;
		for($i=0;$i<count($paramRelat);$i++){
			$paramReport[] = $paramRelat[$i];
		}
		
		//print_r($paramReport);
		
		$client = $this->getWsdl($wsdlCli);
		
		// verifica se ocorreu erro na criação do objeto
		$err = $client->getError();
		if ($err){
			echo "Erro no construtor<pre>".$err."</pre>";
		}
		// chamada do método SOAP
		$result = $client->call('gerarRelatorio',array('paramConnection'=>$paramConnection, 'paramReport' => $paramReport)); 
		
		$sizeArray = strlen(serialize($result['gerarRelatorioReturn']));
		
		$file = $result['gerarRelatorioReturn'];
			
		// verifica se ocorreu falha na chamada do método
		if ($client->fault){
			echo "Falha<pre>".print_r($result)."</pre>";
		}else{
			// verifica se ocorreu erro
			$err = $client->getError();
			//die();
			if ($err){
				//echo $wsdlCli;
				if($wsdlCli == "1"){
					$this->gerarRelatorio($banco,$ambiente, $schema, $usuario, $senha, $nomeRelat, $extencao, $paramRelat, "2");
				}//else if($wsdlCli == "2"){
			  		echo "Erro<pre>".$err."</pre>";
				//}
				//echo "Erro<pre>".$err."</pre>";
				die();
			} else{
				return $file;
			}//end_else
		}//end_else
	}
	
	function altenticacao($user, $pwd, $idSistema){
		$client = $this->getWsdl();
		// verifica se ocorreu erro na criação do objeto
		$err = $client->getError();
		if ($err){
			echo "Erro no construtor<pre>".$err."</pre>";
		}
		// chamada do método SOAP
		$result = $client->call('autenticacao',array('user'=>$user, 'pwd' => $pwd,'idSistema'=> $idSistema)); 
		// verifica se ocorreu falha na chamada do método
		if ($client->fault){
			echo "Falha<pre>".print_r($result)."</pre>";
		}else{
			// verifica se ocorreu erro
			$err = $client->getError();
			if ($err){
				return "Erro<pre>".$err."</pre>";
			} else{
				return $result;
			}//end_else
		}//end_else
	}
}
?>

