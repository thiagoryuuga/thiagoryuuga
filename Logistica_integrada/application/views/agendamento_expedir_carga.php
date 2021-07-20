<script language="javascript">
	function liberarCarga(id) {
		if (confirm("Deseja realmente liberar?")) {
			window.location.href="<?php echo base_url()?>index.php/agendamento/expedirLiberar/"+id;
		}
	}
</script>

								
													<h3 style="padding: 10px;">Agendamento - Expedir Carga</h3>	
													
													<table cellpadding="0" cellspacing="0" border="0" class="display" id="autorizado">

														<thead>
															<tr>
															
																<th style="text-align: center;"><a href="#">Carga EBS</a></th>
																
																<th><a href="#">Inclusão</a></th>
																
																<th style="text-align: center;"><a href="#">Cliente</a></th>
																																		
																<th><a href="#">Endereço</a></th>
																
																  <th><a href="#">Agendamento</a></th>
																
																<th><a href="#">Cod. Item</a></th>
																
	                                                            <th><a href="#">Descrição Item</a></th>
	                                                            
	                                                            <th><a href="#">Quantidade</a></th>
	                                                          
																<th style="text-align: center;"><a href="#">Seleção</a></th>
																
																<?php //if($session_permissao == "G" || $session_permissao == "L" || $session_permissao == "E" and empty($_POST['filtro'])){?>
																<th style="width: 96px;">Ação</th>
																<?php //} ?>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($cargasAgendamentoDistribuicao as $row) {?>	
															<tr class="first">
																<!--<td style="text-align: center;"><?php echo $rowPendentes['ID'];?></td>-->
																
																<td style="text-align: center;"><span><?php echo $row['COD_CARGA_EBS']?></span></td>																		
																
																<td><?php echo $row['DATA_INCLUSAO_AGENDA'];?></td>
																
	                                                            <td><?php echo utf8_encode($row['NOME_CLIENTE']);?></td>
																
																<td><?php echo utf8_encode($row['ENDERECO']);?></td>
																
																<td><?php echo utf8_encode($row['DATA_AGENDAMENTO']);?></td>
																																		
																<td><?php echo $row['COD_ITEM'];?></td>
																
																<td><?php echo utf8_encode($row['DESCRICAO_ITEM']);?></td>
																
																<td><?php echo $row['QUANTIDADE'];?></td>
																																		
																<td style="text-align: center;"><input type="checkbox" name="COD_CARGA_EBS[]" value="<?php echo $row['COD_CARGA_EBS']?>"></td>	
																																																																	
																<td style="width: 96px;">																			
																	<div class="">
																		<ul style="list-style-type: none;">
																			<li>																				
																				<a id="create-user" class="button blue thickbox" href="<?php echo base_url();?>index.php/agendamento/formDevolucao/<?php echo $row['ID']?>?height=260&width=300" title="Devolver">Devolver</a></li>																			
																		</ul>
																	</div>																	
																</td>
																
															</tr>
															<?php } ?>	
																
																
														</tbody>
														
													</table>
											
												