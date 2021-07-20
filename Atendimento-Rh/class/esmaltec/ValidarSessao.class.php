<?php
class ValidarSessao{
	
	
	public static function validaSessao(){
		
		if(isset($_SESSION['autenticado']) && isset($_SESSION['autenticado']) && isset($_SESSION['ultimo_acesso'])){
			//verifica se a variável logado é true
			if($_SESSION['usuario_logado'] == true){
				//senão, calculamos o tempo transcorrido 
				$sessao_dataSalva = $_SESSION["ultimo_acesso"]; 
				$sessao_agora = date("Y-n-j H:i:s"); 
				$sessao_tempo_transcorrido = (strtotime($sessao_agora)-strtotime($sessao_dataSalva)); 
				
				//comparamos o tempo transcorrido 
				if($sessao_tempo_transcorrido >= 1200) { 
					//se passaram 10 minutos ou mais 
					session_destroy(); // destruo a sessão
					session_start();
					$_SESSION['query'] = $_SERVER['REDIRECT_URL'];
					//envio ao usuário à página de autenticação
					$var['erro'] = array('titulo_erro' => 'Inatividade', 'msg_erro' => 'Você precisa fazer autenticação novamente por motivo de inativadade!', 'tipo_erro' => 'warning' );
					return $var;
				}else{
					//$_SESSION['QuErY'] = $_SERVER['QUERY_STRING'];
					$_SESSION["ultimo_acesso"] = $sessao_agora; 	
					return true;
				}
			}else{
				$var['erro'] = array('titulo_erro' => 'Autenticação', 'msg_erro' => 'Você precisa se autenticar novamente!', 'tipo_erro' => 'warning' );
				return $var;
				
			}
		}else{
			$var['erro'] = array('titulo_erro' => 'Invasão', 'msg_erro' => 'Você precisa se autenticar primeiro!', 'tipo_erro' => 'danger' );
			return $var;
			
		}
	}
}
?>