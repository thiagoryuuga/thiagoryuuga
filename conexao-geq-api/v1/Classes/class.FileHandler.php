<?php

require_once 'class.Security.php';
 class FileHandler
 {
	function __construct()
	{
	
	}
	
	function fileConverter($documento)
	{
        $securityAPP = new Security();
        //recebendo o $_FILE que é enviado pela função na pagina index e removendo 1 nivel do array para simplificar o código
        $doc = $documento['documento'];
       
        $tamanho_arquivo_kb = (filesize($doc['tmp_name'])/1024)/1024;
        $tamanho_arquivo_mb = floor($tamanho_arquivo_kb*100) /100;
        if($tamanho_arquivo_mb < 10)
        {
            try
            {
                define(DIRECTORY_SEPARATOR, '/');
                $folder = str_replace('v1/','',getcwd() . DIRECTORY_SEPARATOR . 'uploads'. DIRECTORY_SEPARATOR . basename($doc['name']));
                copy($doc['tmp_name'], $folder);
                $source = imagecreatefromstring(file_get_contents($doc['tmp_name']));
                list($width, $height) = getimagesize($doc['tmp_name']);
                $destination = imagecreatetruecolor($width, $height);
                imagealphablending( $destination, false );
                imagesavealpha( $destination, true );
                $img = imagecopyresampled($destination, $source, 0, 0, 0, 0, $width, $height, $width, $height);
                ob_start();
                imagejpeg($source);
                $data = ob_get_contents();
                ob_end_clean();
                $retorno[] = array();
                $retorno['documento'] = base64_encode($data);
                $retorno['status'] = true;
                $retorno['message'] = 'Arquivo Processado';
                $retorno['results'] = array('resource'=>'OK','code'=>200);
                $securityAPP->setApiUtilizationResponse(json_encode($retorno['results']));
                return $retorno;
                 
            }
            catch(Exception $e)
            {
                //$retorno[] = array('message'=>utf8_encode('Arquivo não processado, favor enviar arquivos de até 10Mb'),'results'=>array('resource'=>'payload too large','code'=>413));
                $retorno[] = array();
                $retorno['documento'] = null;
                $retorno['status'] = false;
                $retorno['message'] = utf8_encode('Arquivo não processado, favor enviar arquivos de até 10Mb');
                $retorno['results'] = array('resource'=>'payload too large','code'=>413);
                $securityAPP->setApiUtilizationResponse(json_encode($retorno));
                return $retorno;

            }
        }    
       
    }
 }
 ?>