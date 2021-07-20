<script>

$('document').load()
{
	 listaDifPeso();
}

setInterval( function () {
    listaDifPeso();
}, 60000 );
	
	function listaDifPeso()
	{

		$.ajax({ url: "<?php echo site_url('expedicao/listaDifPeso'); ?>",
			data: { dummyPost: 'foo'},
			dataType: "html",
			type: "POST",
			success: function(data){
				
				 $("#dif_peso").fadeOut('fast');
				 $("#dif_peso").find("tbody").empty();
				 $("#dif_peso").find("tbody").append(data);
				 tb_init('a.thickbox');
				 $("#dif_peso").fadeIn('slow');

				
			}
		});
	}
		

	
</script>


<script language="javascript">
	function valida_saida(id) {
		
		if (confirm("Deseja autorizar saida ?")) {
			
				window.location.href="<?php echo base_url()?>index.php/inicio/saida_expedicao/"+id;
			
		}		
		
		
	}
	function diferenca_peso(id)
	{
		if(confirm("Confirma a Diferença no Peso?"))
		{
			window.location.href="<?php echo base_url()?>index.php/inicio/diferenca_peso/"+id;
		}
	}

	function encaminha_saida(id)
	{
		if(confirm("Enviar para lista de saídas?"))
		{
			window.location.href="<?php echo base_url()?>index.php/inicio/encaminha_saida/"+id;
		}
	}
</script> 

																					
													<h3 style="margin-top:-15px; padding:10px;">Saidas - Acompanhamento</h3>	
													<table cellpadding="0" cellspacing="0" border="0" class="display" id="dif_peso">

														<thead>

															<tr style="color:#6B98A8">

																<th style="text-align: center;font-size:10px;">Data de Entrada na Fábrica</th>
																<th>Transportadora</th>
																<th>Operação</th>
																<th>Veículo</th>
																<th style="text-align: center;">Placa</th>
																<th>UF</th>																
																<th>Cidade</th>
																<th>Loc. Carga</th>
																<th>Doca</th>
																<!--<th>Peso (Kg)</th>-->
																<th>Motorista</th>
																<th style="text-align: center;">Status</th>
																

															</tr>

														</thead>


														<tbody>

															<?php if(isset($veiculosDiferencaPeso)){ foreach ($veiculosDiferencaPeso as $rowPendentes) {?>	
															<tr>
																<td  style="background-color:#fff" class="center"><?php echo sqlToDate($rowPendentes['DATA_ATUAL'])?> as <?php echo $rowPendentes['HORA_CHEGADA']?></td>

																<td><?php echo substr($rowPendentes['NOME_TRANSPORTADORA'],0,15)?></td>

																<td><?php echo $rowPendentes['VEICULO']?></td>

																<td class="center"><?php echo $rowPendentes['PLACA_VEICULO']?></td>

																<td><?php echo utf8_encode($rowPendentes['NOME_ESTADO'])?></td>
																
																<td><?php echo utf8_encode($rowPendentes['NOME_CIDADE'])?></td>

																<td><?php echo $rowPendentes['LOCAL_CARREGAMENTO']?></td>

																<td><?php echo $rowPendentes['DOCA']?></td>
																
																<?php
                                                                    $link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";
                                                                ?>
                                                                <td class="center"><a class="thickbox" href="<?echo $link?>">[Informações]</a></td>
																<td class="center">
																<?php if($rowPendentes['STATUS'] == "SAI"){ ?>
																<ul style="list-style-type: none;">
																	<li><a href="#" onclick="valida_saida(<?php echo $rowPendentes['ID_CARGA']?>)">Confirmar Saída&nbsp;<img src="<?php echo base_url();?>css/layout/site/tables/approved.gif"> </a></li>
																	<li><a href="#" onclick="diferenca_peso(<?php echo $rowPendentes['ID_CARGA']?>)">Diferença de Peso&nbsp;<img src="<?php echo base_url();?>css/layout/site/tables/action4.gif"> </a></li>
																	</ul>
																	
																<? }?>

															</tr>
															<?php } }?>															
														</tbody>
														
													</table>


													
												