
<script>
	function timeout() {
    setTimeout(function () {
		location.reload();
        // Do Something Here
        // Then recall the parent function to
        // create a recursive loop.
        timeout();
    }, 120000);
}
window.onload = timeout;
</script>

<?php include_once('inicio.inc.php');?>

<form id="local_cargas" method="post"action="<?php echo base_url();?>index.php/painel/index">
<div style="height:35px; width:99%; font-size:15px; padding-top: 12px; padding-left:10px;">

<label>Local de carga: </label>
	<select name="locais">
		<option value="">Escolha</option>
		<option value="APA_1">APA_1</option>
		<option value="APA_2">APA_2</option>
		<option value="BOTIJAO">BOTIJAO</option>
		<option value="APA_CDE">APA_CDE</option>
	</select>
	<input type="submit" name="Filtrar" id="filtrar" value="Filtrar" style="float:right; margin-right:80%;" class="button green">
</div>

<div style:"border-style:solid;">

			
				<div id="chegada" style="float:left; position:relative; width:23%; margin-right:4px; font-size:10px; height:650px; overflow:auto; overflow-x: hidden; border-style:solid; border-color:#E7F3F8">
				<div id="info_chegada" style="font-size:20px; padding-left:4%;">Chegada Total:<?php echo $conta_veiculos_chegada['TOTAL'];?></div>
					<table>
						<thead>
						<tr style="color:#6B98A8;">
						<th>Placa</th>
						<th>Transport.</th>
						<th>Entrada</th>
						<th>Local</th>
						</tr>
						</thead>
						<tbody>
							<?php $tyle=""; ?>
							<?php foreach ($painel_chegada as $rowPendentes) {
							if($rowPendentes['LOCAL_CARREGAMENTO'] =='APA_1'||
								$rowPendentes['LOCAL_CARREGAMENTO'] =='APA_2'||
								$rowPendentes['LOCAL_CARREGAMENTO'] =='BOTIJAO'||
								$rowPendentes['LOCAL_CARREGAMENTO'] =='')
							{
								$title = 'Interno';
							}
							else {
								$title = 'Externo';
							}
							?>
							

									<tr title=<?php echo $title;?>  class="<?php echo($title);?>">
									<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['VEICULO']?></td>
									<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['TRANSPORTADORA']?></td>
									<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['REGISTRO_PORTARIA']?></td>
									<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['LOCAL_CARREGAMENTO'];?></td>				
									
									</tr>
									<?php } ?>
									</tbody>

					</table>
				</div>

				<div id="entrada" style="float:left; position:relative; width:25%; margin-right:4px; font-size:10px; height:650px; overflow:auto; overflow-x: hidden; border-style:solid; border-color:#E7F3F8">
				<div id="info_entrada" style="font-size:20px; padding-left:4%;">Entrada Total: <?php echo $conta_veiculos_entrada['TOTAL'];?></div>
					<table>
						<thead>
						<tr style="color:#6B98A8">
						<th>Placa</th>
						<th>Transport.</th>
						<th>Entrada</th>
						<th>Local</th>
						<th>Doca</th>
						</tr>
						</thead>
						<tbody>
					<?php foreach ($painel_entrada as $rowPendentes) {
					if($rowPendentes['LOCAL_CARREGAMENTO'] =='APA_1'||
						$rowPendentes['LOCAL_CARREGAMENTO'] =='APA_2'||
						$rowPendentes['LOCAL_CARREGAMENTO'] =='BOTIJAO')
					{
						$title = 'Interno';
					}
					else {
						$title = 'Externo';
					}?>	

				<tr class="<?php echo $title;?>">
					<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['VEICULO']?></td>
					<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['TRANSPORTADORA']?></td>
					<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['HORA_ENTRADA']?></td>
					<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['LOCAL_CARREGAMENTO']?></td>
					<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['DOCA']?></td>
				</tr>
				<?php } ?>
                </tbody>
				</table>
				</div>
				
				<div id="docas" style="float:left; width:25%; height:650px; font-size:10px; overflow:auto; overflow-x: hidden; border-style:solid;  border-color:#E7F3F8">
				<div id="info_docas" style="font-size:20px; padding-left:4%;">Docas Total: <?php echo $conta_veiculos_docas['TOTAL'];?></div>
					<table>
						<thead>
						<tr style="color:#6B98A8">
						<th>Placa</th>
						<th>Transport.</th>
						<th>Entrada</th>
						<th>Local</th>
						<th>Doca</th>
						</tr>
						</thead>
		
						<tbody>
						<?php $tyle=""; ?>
						<?php foreach ($painel_docas as $rowPendentes) {
							if($rowPendentes['LOCAL_CARREGAMENTO'] =='APA_1'||
							$rowPendentes['LOCAL_CARREGAMENTO'] =='APA_2'||
							$rowPendentes['LOCAL_CARREGAMENTO'] =='BOTIJAO')
							{
								$title = 'Interno';
							}
							else {
								$title = 'Externo';
							}
							
						?>
						<tr title=<?php echo $title;?> class="<?php echo($title);?>">
						<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['VEICULO']?></td>
						<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['TRANSPORTADORA']?></td>
						<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['HORA_ENTRADA']?></td>
						<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['LOCAL_CARREGAMENTO']?></td>
						<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['DOCA']?></td>				 
						</tr>
						<?php } ?>
                	</tbody>
					</table>


				</div>				
				<div id="saida" style="float:left;width:24%; margin-right:4px; font-size:10px; height:650px; overflow:auto; overflow-x: hidden;  border-style:solid; border-color:#E7F3F8">
				<div id="info_saida" style="font-size:20px; padding-left:4%;">Saída Total:<?php echo $conta_veiculos_saida['TOTAL'];?></div>
					<table>
						<thead>
							<tr style="color:#6B98A8">
							<th>Placa</th>
							<th>Transport.</th>
							<th>Local</th>
							<th>Saída</th>
						</tr>
					</thead>

					 <tbody>
        
		<?php foreach ($painel_saida as $rowPendentes) {			
			$style="";		
			$title = $rowPendentes['INFORMACOES_FISCAIS'];
			
			switch($rowPendentes["STATUS"]) {		 
		 case 'SAI':			
			if ($rowPendentes["TEMP_SAI"] >= 30 && $rowPendentes["TEMP_SAI"] <= 60) {
				$style = "externo_rom_amarelo";
			}else if ($rowPendentes["TEMP_SAI"] > 60) {
				$style = "externo_rom_vermelho";
			} else {
				$style = "externo_rom_verde";
			}	 
		 break;
		 
		 case 'ROM':	 	
			
			if ($rowPendentes["TEMPO_ROM"] >= 30 && $rowPendentes["TEMPO_ROM"] <= 60) {
				$style = "externo_rom_amarelo";
			}else if ($rowPendentes["TEMPO_ROM"] > 60) {
				$style = "externo_rom_vermelho";
			} else {
				$style = "externo_rom_verde";
			}	
		 
		 break;		 

		 case 'FIS':
		 
			if ($rowPendentes["TEMP_FIS"] >= 30 && $rowPendentes["TEMP_FIS"] <= 60) {
				$style = "externo_rom_amarelo";
			}else if ($rowPendentes["TEMP_FIS"] > 60) {
				$style = "externo_rom_vermelho";
			} else {
				$style = "externo_rom_verde";
			}			 
		 
		 break;			
		 
		 case 'DIF':		 
		 	$style = "externo_rom_vermelho";		 
		 break; 
	 }

			?>	

				<tr title="<?php echo $title;?>" class="<?php echo $style;?>">
				<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['VEICULO']?></td>
				<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['TRANSPORTADORA']?></td>
				<td style="padding-left:5px; padding-right:5px;"><?php echo $rowPendentes['LOCAL_CARREGAMENTO']?></td>
				<td style="padding-left:5px; padding-right:5px;"><?php echo($rowPendentes['HORA_LIB_FISCAL_FIM']=="" ? ($rowPendentes['REGISTRO_PORTARIA']) : ($rowPendentes['HORA_LIB_FISCAL_FIM']));?></td>
				</tr>
				<?php } ?>
                </tbody>

					</table>
				</div>
				</div>
				
				</form>