<?php

class AndroidNotification{
    function __construct()
    {
		
    }

function sendMessage($dados_encontrados, $params_msg)
	{	
		#API_ACCESS_KEY  = senha do servidor gerada na aba cloud-messageing do firebase
		# listando os deviceId para envio das informações
		$API_ACCESS_KEY = 'AAAA3wmOuAk:APA91bHz0ZVgY6J8UKmKyo2wdNzcA0MlrrLG3PQMgPZEsFyb8iUc3L5qgDtZbQ5uiGaMrg2-cubHW9vClpK109SDE1RgQc5m2ZOltb2KBRacfKrwow2osRRa-mlYqOG0gt_3AMKgymr2';
		//$API_ACCESS_KEY = 'AAAAruF10ww:APA91bHYUxoqZWY1dh-dUA8rpx1nh7x95qq1bi3xxlIe6YuZs6n1U3b7N4sonP9ywfcTnuNAmFRgrtWWd5f7ZJEdYUCw3qSecTfD_JqH7q_oWUyMyED7zDH9_9R2zuFkge6QfjUtWdij';
		$registrationIds = trim($dados_encontrados[0][2]);
		#montando os dados
		
			$fields = array
			(
				/*'to'		=> $registrationIds,
				'notification'	=> $params_msg,*/
				'notification' => $params_msg,
				'to' => $registrationIds
				
			);


		$headers = array
			(
				'Authorization: key=' . $API_ACCESS_KEY,
				'Content-Type: application/json',
				"Accept: application/json"
			);			
	#Enviando resposta ao servidor do firebase
	#Para analise e debug da chamada Curl, descomentar as linhas no bloco abaixo
	

		ob_start();
		$out = fopen('php://output', 'w');
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_VERBOSE, false);
		curl_setopt( $ch, CURLOPT_STDERR, $out);
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, trim(json_encode( $fields, JSON_NUMERIC_CHECK ) ));
		$result = curl_exec($ch);
		curl_close( $ch );
		fclose($out);
	#Exibindo resultados do servidor
		//echo $result;		
	$resposta = (json_decode($result, true));
	if($resposta['success'] == 1)
	{
		$result = "ENVIADO";
	}
	else {
		$result = 'FALHA';
	}
	
	return $fields;
	}
	
	
}

