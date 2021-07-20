<style type="text/css">
            
	label { display: block; margin-top: 10px; }
	label.error { width:100px;  color: red; margin: 7px 10px; vertical-align: middle; font-size: 10px }
	p { clear: both; }
	.submit { margin-top: 1em; }
	em { font-weight: bold; padding-right: 1em; vertical-align: top; }

	input.text { 
		border: 1px solid #cecece;
		display: block;
		background: #fefefe ;
		float: left;
		padding: 2px 4px;
		width: 194px;
		margin: 5px 8px 0 0;
		line-height: normal;
	}

	.ui-widget-header{background: #E7F3F8; border: 1px solid #E7F3F8; color:#2E6E9E}
	.ui-widget-content{border: 0}
	
	<!-- Modal para cadastro --->
	body { font-size: 62.5%; }
	label, input { display:block; }
	
	fieldset { padding:0; border:0; margin-top:25px; }
	h1 { font-size: 1.2em; margin: .6em 0; }
	div#users-contain { width: 350px; margin: 20px 0; }
	div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
	div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
	.ui-dialog .ui-state-error { padding: .3em; }
	.validateTips { border: 1px solid red; padding: 0.3em; }
	
</style>

<script>
function editaValores(placa_cavalo,placa_carreta,cod_tipo,id_veiculo)
{
	$('#placa_cavalo').val(placa_cavalo);
	$('#placa_carreta').val(placa_carreta);
	$('#id_veiculo').val(id_veiculo);
	$('#tipo_veiculo').val(cod_tipo);
	
}
</script>


<div style="padding:1%">
	<form id="formCarreta" style="color:#6B98A8" class="search_form general_form" action="<?php echo base_url();?>index.php/cadastrar_carreta/cadastra_carreta" method="post">
		<?php if(!empty($msg_cadastro)){ ?>
		<ul class="system_messages">
		     <?php if($color_tag == 'green')
		     { ?>
		       <li class="green"><span class="ico"></span><strong class="system_title"><?php echo $msg_cadastro;?></strong></li>
		     <?php }
		     if($color_tag == 'yellow')
		     { ?>
		       <li class="yellow"><span class="ico"></span><strong class="system_title"><?php echo $msg_cadastro;?></strong></li>
		     <?php } ?>
			
		</ul>
	<?php } ?>
		<label style="width:190px;">Tipo do veículo</label>
			<select name="VEICULO" id="tipo_veiculo" class="input_wrapper">
				<option name="C_BAU" value="C_BAU" <?php echo @$var = $row['VEICULO'] == "C_BAU" ? " selected=\"selected\" " : "" ;?>>Carreta baú</option>
				<option name="C_BAU_REB" value="C_BAU_REB" <?php echo @$var = $row['VEICULO'] == "C_BAU_REB" ? " selected=\"selected\" " : "" ;?>>Carreta baú rebaixada</option>
				<option name="GRANELEIRA" value="GRANELEIRA" <?php echo @$var = $row['VEICULO'] == "GRANELEIRA" ? " selected=\"selected\" " : "" ;?>>Carreta graneleira</option>
				<option name="SIDER" value="SIDER" <?php echo @$var = $row['VEICULO'] == "SIDER" ? " selected=\"selected\" " : "" ;?>>Carreta sider</option>																
				<option name="C_TRUCK" value="C_TRUCK" <?php echo @$var = $row['VEICULO'] == "C_TRUCK" ? " selected=\"selected\" " : "" ;?>>Truck baú</option>
				<option name="CONTAINER_20" value="CONTAINER_20" <?php echo @$var = $row['VEICULO'] == "CONTAINER_20" ? " selected=\"selected\" " : "" ;?>>Container 20”</option>
				<option name="CONTAINER_40" value="CONTAINER_40" <?php echo @$var = $row['VEICULO'] == "CONTAINER_40" ? " selected=\"selected\" " : "" ;?>>Container 40”</option>
				<option name="IVECO" value="IVECO" <?php echo @$var = $row['VEICULO'] == "IVECO" ? " selected=\"selected\" " : "" ;?>>Iveco</option>
				<option name="FURGOVAN" value="FURGOVAN" <?php echo @$var = $row['VEICULO'] == "FURGOVAN" ? " selected=\"selected\" " : "" ;?>>Furgovan</option>
				<option name="LEVE" value="LEVE" <?php echo @$var = $row['VEICULO'] == "LEVE" ? " selected=\"selected\" " : "" ;?>>Veiculo leve</option>																
			</select>														
		<br /><br />
		<label>Placa do cavalo</label><input style="margin-left:80px;width:100px" class="text" name="PLACA_CAVALO" id="placa_cavalo" type="text" value="<?php echo $var = empty($row['PLACA_CAVALO']) ? "" : $row['PLACA_CAVALO'] ;?>" /><br /><br />
		<label>Placa carreta</label><input style="margin-left:80px;width:100px" class="text" name="PLACA_CARRETA" id="placa_carreta" type="text" value="<?php echo $var = empty($row['PLACA_CARRETA']) ? "" : $row['PLACA_CARRETA'] ;?>" />
		<input style="margin-left:80px;width:100px" class="text" name="ID_VEICULO" id="id_veiculo" type="hidden" value="<?php echo $var = empty($row['ID_VEICULO']) ? "" : $row['ID_VEICULO'] ;?>" /><br /><br />
		
		<input type="submit" class="styled-button" id="sender" style="width: 150px;" value="Cadastar Caminhão" />
</form>

<div class="table_wrapper">
						<div id="table_wrapper_inner" class="table_wrapper_inner">
									<table cellpadding="0" cellspacing="0" width="100%" border="0"  id="veiculos_cadastrados">
								
									<thead>
										<tr>
											<th><a href="#">PLACA_CAVALO</a></th>
											<th><a href="#">PLACA CARRETA</a></th>
											<th><a href="#">TIPO</a></th>                                                            											
											<th><a href="#">AÇÃO</a></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($veiculos_cadastrados as $row) {?>
										<tr class="first">
											<td><?php echo $row['PLACA_CAVALO']?></td>
											<td><?php echo $row['PLACA_CARRETA']?></td>
											<td><?php echo $row['TIPO_VEICULO']?></td>											
											<td style="width: 96px;">																			
											<?php $link = base_url().'index.php/cadastrar_carreta/excluir/'.$row['ID_VEICULO']?>
											<?php $link_editar = base_url().'index.php/cadastrar_carreta/excluir/'.$row['ID_VEICULO']?>
														<a class="" href="#" title="Editar" onclick="editaValores('<?php echo $row['PLACA_CAVALO'] ?>','<?php echo $row['PLACA_CARRETA'] ?>','<?php echo $row['COD_TIPO'] ?>','<?php echo $row['ID_VEICULO'] ?>');">[Editar]</a>
														<a class="" href="<?php echo $link ?>" title="Excluir">[Excluir]</a>
																														
											</td>
											
										</tr>
									<?php } ?>										
									</tbody>
								</table>
							</div>	
</div>