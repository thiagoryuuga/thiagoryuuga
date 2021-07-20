<?php var_dump($veiculos_cadastrados);?>
<table cellpadding="0" cellspacing="0" width="100%" border="0"  id="veiculos_cadastrados">
	<thead>
		<tr>
			<th><a href="#">PLACA</a></th>
			<th><a href="#">TIPO CADASTRADO</a></th>                                                            											
			<th><a href="#">AÇÃO</a></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($veiculos_cadastrados as $row) {?>
			<tr class="first">
			<td><?php echo $row['PLACA_VEICULO']?></td>
			<td><?php echo $row['CATEGORIA']?></td>											
			<td style="width: 96px;">																			
			<?php $link = base_url().'index.php/cadastrar_carreta/excluir/'.str_replace(' ','_',$row['PLACA_VEICULO'])?>
			<a class="" href="<?php echo $link ?>" title="visualizar">[Excluir]</a>
			</td>
			</tr>
		<?php } ?>										
	</tbody>
</table>