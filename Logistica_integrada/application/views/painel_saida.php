<div class="display" style="line-height:30px; text-align:right; font-size:20px; font-weight:bold; margin-right:5px;">Total de Veículos: <?php echo $conta_veiculos_saida['TOTAL']?></div>
<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="placas_saida" name="placas_saida">

		<thead>
			<tr style="color:#6B98A8">
			<th>Placa</th>
			<th>Transportadora</th>
			<th>Saída</th>
			<th>Visualizar</th>
			</tr>
		</thead>

	    <tbody style="font-size:11px;">
        
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
				<td><?php echo $rowPendentes['VEICULO']?></td>
				<td><?php echo $rowPendentes['TRANSPORTADORA']?></td>
				<td><?php echo($rowPendentes['HORA_LIB_FISCAL_FIM']=="" ? ($rowPendentes['REGISTRO_PORTARIA']) : ($rowPendentes['HORA_LIB_FISCAL_FIM']));?></td>
				</td>
				
				<?php
                		$link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";					
						
                 ?>
				 <td><a class="thickbox" href="<?echo $link?>">[Informações]</a></td>
				</tr>
				<?php } ?>
                </tbody>
				</table>