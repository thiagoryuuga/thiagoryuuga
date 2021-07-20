<script language="javascript">
	function valida(id) {
		alert(id);
		if (confirm("Deseja autorizar a Saída ?")) {
			


			window.location.href="<?php echo base_url()?>index.php/inicio/saida/"+id;
		}
		
	}
	
$(function (){
	$(".changer").change(function()
	{
		if($(".changer").is(":checked")){
		$("#m_transp").css('display','block');
		$("#m_header").css('display','block');
		//$(".m_button").css('display','block');
		}
		else
		{
		$("#m_transp").css('display','none');
		$("#m_header").css('display','none');
		//$(".m_button").css('display','none');
		}
	});
});

function alteraTransp(value){
	
	if($('#transp').val()=='-1')
	{
		$('#transp').focus();
		alert('Defina uma Transportadora');
	}
	else {		
		$.ajax({ url: "<?php echo site_url('agendamento/trocaTransp'); ?>",
			data: { id_carga:$('input[type=checkbox]:checked').serializeArray() , transp: $("#transp").val()},
			dataType: "json",
			type: "POST",
			success: function(data){
			$('#liberadas').fadeOut('slow');
			alert('Alterações efetuadas com sucesso');			
			document.location.reload(true);				
													
			}
			
		});
	}
		
}

</script>

   <div class="dashboard_menu" id="m_transp" style="font-size:14px; display:none;"><label style="float:left;"> Selecione a Transportadora </label><select id="transp" name="transp" style="float:left; margin-left:5px;"><option value="-1">Escolha</option><?php foreach ($trasportadoras as $transportadoras){?>
         <option value="<?php echo($transportadoras['ID_TRANSPORTADORA'])?>"><?php echo($transportadoras['NOME_TRANSPORTADORA']." - ".$transportadoras['CNPJ_TRANSPORTADORA'])?></option>  <?php } ?>
                                                
         </select> <button class="button blue normal" type="button"  id="Alterar" name="Alterar" 
 style="float:left;  margin-left:10px;" value="Alterar" onclick="alteraTransp(this.value)" />Alterar</button>
 </div>
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
         
<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="recusadas">

														<thead>

															<tr style="color:#6B98A8">

																<th style="text-align: center;font-size:8px;">Data e hora da Chegada</th>
																<th>Transportadora</th>
																<th>Veículo</th>
																<th style="text-align: center;">Placa</th>
																<th>UF</th>																
																<th>Cidade</th>
																<th>Status</th>
																<th>Loc. Carga</th>
																<th>Doca</th>
																<th>Conferente</th>
																<th>Capatazia</th>
																<th>Motorista</th>
																<th>Motivo Recusa</th>
																<th style="text-align: center;">Visualizar</th>

                                                                <!--<th style="text-align:center;">Marcar</th>-->
                                                               <!-- <th style="display:none;" id="m_header">Gravar</th> -->

															</tr>

														</thead>

														<tbody style="font-size:11px;">
                                                       
															<?php foreach ($veiculosRecusados as $rowPendentes) {?>	
															<tr>

																<td  style="background-color:#fff" class="center"><?php echo (sqlToDate($rowPendentes['DATA_ATUAL']).', as '.$rowPendentes['HORA_CHEGADA'])?></td>

																<td><div id="<?php echo ('d_'.$rowPendentes['ID_CARGA'])?>"><?php echo $rowPendentes['NOME_TRANSPORTADORA']?></div></td>

																<td><?php echo $rowPendentes['VEICULO']?></td>

																<td class="center"><?php echo $rowPendentes['PLACA_VEICULO']?></td>

																<td><?php echo utf8_encode($rowPendentes['NOME_ESTADO'])?></td>
																
																<td><?php echo utf8_encode($rowPendentes['NOME_CIDADE'])?></td>

																<td><?php echo $rowPendentes['STATUS']?></td>

																<td><?php echo($rowPendentes['LOCAL_CARREGAMENTO'])?></td>

																<td><?php echo($rowPendentes['DOCA'])?></td>

																<td><?php echo($rowPendentes['CONFERENTE'])?></td>

																<td><?php echo($rowPendentes['CAPATAZIA'])?></td>

																<?php
                                                                    $link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";
                                                                ?>
                                                                <td class="center"><a class="thickbox" href="<?echo $link?>">[Informações]</a></td>

																<td><?php echo(utf8_encode($rowPendentes['OBSERVACAO_RECUSA']))?></td>

																<td style="width: 96px;">																			
																			<div class="actions">
																				<ul>
																					<li><a class="action1 thickbox" href="<?php echo base_url()?>index.php/expedicao/ver/<?php echo $rowPendentes['ID_CARGA']?>/<?php echo $par = ""?>?height=580&width=1100" title="visualizar"></a></li>
																				</ul>
																			</div>																	
																		</td>
                                                                        
															</tr>
															<?php } ?>
                                                          															
														</tbody>
														
													</table>