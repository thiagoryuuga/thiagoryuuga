<?php print_r($_GET);?>
<form id="formCarreta" style="color:#6B98A8" class="search_form general_form" action="<?php echo base_url();?>index.php/editar_carreta/cadastra_carreta" method="post">
		<?php if(!empty($msg_cadastro)){ ?>
		<ul class="system_messages">
			<li class="green"><span class="ico"></span><strong class="system_title"><?php echo $msg_cadastro;?></strong></li>
		</ul>
	<?php } ?>
		<label style="width:190px;">Tipo do veículo</label>
			<select name="VEICULO" class="input_wrapper">
				<option name="C_BAU" value="C_BAU" <?php echo @$var = $row['VEICULO'] == "C_BAU" ? " selected=\"selected\" " : "" ;?>>Carreta baú</option>
				<option name="C_BAU_REB" value="C_BAU_REB" <?php echo @$var = $row['VEICULO'] == "C_BAU_REB" ? " selected=\"selected\" " : "" ;?>>Carreta baú rebaixada</option>
				<option name="GRANELEIRA" value="GRANELEIRA" <?php echo @$var = $row['VEICULO'] == "GRANELEIRA" ? " selected=\"selected\" " : "" ;?>>Carreta graneleira</option>
				<option name="SIDER" value="SIDER" <?php echo @$var = $row['VEICULO'] == "SIDER" ? " selected=\"selected\" " : "" ;?>>Carreta sider</option>																
				<option name="C_TRUCK" value="C_TRUCK" <?php echo @$var = $row['VEICULO'] == "C_TRUCK" ? " selected=\"selected\" " : "" ;?>>Truck baú</option>
				<option name="CONTAINER_20" value="CONTAINER_20" <?php echo @$var = $row['VEICULO'] == "CONTAINER_20" ? " selected=\"selected\" " : "" ;?>>Container 20º</option>
				<option name="CONTAINER_40" value="CONTAINER_40" <?php echo @$var = $row['VEICULO'] == "CONTAINER_40" ? " selected=\"selected\" " : "" ;?>>Container 40º</option>
				<option name="IVECO" value="IVECO" <?php echo @$var = $row['VEICULO'] == "IVECO" ? " selected=\"selected\" " : "" ;?>>Iveco</option>
				<option name="FURGOVAN" value="FURGOVAN" <?php echo @$var = $row['VEICULO'] == "FURGOVAN" ? " selected=\"selected\" " : "" ;?>>Furgovan</option>
				<option name="LEVE" value="LEVE" <?php echo @$var = $row['VEICULO'] == "LEVE" ? " selected=\"selected\" " : "" ;?>>Veiculo leve</option>																
			</select>														
		<br /><br />
		<!--<label>Placa carreta</label><input style="margin-left:80px;width:100px" class="text" name="PLACA_VEICULO" id="placa_veiculo" type="text" value="<?php echo $var = empty($row['PLACA_VEICULO']) ? "" : $row['PLACA_VEICULO'] ;?>" /><br /><br />-->
		<label>Placa do veículo</label><input style="margin-left:80px;width:100px" class="text" name="placa_veic" id="placa_veic" type="text" value="<?php echo $_GET['placa_veic'];?>" /><br /><br />
		<input type="text" name="id_veiculo" id="id_veiculo" value="<?php echo $_GET['id_veic']?>">
		<input type="text" name="tip_veic" id="tip_veic" value="<?php echo $_GET['tip_veic']?>">
		<input type="submit" class="styled-button" id="sender" style="width: 150px;" value="Cadastar Caminhão" />
</form>

