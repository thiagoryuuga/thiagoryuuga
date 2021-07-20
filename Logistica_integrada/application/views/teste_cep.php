<?php //include 'inicio.inc.php'?>

<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>		
        <div id="dialog-form" title="Cadastrar motorista">	
		<form action="<?php echo base_url();?>index.php/inicio/inserir_motorista" method="post">
			<fieldset>
				<label for="nome_motorista">Nome</label>
				<input type="text" name="nome_motorista" id="nome_motorista" class="text ui-widget-content ui-corner-all" /><br /><br />
				<label for="cpf_motorista">CPF</label>
				<input type="text" name="cpf_motorista" id="cpf_motorista" value="" class="text ui-widget-content ui-corner-all" />
				<input type="hidden" name="novo" id="novo" value="true" class="text ui-widget-content ui-corner-all" />		
				<input type="hidden" name="data_cadastro" id="data_cadastro" value="<?php echo $horario = date("d/m/Y H:i:s");?>" class="text ui-widget-content ui-corner-all" />		
				<input type="submit">
			</fieldset>
		</form>
		</div>		
	</body>
</html>