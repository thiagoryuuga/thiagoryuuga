<script language="javascript">
	function cancelar_carga(id) {
		if (confirm("Deseja cancelar esta carga?")) {
			window.location.href="<?php echo base_url()?>index.php/logistica/cancelar/"+id;		
		}
	}
</script> 

<style type="text/css">       	   
	.ui-widget-header{background: #B4D8EA; border: 1px solid #B4D8EA; color:#2E6E9E;}
		
</style>

 <form action="<?php echo base_url()?>logistica/pesquisar" method="post">
                                                <fieldset style="padding:5px">												
                                                <div class="table_wrapper">
                                              		<div class="table_wrapper_inner" >
                                                    <div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix" style="padding:3px; margin-bottom:3px; font-size:11px; background-color:#B4D8EA;">
                                                    <table cellpadding="0" cellspacing="0" width="100%" border="0"  id="distribuidas">												
                                                        <thead>
															<tr class="ui-widget-header">                                                            
																<th style="text-align: center;font-size:10px;">Data e Hora na Expedição</th>
																<th><a href="#">Cliente</a></th>
																<th><a href="#">Transportadora</a></th>                                                            
																<th><a href="#">UF</a></th>
																<th><a href="#">Cidade</a></th>
																<th style="text-align: center;"><a href="#">Placa Carreta</a></th>
																<th><a href="#">Distribuição</a></th>
																<th><a href="#">Agendamento</a></th>
                                                               <!-- <th><a href="#">Situação</a></th>-->
																<!--<?php if($session_permissao == "G" || $session_permissao == "L" || $session_permissao == "E" ){?>
																<th style="text-align: center;">Ação</th>
																<?php } ?>-->
															</tr>
														</thead>
														<tbody>
                                                       
															<?php foreach ($cargasLogisticaDist as $rowPendentes) {?>
                                                             	
															<tr class="first">
																
																<td style="text-align: center;"><span><? /*php echo sqlToDataHora($rowPendentes['HORA_SAIDA_LOGISTICA'])*/ ?></span></td>
																<td><?php echo $rowPendentes['NOME_CLIENTE']?></td>
																<td><?php echo $rowPendentes['NOME_TRANSPORTADORA']?></td>
																<td><?php echo utf8_encode($rowPendentes['NOME_ESTADO'])?></td>
																<td><?php echo utf8_encode($rowPendentes['NOME_CIDADE'])?></td>
																<td style="text-align: center;"><?php echo $rowPendentes['PLACA_VEICULO']?></td>
																<td><?php echo $rowPendentes['DISTRIBUICAO']?></td>
																<td><?php echo toDate($rowPendentes['DATA_SAIDA'])?></td>
																<!--<?php if($session_permissao == "G" || $session_permissao == "L" || $session_permissao == "E"){?>
																
																<td style="width: 96px;">																			
																			<div class="actions">
																				<ul>
																					<li><a class="action1 thickbox" href="<?php echo base_url()?>index.php/expedicao/ver/<?php echo $rowPendentes['ID_CARGA']?>?height=580&width=1100" title="visualizar"></a></li>
																					<li><a class="action4" href="#" onclick="cancelar_carga(<?php echo $rowPendentes['ID_CARGA']?>)" title="Cancelar Carga"></a></li>
																				</ul>
																			</div>																	
																		</td>

																<?php } ?>-->
															</tr>
															<?php } ?>	                                                                                                               
														</tbody>
													</table>
                                                    
                                                    </div></div>
                                                   
                                                </div>                                                
                                                </fieldset>
                                                </form> 
                                                       