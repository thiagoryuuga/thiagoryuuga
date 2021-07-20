	<h3 style="padding: 10px;">Pendentes - Acompanhamento</h3>
		<table cellpadding="0" cellspacing="0" width="100%"  border="0" class="display" id="pendentes">
		<thead>
			<tr style="color:#6B98A8">
			<th style="text-align: center;font-size:10px;">Data de Entrada na Portaria</th>
			<th>Transportadora</th>
			<th>Operação</th>
			<th>Veículo</th>
			<th style="text-align: center;">Placa</th>
			<th>UF</th>																
			<th>Cidade</th>
			<th>Loc. Carga</th>
			<th style="text-align: center;">Status</th>
			<th>Motorista</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($cargasPendentes as $rowPendentes) {?>	
			<tr>															
			<td  style="background-color:#fff" class="center"><?php echo substr($rowPendentes['DATA_ATUAL'], 0,10)?> as <?php echo $rowPendentes['HORA_CHEGADA']?></td>
			<td><?php echo $rowPendentes['NOME_TRANSPORTADORA']?></td>
			<td><?php echo $rowPendentes['DES_OPERACAO']?></td>
			<td><?php echo $rowPendentes['VEICULO']?></td>
			<td class="center"><?php echo $rowPendentes['PLACA_VEICULO']?></td>
			<td><?php echo utf8_encode($rowPendentes['NOME_ESTADO'])?></td>
			<td><?php echo utf8_encode($rowPendentes['NOME_CIDADE'])?></td>
			<td><?php echo utf8_encode($rowPendentes['LOCAL_CARREGAMENTO'])?></td>
			<td class="center">
				<?php
					if($rowPendentes['STATUS']=='LOG')
					{
						echo "Aguardando Logística";
					}
					if($rowPendentes['STATUS']=='EXP')
					{
						echo "Aguardando Expedição";
					}
				?>
			</td>
			<?php
				$link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";
			?>
			<td class="center"><a class="thickbox" href="<?echo $link?>">[Informações]</a></td>
			</tr>
			<?php } ?>															
			</tbody>														
			</table>
											