<?php
function __autoload($classe){
	$dir = ESM_CLASS_PATH;
	$pastas = array('bean','esmaltec','lib','ws');
	$subpastas = array('bean', 'dao');
	foreach ($pastas as $pasta) {
		$dir_a = "$dir{$pasta}/{$classe}.class.php";
		$dir_b = "$dir{$pasta}/{$classe}.php";
		//echo $dir_a.'<br>';
		if(file_exists($dir_a)){
			echo 'Existe: '.$dir_a;
			include $dir_a;
		}else if(file_exists($dir_b)){
			include $dir_b;
			/**
			foreach($subpastas as $subpasta){
				$dir_b = "$dir{$pasta}/{$subpasta}/{$classe}.class.php";
				//echo $dir_b.'<br>';
				if(file_exists($dir_b)){
					//echo $dir_b;
					include_once $dir_b;
				}
			}
			**/
		}
	}
}
?>