
<script language="javascript">
	function valida_saida(id,operacao) {
		
		if (confirm("Deseja autorizar saida ?")) {	

				
				if(operacao == 'TRA')
				{
					window.location.href="<?php echo base_url()?>index.php/inicio/local_descarregamento/"+id+'/'+operacao;
					  
				}
				else
				{
					window.location.href="<?php echo base_url()?>index.php/inicio/saida_expedicao/"+id;
				}
		}		
	}
	function diferenca_peso(id)
	{
		if(confirm("Confirma a Diferença no Peso?"))
		{
			window.location.href="<?php echo base_url()?>index.php/inicio/diferenca_peso/"+id;
		}
	}
</script>  
																					
<h3 style="margin-top:-15px; padding:10px;">Saidas - Acompanhamento</h3>	
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="liberadas">
	<thead>
		<tr style="color:#6B98A8">
		<th style="text-align: center;font-size:10px;">Data Autoriz. Saída </th>
		<th>Transportadora</th>
		<th>Operação</th>
		<th>Veículo</th>
		<th style="text-align: center;">Placa</th>
		<th>UF</th>																
		<th>Cidade</th>
		<th>Loc. Carga</th>
		<th>Doca</th>
		<th>Motorista</th>
		<th style="text-align: center;">Status</th>
		</tr>
	</thead>
	<tbody>
	<?php if(isset($cargasSaidas)){ foreach ($cargasSaidas as $rowPendentes) {?>	
		<tr>
		<td  style="background-color:#fff" class="center"><?php echo $rowPendentes['HORA_AUTORIZ_SAIDA']?></td>
		<td><?php echo substr($rowPendentes['NOME_TRANSPORTADORA'],0,15)?></td>
		<td><?php echo $rowPendentes['DES_OPERACAO']?></td>
		<td><?php echo $rowPendentes['VEICULO']?></td>
		<td class="center"><?php echo $rowPendentes['PLACA_VEICULO']?></td>
		<td><?php echo utf8_encode($rowPendentes['NOME_ESTADO'])?></td>
		<td><?php echo utf8_encode($rowPendentes['NOME_CIDADE'])?></td>
		<td><?php echo $rowPendentes['LOCAL_CARREGAMENTO']?></td>
		<td><?php echo $rowPendentes['DOCA']?></td>
	<?php $link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";?>
         <td class="center"><a class="thickbox" href="<?echo $link?>">[Informações]</a></td>
		<td class="center">
		<?php if($rowPendentes['STATUS'] == "SAI"){ ?>
		<ul style="list-style-type: none;">
		<!--ALTERAÇÃO DO MÉTODO DE SAÍDA EM VIRTUDE DA DEMANDA DEV 1019 QUE SOLICITA UM ENCAMINHAMENTO DIFERENCIADO PARA VEÍCULOS QUE DEVEM OPERAR COMO TRANSFERENCIA / DESCARREGAMENTO-->

		<?php if($rowPendentes['TIPO_OPERACAO'] != "TRA"){?>
			<li><a href="#" onclick="valida_saida(<?php echo $rowPendentes['ID_CARGA']?>,'<?php echo $rowPendentes['TIPO_OPERACAO']?>')">Confirmar Saída&nbsp;<img src="<?php echo base_url();?>css/layout/site/tables/approved.gif"> </a></li>
			<?php } ?>
			<?php if($rowPendentes['TIPO_OPERACAO'] == "TRA"){
			$link_descarregamento = base_url()."index.php/inicio/local_descarregamento/".$rowPendentes['ID_CARGA']."?height=300&width=400";?>
			<li><a href="<?php echo $link_descarregamento?>" class="thickbox"> Liberar Descarregamento&nbsp;<img style="width:15px;" src="<?php echo base_url();?>css/layout/site/forms/yellow_ico.png"> </a></li>
			<?php } ?>
			<li><a href="#" onclick="diferenca_peso(<?php echo $rowPendentes['ID_CARGA']?>)">Diferença de Peso&nbsp;<img src="<?php echo base_url();?>css/layout/site/tables/action4.gif"> </a></li>
		
		</ul>
		<?php } ?>
		</tr>
		<?php } }?>															
	</tbody>
</table>										