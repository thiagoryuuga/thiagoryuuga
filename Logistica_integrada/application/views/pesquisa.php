<script type="text/javascript">
$(function()
{
	$('#data_pesquisa1').mask('99/99/9999');
		$('#data_pesquisa2').mask('99/99/9999');
});


$(document).ready( function() {
	$("#formPesquisa").validate({
		// Define as regras
		rules:{			
			data_pesquisa1:{
				// campoNome será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true
			}
			
		},
		// Define as mensagens de erro para cada regra
		messages:{
			
			data_pesquisa1:{
				required: "Campo Obrigatório",
				minLength: "O seu nome deve conter, no mínimo, 2 caracteres"
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
										
											<form id="formPesquisa" action="<?php echo base_url()?>index.php/logistica/pesquisar#page" method="post">
											
											
                                                <fieldset style="padding:5px">
												
												<div style="padding:25px; background-color:#B4D8EA;">
													<label>Data inicio: </label><br /><input class="text" name="data_pesquisa1" id="data_pesquisa1" type="text" value="<?php echo $var = empty($_POST['data_pesquisa1']) ? "" : $_POST['data_pesquisa1'] ;?>" />&nbsp;&nbsp;&nbsp;<br>
													<label>Data fim: </label><br /><input class="text" name="data_pesquisa2" id="data_pesquisa2" type="text" value="<?php echo $var = empty($_POST['data_pesquisa2']) ? "" : $_POST['data_pesquisa2'] ;?>" />&nbsp;&nbsp;&nbsp;
													<br /><br />
													
													<label>Status </label>
													<select name="status" id="status">
																<option value="">Todos</option>
																<option value="LOG" <?php echo $selecionado = @$_POST['status'] == "LOG" ? " selected=\"selected\" " : "" ;?>>Logistica</option>	
																<option value="EXP" <?php echo $selecionado = @$_POST['status'] == "EXP" ? " selected=\"selected\" " : "" ;?>>Expedição</option>	
																<option value="AUT" <?php echo $selecionado = @$_POST['status'] == "AUT" ? " selected=\"selected\" " : ""  ;?>>Autorizado</option>	
																<option value="LIB" <?php echo $selecionado = @$_POST['status'] == "LIB" ? " selected=\"selected\" " : ""  ;?>>Liberada Entrada</option>
																<option value="TRA" <?php echo $selecionado = @$_POST['status'] == "TRA" ? " selected=\"selected\" " : "" ;?>>Em Trânsito</option>
																<option value="CAN" <?php echo $selecionado = @$_POST['status'] == "CAN" ? " selected=\"selected\" " : "" ;?>>Cancelados</option>																
																<!--<option value="SAI" <?php echo $selecionado = @$_POST['status'] == "SAI" ? " selected=\"selected\" " : "" ;?>>Saída</option>-->
																
													</select>			
													
													<input type="submit" class="styled-button" style="width: 100px;" value="pesquisar..." name="pesquisar"/>
													<input type="submit" class="styled-button" style="width: 110px;" value="Exportar excel" name="xls">
													
													<input type="hidden" value="true" name="filtro"/>
													
												
												</div>
											</form>