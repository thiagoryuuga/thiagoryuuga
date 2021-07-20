<script language="javascript">




	function valida(id) {
		if (confirm("Deseja autorizar a Saída ?")) {
			window.location.href="<?php echo base_url()?>index.php/inicio/saida/"+id;
		}
	}
</script>  
<link rel="stylesheet" href="<?php echo base_url();?>js/thickbox/thickbox.css">
<script src="<?php echo base_url();?>js/thickbox/thickbox.js"></script>
	<div class="table_wrapper">
		<div id="tab-3" class="table_wrapper_inner">
			<h3>Liberadas - Acompanhamento</h3>
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="liberadas">
					<thead>
						<tr style="color:#6B98A8">
							<th style="text-align: center;font-size:10px;">Entrada na Fábrica</th>
							<th>Transportadora</th>
							<th>Veículo</th>
							<th style="text-align: center;">Placa</th>
							<th>UF</th>																
							<th>Cidade</th>
							<th>Motorista</th>
							<th style="text-align: center;">Status</th>
							<th style="text-align: center;">Visualizar</th>
						</tr>
					</thead>
					 <tbody>
					    <?php foreach ($cargasLiberadas as $rowPendentes) {?>	
						<tr>
							<td  style="background-color:#fff" class="center"><?php echo sqlToDataHora($rowPendentes['HORA_ENTRADA'])?></td>
							<td><?php echo $rowPendentes['NOME_TRANSPORTADORA']?></td>
							<td><?php echo $rowPendentes['VEICULO']?></td>
							<td class="center"><?php echo $rowPendentes['PLACA_VEICULO']?></td>
							<td><?php echo utf8_encode($rowPendentes['NOME_ESTADO'])?></td>
							<td><?php echo utf8_encode($rowPendentes['NOME_CIDADE'])?></td>
							<?php
								$link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";
							?>
							<td class="center"><a class="thickbox" href="<?echo $link?>">[Informações]</a></td>
							<td class="center">
							   <?php if($rowPendentes['STATUS'] == "LIB"){ ?>
									<a href="#" onclick="valida(<?php echo $rowPendentes['ID_CARGA']?>)">Autorizada Saída!&nbsp;<img src="<?php echo base_url();?>css/layout/site/tables/approved.gif"> </a>
								<?php }?>
							</td>
							<td>																			
							   <div class="actions" style="width: 40px;">
								 <ul>
									<li><a class="action1 thickbox" href="<?php echo base_url()?>index.php/expedicao/ver/<?php echo $rowPendentes['ID_CARGA']?>/<?php echo $var = "LIB"?>?height=580&width=1100" title="visualizar"></a></li>
								 </ul>
							  </div>																	
						    </td>
						  </tr>
						 <?php } ?>															
					 </tbody>
					 </table>
					</div>												
				</div>