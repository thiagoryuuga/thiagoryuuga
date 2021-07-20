<h3 style="padding: 10px;">Auditoria de operações</h3>
<form action="<?php echo base_url()?>index.php/expedicao/buscaHistorico" method="post">
	<table cellpadding="0" cellspacing="0" width="100%"  border="0" class="display" id="auditoriaExp">
		<thead>
			<tr style="color:#6B98A8">
				<th style="text-align: center;">Matricula</th>
				<th style="text-align: center;">Usuário</th>
				<th style="text-align: center;">Carreta</th>
				<th style="text-align: center;">Operação</th>
				<th style="text-align: center;">Local</th>
				<th style="text-align: center;">Data</th>
			</tr>
		</thead>
        <tbody>
		<?php foreach ($historicoCargas as $historico) {?>	
			<tr>
				<td style="text-align: center;"><?php echo ($historico['MATRICULA'])?></td>
				<td style="text-align: center;"><?php echo $historico['NOM_FUNCIONARIO']?></td>
				<td style="text-align: center;"><?php echo $historico['PLACA_VEICULO']?></td>
				<td style="text-align: center;"><?php echo $historico['OPERACAO']?></td>
				<td style="text-align: center;"><?php echo $historico['LOCAL_CARREGAMENTO']?></td>
				<td style="text-align: center;"><?php echo $historico['DATA_EXECUCAO']?></td>
			</tr>
			<?php } ?>															
			</tbody>
		</table>
		</form>
											