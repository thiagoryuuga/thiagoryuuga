<?php
require_once(ESM_CLASS_PATH.'lib/nusoap.php');
class Wsesmaltec{
	
	public $wsdlCli = "1";
	
	function Wsesmaltec()
	{
	}
	
	function ip($banco, $ambiente){
		if($banco == "mysql" && $ambiente == "prod"){return "192.168.192.6";}
		if($banco == "mysql" && $ambiente == "teste"){return "192.168.192.2";}
		if($banco == "oracle" && $ambiente == "prod"){return "192.168.192.4";}
		if($banco == "oracle" && $ambiente == "teste"){return "192.168.192.10";}
	}
	
	function getWsdl($wsdlCliet){
		if($wsdlCliet == "1"){
			$wsdl = 'http://192.168.192.64:8080/WSEsmaltec/services/Principal?wsdl';
		}
		else{
			$wsdl = 'http://192.168.192.62:8080/WSEsmaltec/services/Principal?wsdl';	
		}
		$client = new soapclient($wsdl, true);
		return $client;
	}
	
	function getPath($wsdlCliet){
		if($wsdlCliet == "1"){
			$path = '//var//lib//tomcat6//webapps//reports//';
		}
		else{	
			$path = '//usr//local//tomcat//webapps//reports//';
		}
		
		return $path;
		
	}
	
	function getHeader($extensao){
		if($extensao == "pdf"){
			return header("Content-Disposition: attachment; filename=arquivo1.pdf");
		}else if($extensao == "xls"){
			return header("Content-Disposition: attachment; filename=arquivo1.xls");
		}else if($extensao == "docx"){
			return header("Content-Disposition: attachment; filename=arquivo1.docx");
		}
	}
	
	function gerarRelatorio($banco,$ambiente, $schema, $usuario, $senha, $nomeRelat, $extensao, $paramRelat){
				
		//die('oia');
		$ip = $this->ip($banco,$ambiente);	
		$path = $this->getPath($this->wsdlCli);	
		$paramConnection = array('servidor:'.$banco.'','ipServidor:'.$ip.'','schema:'.$schema.'','usuario:'.$usuario.'','senha:'.$senha.'');
		
		$paramReport = array('extensao:'.$extensao.'','path:'.$path.'','nameReport:'.$nomeRelat.'.jasper');
		

		//$j = 2;
		for($i=0;$i<count($paramRelat);$i++){
			$paramReport[] = $paramRelat[$i];
		}
		
		//print_r($paramReport);
		
		$client = $this->getWsdl($this->wsdlCli);
		
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
			if ($err){
				//echo $this->wsdlCli;
				if($this->wsdlCli == "1"){
					//echo 'entrei';
					//die();
					$this->wsdlCli = "2";
					return $this->gerarRelatorio($banco,$ambiente, $schema, $usuario, $senha, $nomeRelat, $extensao, $paramRelat);
					
				}else if($this->wsdlCli == "2"){
			  		echo "Erro<pre>".$err."</pre>";
					die();
				}
				//die();
			} else{
				//header( 'Content-Type: text/html; charset=us-ascii' );
				//header("Content-type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");     
				//header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");  
				$this->getHeader($extensao);
				header("Content-Transfer-Encoding: binary");
				header('Expires: 0'); 	
				header('Pragma: no-cache');
				//echo $file;
				echo base64_decode($file);
				//die();
				//return  $file;
				
			}//end_else
		}//end_else
	}
	
	function autenticacao($user, $pwd, $idSistema){
		$client = $this->getWsdl($this->wsdlCli);
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
				//echo $this->wsdlCli;
				if($this->wsdlCli == "1"){
					//echo 'entrei';
					//die();
					$this->wsdlCli = "2";
					return $this->autenticacao($user, $pwd, $idSistema);
					
				}else if($this->wsdlCli == "2"){
			  		echo "Erro<pre>".$err."</pre>";
					die();
				}
				//die();
			} else{
				if($result['autenticacaoReturn']['usuario']['dateExpire'] != "" && $result['autenticacaoReturn']['msgErro'] == ""){
					$result['autenticacaoReturn']['msgErro'] = "Seu Usuario Esta Bloqueado Para o Acesso aos Sistemas Internos.";
				}
				return $result;
			}//end_else
		}//end_else
	}
}
?>