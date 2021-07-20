<div class="display" style="line-height:30px; text-align:right; font-size:20px; font-weight:bold; margin-right:5px;">Total de Veículos: <?php echo $conta_veiculos_docas['TOTAL']?></div>
<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="placas_docas" name="placas_docas">

		<thead>
			<tr style="color:#6B98A8">
			<th>Placa</th>
			<th>Transportadora</th>
			<th>Entrada</th>
			<th>Doca</th>
			<th>Visualizar</th>
			</tr>
		</thead>

	    <tbody style="font-size:11px;">
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
				<td><?php echo $rowPendentes['VEICULO']?></td>
				<td><?php echo $rowPendentes['TRANSPORTADORA']?></td>
				<td><?php echo $rowPendentes['HORA_ENTRADA']?></td>
				<td><?php echo $rowPendentes['DOCA']?></td>
				</td>
				
				<?php
                		$link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";					
						
                 ?>
				 <td style="<?php echo($tyle); ?>"><a class="thickbox" href="<?echo $link?>">[Informações]</a></td>
				</tr>
				<?php } ?>
                </tbody>
				</table>