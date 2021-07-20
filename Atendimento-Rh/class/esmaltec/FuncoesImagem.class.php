<?php

class FuncoesImagem{
	
	public static function calcula_fator_imagem($foto, $largura, $altura){
		
		// configura foto grande
		$new_width =  $largura;
		$new_height = $altura;
		
		list($width, $height) = @getimagesize($foto);
		
		/** Template do Pedrosa **/
		/*
		$str = 'Tamanho da Foto:<br>';
		$str .= 'Largura: '.$width.'<br>';
		$str .= 'Altura: '.$height.'<br>';
		$str .= 'Box da Foto:<br>';
		$str .= 'Largura: '.$new_width.'<br>';
		$str .= 'Altura: '.$new_height.'<br>';
		*/
		//1º Passo - Verificar se altura e largura da foto é maior que o box da foto
		if($width > $new_width && $height > $new_height){
			$fatorLargura = ($width / $new_width);
			$fatorAltura = ($height / $new_height);
			$fator = max(array($fatorLargura, $fatorAltura));
			$fotoProWG = @round($width / $fator);
			$fotoProHG = @round($height / $fator);
			/*
			$str .= 'Fator da Foto:<br>';
			$str .= 'Fator Largura: '.$fatorLargura.'<br>';
			$str .= 'Fator Altura: '.$fatorAltura.'<br>';
			$str .= 'Fator : '.$fator.'<br>';
			$str .= 'Foto Nova Largura: '.$fotoProWG.'<br>';
			$str .= 'Foto Nova Altura : '.$fotoProHG.'<br>';
			*/
		}else{
			//2º Passo - Caso contrário verificar se é do mesmo tamanho
			if($width == $new_width && $height == $new_height){
				$fotoProWG = $new_width;
				$fotoProHG = $new_height;
			}else {
				
				//3º Passo - Verfica se a largura da foto é menor que o box
				if($width < $new_width && $height < $new_height){
					$fotoProWG = $width;
					$fotoProHG = $height;
				}else if($width < $new_width && $height >= $new_height){
					$fator = @ceil($height / $new_height);
					$fotoProWG = @round($width / $fator);
					$fotoProHG = @round($height / $fator);
					/*
					$str .= 'Fator da Foto:<br>';
					$str .= 'Fator : '.$fator.'<br>';
					$str .= 'Foto Nova Largura: '.$fotoProWG.'<br>';
					$str .= 'Foto Nova Altura : '.$fotoProHG.'<br>';
					*/
				}else if($width >= $new_width && $height < $new_height){
					$fator = @ceil($width / $new_width);
					$fotoProWG = @round($width / $fator);
					$fotoProHG = @round($height / $fator);
					/*
					$str .= 'Fator da Foto:<br>';
					$str .= 'Fator : '.$fator.'<br>';
					$str .= 'Foto Nova Largura: '.$fotoProWG.'<br>';
					$str .= 'Foto Nova Altura : '.$fotoProHG.'<br>';
					*/
					
				}
			}
		}
		$fotoThumb = "thumb2.php?img=".$foto."&w=".$fotoProWG."&h=".$fotoProHG."&calculaDimensao=0";
		return $fotoThumb;
			
	}
	
	public static function resizeImage($image,$width,$height,$scale) {
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		switch($imageType) {
			case "image/gif":
				$source=imagecreatefromgif($image); 
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image); 
				break;
			case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image); 
				break;
		}
		imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
		
		switch($imageType) {
			case "image/gif":
				imagegif($newImage,$image); 
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				imagejpeg($newImage,$image,90); 
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$image);  
				break;
		}
		
		chmod($image, 0777);
		return $image;
	}
	//You do not need to alter these functions
	public static  function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		switch($imageType) {
			case "image/gif":
				$source=imagecreatefromgif($image); 
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image); 
				break;
			case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image); 
				break;
		}
		imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
		switch($imageType) {
			case "image/gif":
				imagegif($newImage,$thumb_image_name); 
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				imagejpeg($newImage,$thumb_image_name,90); 
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$thumb_image_name);  
				break;
		}
		chmod($thumb_image_name, 0777);
		return $thumb_image_name;
	}
	//You do not need to alter these functions
	public static  function getHeight($image) {
		$size = getimagesize($image);
		$height = $size[1];
		return $height;
	}
	//You do not need to alter these functions
	public static function getWidth($image) {
		$size = getimagesize($image);
		$width = $size[0];
		return $width;
	}	
}

?>