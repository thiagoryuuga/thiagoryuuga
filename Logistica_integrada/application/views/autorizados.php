
<script language="javascript">
	function valida_autorizacao(id) {
		if (confirm("Deseja autorizar entrada ?")) {
			
				window.location.href="<?php echo base_url()?>index.php/inicio/liberar/"+id;		
		}
	}
	function valida_saida(id) {
		if (confirm("Deseja autorizar a saída ?")) {
				window.location.href="<?php echo base_url()?>index.php/inicio/saida/"+id;
			
		}
	}
</script>

	<h3 style="padding: 10px;">Autorizados - Acompanhamento</h3>
		<?php if($this->session->flashdata('flashSucesso')){ ?>
			<ul class="system_messages">
				<li class="green"><span class="ico"></span><strong class="system_title"><?php echo $this->session->flashdata('flashSucesso');?></strong></li>
			</ul>
		<?php } ?>
		<?php if($this->session->flashdata('flashCancelado')){?>															
			<ul class="system_messages">														
				<li class="yellow"><span class="ico"></span><strong class="system_title"></strong><?php echo $this->session->flashdata('flashCancelado');?></li>
			</ul>													
		<?php } ?>	
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="autorizado">
			 <thead>
			 	<tr style="color:#6B98A8">
				 <th style="text-align: center;font-size:10px;">Data da Autorização</th>
				 <th>Transportadora</th>
				 <th>Operação</th>
				 <th>Veículo</th>
				 <th style="text-align: center;">Placa</th>
				 <th>Loc. Carga</th>
				 <th>Doca</th>
				 <th>UF</th>																
				 <th>Cidade</th>
				 <th style="width:160px;text-align: center;">Status</th>
				 <th style="text-align: center;">Motorista</th>
				</tr>
			</thead>
				<tbody>
					<?php foreach ($cargasAutorizadas as $rowAutorizadas) {?>	
					<tr>
						<?php if($rowAutorizadas['LOCAL_CARREGAMENTO'] == 'APA_3_LOG'||
								 $rowAutorizadas['LOCAL_CARREGAMENTO'] == 'APA_4_COOPER'||
								 $rowAutorizadas['LOCAL_CARREGAMENTO'] == 'MB_Serv' ||
								 $rowAutorizadas['LOCAL_CARREGAMENTO'] == 'APA_5_COOPERCARGO')
								 {$tyle = "";} else {$tyle ="";}?>
						<td style="<?php echo ($tyle);?>background-color:#fff" class="center"><?php echo sqlToDataHora($rowAutorizadas['HORA_SAIDA_EXPEDICAO'])?></td>
						<td style="<?php echo ($tyle);?>"><?php echo substr($rowAutorizadas['NOME_TRANSPORTADORA'],0,15)?></td>
						<td style="<?php echo ($tyle);?>"><?php echo $rowAutorizadas['DES_OPERACAO']?></td>
					    <td style="<?php echo ($tyle);?>"><?php echo $rowAutorizadas['VEICULO']?></td>
						<td style="<?php echo ($tyle);?>"class="center"><?php echo $rowAutorizadas['PLACA_VEICULO']?></td>
						<td style="<?php echo ($tyle);?>"><?php echo $rowAutorizadas['LOCAL_CARREGAMENTO']?></td>
						<td style="<?php echo ($tyle);?>"><?php echo $rowAutorizadas['DOCA']?></td>
						<td style="<?php echo ($tyle);?>"><?php echo utf8_encode($rowAutorizadas['NOME_ESTADO'])?></td>
						<td style="<?php echo ($tyle);?>"><?php echo utf8_encode($rowAutorizadas['NOME_CIDADE'])?></td>
						<td class="center">
						<ul style="list-style-type: none;">
							<li>
								<a href="#" onclick="valida_autorizacao(<?php echo $rowAutorizadas['ID_CARGA']?>)">Confirmar Entrada &nbsp;<img src="<?php echo base_url();?>css/layout/site/tables/approved.gif"> </a></li>																	
									<?php $tela_recusa = base_url()."index.php/inicio/recusa_entrada/".$rowAutorizadas['ID_CARGA']."?height=200&width=400"; ?>
							<li><a href="<?php echo($tela_recusa)?>"class="thickbox">Recusar Entrada &nbsp;<img src="<?php echo base_url();?>css/layout/site/tables/action4.gif"> </a></li>
						</ul>
							<?php
								$link = base_url()."index.php/inicio/dados_motorista/".$rowAutorizadas['ID_CARGA']."?height=300&width=400";
							?>
						<td class="center"><a class="thickbox" href="<?echo $link?>">[Informações]</a></td>
						</tr>
						<?php } ?>															
				</tbody>
			</table>