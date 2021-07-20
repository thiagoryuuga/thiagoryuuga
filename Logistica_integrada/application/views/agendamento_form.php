<script type="text/javascript">

$(function(){
	$("#Limpar").click(function(){
		window.location.href = '<?php echo base_url();?>index.php/importFaltas';
	});
	
	$("#record_length").html("Tabela");
	
	$("#importForm").validate();
	
	if($.browser.msie == true){
		$("#form").css("height",120+'px');
	}
	
	var msn = '<?php if (isset($msn)) echo $msn;?>';
		if(msn == 'msn5'){
			alert('Importação realizada com sucesso.');
			window.location.href = '<?php echo base_url();?>'+'/index.php/importFaltas';
		}else if(msn == 'msn6'){
			alert('Ocorreu um erro na importação, tente novamente.');
			window.location.href = '<?php echo base_url();?>'+'/index.php/importFaltas';
		}	
	$('.readonly').attr('readonly','readonly');
});


</script>

<style type="text/css">
    
	label.error { width:100px;  color: red; margin: 7px 10px; vertical-align: middle; font-size: 10px; }
	p { clear: both; }
	.submit { margin-top: 3em; }
	em { font-weight: bold; padding-right: 1em; vertical-align: top; }

	input.text { 
		border: 1px solid #cecece;		
		background: #fefefe ;		
		width: 194px;
		
	}
	
	.readonly{
		background: #f5f5f5;
	}
	
	#record_length {
		font-size: 20px;
		font-weight: normal;
		line-height: 35px;
		margin: 0 0 0 13px;;
		padding: 0;
	}
			
</style>
										
<form  enctype="multipart/form-data" action="<?php echo base_url() . 'index.php/importAgenda/importar'; ?>" method="post" id="importForm" accept-charset="UTF-8">
											
    <fieldset style="padding:5px">
	
	<div style="padding:25px; background-color:#B4D8EA; height: 120px;">  
		<label>Data do Agendamento </label><br /><input style="width: 120px" name="data_agendamento" id="data_agendamento" type="text" value="" />&nbsp;&nbsp;&nbsp;
																										
		<label>Filial </label>
		<select name="filial" id="filial">
					<option value=""> Selecione...</option>
					<option value="N">Norte</option>	
					<option value="NE">Nordeste</option>	
					<option value="CO">Centro - Oeste</option>	
					<option value="SE">Sudeste</option>
					<option value="S">Sul</option>																				
		</select>			
		<br/><br/>
		<label>Selecione a planilha: </label><br />
		<input class="button white text" style="width: 360px;" name="arquivo" id="arquivo" type="file" class="required"/><br />													
		<br /><br /><button class="button blue normal" type="submit" id="Cadastrar"  name="Cadastrar" value="Cadastrar" />Cadastrar</button>												
														
	</div>
</form>	
								