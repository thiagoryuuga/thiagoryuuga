<script type="text/javascript">
$(function()
{
	$('#data_inicio').mask('99/99/9999');
		$('#data_fim').mask('99/99/9999');
});


$(document).ready( function() {
	$("#formPesquisaHistorico").validate({
		// Define as regras
		rules:{			
			data_inicio:{
				required: true
			},
			data_fim:{
				required: true
			}
			
		},
		// Define as mensagens de erro para cada regra
		messages:{
			
			data_inicio:{
				required: "Campo Obrigatório"				
			},
			data_fim:{
				required: "Campo Obrigatório"				
			}
			
			
		}
	});
	
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
</style>
										
<form id="formPesquisaHistorico" action="<?php echo base_url()?>index.php/expedicao/buscaHistorico" method="post">
	<fieldset style="padding:5px">
		<div style="padding:25px; background-color:#B4D8EA;">
			<label>Data inicio: </label><br /><input class="text" name="data_inicio" id="data_inicio" type="text" value="<?php echo $var = empty($_POST['data_inicio']) ? "" : $_POST['data_inicio'] ;?>" />&nbsp;&nbsp;&nbsp;<br>
			<label>Data fim: </label><br /><input class="text" name="data_fim" id="data_fim" type="text" value="<?php echo $var = empty($_POST['data_fim']) ? "" : $_POST['data_fim'] ;?>" />&nbsp;&nbsp;&nbsp;<br>
			<label>Placa Carreta: </label><br /><input class="text" name="placa_carreta" id="placa_carreta" type="text" value="<?php echo $var = empty($_POST['placa_carreta']) ? "" : $_POST['placa_carreta'] ;?>" />&nbsp;&nbsp;&nbsp;<br>
			<br /><br />
			<label>Operação: </label>
			<select name="operacao" id="operacao">
				<option value="">Todos</option>
				<option value="ATUALIZACAO" <?php echo $selecionado = @$_POST['operacao'] == "ATUALIZACAO" ? " selected=\"selected\" " : "" ;?>>Atualização de Dados</option>
				<option value="ENTRADA" <?php echo $selecionado = @$_POST['operacao'] == "ENTRADA" ? " selected=\"selected\" " : "" ;?>>Autorização de Entrada</option>	
				<option value="SAIDA" <?php echo $selecionado = @$_POST['operacao'] == "SAIDA" ? " selected=\"selected\" " : ""  ;?>>Autorização de Saída</option>	
				<option value="CANCELADO" <?php echo $selecionado = @$_POST['operacao'] == "CANCELADO" ? " selected=\"selected\" " : ""  ;?>>Cancelamento de Veículo</option>
				<option value="ROMANEIO" <?php echo $selecionado = @$_POST['operacao'] == "ROMANEIO" ? " selected=\"selected\" " : "" ;?>>Emissão de Romaneio</option>
				<option value="CARREGAMENTO" <?php echo $selecionado = @$_POST['operacao'] == "CARREGAMENTO" ? " selected=\"selected\" " : "" ;?>>Inicio de Carregamento</option>	
     			<option value="FISCAL" <?php echo $selecionado = @$_POST['operacao'] == "FISCAL" ? " selected=\"selected\" " : "" ;?>>Liberação Fiscal</option>
			</select>			
			<!--<input type="submit" class="styled-button" style="width: 100px;" value="pesquisar..." name="pesquisar"/>-->
			<input type="submit" class="styled-button" style="width: 110px;" value="Gerar Arquivo" name="xls">
			<input type="hidden" value="true" name="filtro"/>
		</div>
</form>