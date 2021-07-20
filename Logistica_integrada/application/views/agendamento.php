<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript">
function clonaLinha(id)
{
			
	if($('#'+id).is(':checked'))
	{
		var  confirmar = confirm("deseja quebrar esta linha?");
		if (confirmar == true)
		{
			var id = id.toString().split("_"); 
			var clone = $("."+id[1]).clone();
			clone.addClass('cloned');
			clone.insertAfter("."+id[1]);
		
		}
	}
		
}
</script>

<script type="text/javascript">

function marcarLeadtime(id)
{
	var id = id.toString();
	var ide = id.split("_");
	var leadtime = (document.getElementById('combo_leadtimes_'+ide[1]).value);

	var confirmar = confirm("Confirmar Leadtime de "+leadtime+" dias para esta entrga?");
	if(confirmar ==true)
	{	
	
		if(leadtime == "-1")
		{
			document.getElementById('combo_leadtimes_'+ide[1]).style.color="#F00";
			document.getElementById('combo_leadtimes_'+ide[1]).focus();
			alert('Valor do Leadtime não pode ser nulo');
		}
			else
			{
			$.ajax({ url: "<?php echo site_url('agendamento/setLeadTimePorCarreta'); ?>",
					data: { cod_carreta:ide[1],leadtime:leadtime},
					dataType: "json",
					type: "POST",
					success: function(data){
						alert('Leadtime definido.');
						window.location.reload();

						}

				});	
			
			}

	}
}


function verificador(id)
{	
	
	var id = id.toString();
	var ide = id.split("_");

	if($("."+ide[1]).length > 1)
	{
		
			var reservas =  (document.getElementById('reserva_'+ide[1]).value);
			var retencoes =  (document.getElementById('retencao_'+ide[1]).value);
			var quantid = (document.getElementById('qtd_'+ide[1]).value);
			$.ajax({ url: "<?php echo site_url('agendamento/setLeadTimeDuplicado'); ?>",
							data: {cod_agendamento:ide[1],reservas:reservas,retencoes:retencoes,quantid:quantid},
							dataType: "json",
							type: "POST",
							success: function(data){
								alert('Informações atualizadas');
												
												}
										});

	}
	else
	{
		
			
			var reservas =  (document.getElementById('reserva_'+ide[1]).value);
			var retencoes =  (document.getElementById('retencao_'+ide[1]).value);
        	var quantid = (document.getElementById('qtd_'+ide[1]).value);
        	var distrib = (document.getElementById('distribicao_'+ide[1]).value)
			
			$.ajax({ url: "<?php echo site_url('agendamento/setInfoCargas'); ?>",
					data: { cod_agendamento:ide[1], quantid:quantid,reservas:reservas, retencoes:retencoes, distribuicao:distrib},
					dataType: "json",
					type: "POST",
					success: function(data){
								alert('Informações atualizadas');				
										
									}
							});	
			}
			alert('Informações atualizadas');
			//window.location.reload();
			
	
}

</script>


<script type="text/javascript">
function changer(id)
{
	var id = id.toString();
	var identificador = id.split("_");
	var header = (identificador[1]+"_"+identificador[2]+"_"+identificador[3]);
	var linhas = header.replace("th","tr")
	$('#'+header).css('display','');

	if ($('.'+header).css('display')=='none')
	{
		

		$('.'+header).css('display','');
		$('.'+linhas).css('display','');
		$('.cloned '+linhas).css('display','');
		$('#img1_'+header).css('display','none');
		$('#img2_'+header).css('display','block');
		
	}
	else
	{
		

		$('.'+header).css('display','none');
		$('.'+linhas).css('display','none');
		$('.cloned '+linhas).css('display','none');
		$('#img1_'+header).css('display','block');
		$('#img2_'+header).css('display','none');
		
	}
	
}
</script>

<script type="text/javascript">

$(function(){
	
	$("#data_agendamento").datepicker();
	$("#data_agendamento").datepicker("option", "dateFormat", 'dd/mm/yy');
	
	
	$("#Limpar").click(function(){
		window.location.href = '<?php echo base_url();?>index.php/importFaltas';
	});
	
	$("#record_length").html("Tabela");
	
	$("#importForm").validate();
	
	if($.browser.msie == true){
		$("#form").css("height",120+'px');
	}
	
	var msn = '<?php if (isset($msn)) echo $msn;?>';
		if(msn == "msn5"){
			window.location.href = '<?php echo base_url();?>'+'/index.php/agendamento';
			
			alert('Importação realizada com sucesso.');
		}else if(msn == "msn6"){
			window.location.href = '<?php echo base_url();?>'+'/index.php/agendamento';
			alert('Ocorreu um erro na importação, tente novamente.');
		}	
	$('.readonly').attr('readonly','readonly');
	
	
});

function cancelar(cod_agendamento){
	var conf = confirm('Deseja Realmente Cancelar Este Agendamento?');
	if(conf){
		window.location.href = "<?php echo base_url();?>index.php/agendamento/programacaoCancelar/"+cod_agendamento;
	}
}

$('#filial').validate()
{
	if ($('#filial').value == "")
	{
		$('#filial').addClass('required');
	}
	else
	{
		$('#filial').removeClass('required');
	}
}

</script>


<style type="text/css">
    
	label.error { width:100px;  color: red; margin: 7px 10px; vertical-align: middle; font-size: 10px; }
	p { clear: both; }
	.submit { margin-top: 3em; }
	em { font-weight: bold; padding-right: 1em; vertical-align: top; }

	input.text { 
		border: 1px solid #cecece;		
		background: #fefefe ;		
		width: 194px;
		
	}
	
	.readonly{
		background: #f5f5f5;
	}
	
	#record_length {
		font-size: 20px;
		font-weight: normal;
		line-height: 35px;
		margin: 0 0 0 13px;;
		padding: 0;
	}
	a.action1{
		
		background:url(<?php echo base_url();?>/css/layout/site/tables/view.png);
		padding:0px 8px 3px 8px;
	}
	a.cancelar{
		
		background:url(<?php echo base_url();?>/css/layout/site/tables/action4.gif) no-repeat;
		padding:0px 8px 5px 10px;
		margin-left:12px;
		cursor:pointer;
	}
			
</style>

                                         
                        <div>
                        	<a name="page"> </a>
                        </div>
                        
                        <h3 style="padding: 10px;" id="titulo">Ajustes de Agendamento</h3>
                                      
												             										
												
													<?php if(@$msg){ ?>
													<ul class="system_messages">
														<li class="green"><span class="ico"></span><strong class="system_title"><?php echo $msg;?></strong></li>
													
													</ul>
													<?php } ?>
													
													<?php if($this->session->flashdata('flashCancelado')){?>															
														<ul class="system_messages">														
															<li class="yellow"><span class="ico"></span><strong class="system_title"></strong><?php echo $this->session->flashdata('flashCancelado');?></li>
														</ul>													
													<?php } ?>	
																									
													<br/>
													
													<div class="table_wrapper">
														<div id="table_wrapper_inner" class="table_wrapper_inner">
                                                            <table  cellpadding="0" cellspacing="0" border="0"  id="logistica" >
																                                                                     		
                                                               <tbody>
                                                                <?php foreach ($resumoAgendamentos as $header) {?>
                                                               
                                                                <tr id="<?php echo("sub_th_".$header['COD_CARRETA'])?>" ><td>
                                                                <ul style="list-style-type: none;">
                                                                	<li>
                                                                    			<img src="<?php echo base_url()."images/plus.png"?>"  id="<?php echo("img1_sub_th_".$header['COD_CARRETA'])?>" style="display:block;" onclick="changer(this.id);"></li>
                                                                	<li>
                                                                			  <img src="<?php echo base_url()."images/minus.png"?>" id="<?php echo("img2_sub_th_".$header['COD_CARRETA'])?>" style="display:none;" onclick="changer(this.id);"></li>
                                                                </ul>
                                                                
                                                                </td><td colspan="2" style="height:45PX; font-size:14px;"><?php echo('Dados gerais: ');?></td>
                                                                 <td  colspan="3" id="<?php echo('header_carreta_'.$header['COD_CARRETA'])?>"><?php echo("CARRETA -> ".$header['COD_CARRETA'])?></td>
                                                                 <td colspan="4"><?php echo("REGIONAL -> ".$header['REGIONAL']); ?></td> 
                                                                 <td colspan="3"><?php echo ("CLIENTE ->".$header['CLIENTE_ENTREGA'])?></td>
                                                                 <td colspan="4"> <?php echo("DATA DE AGENDAMENTO ->".$header['DATA_AGENDAMENTO'])?> </td>
                                                                <td colspan="3">

                                                                <?php if(isset($header['DATA_LIMITE']))
                                                                { 
                                                                	echo( "DATA LIMITE -> ".$header['DATA_LIMITE']);
                                                                }
                                                                else { ?>


                                                                <select name="<?php echo("combo_leadtimes_".$header['COD_CARRETA'])?>" id="<?php echo("combo_leadtimes_".$header['COD_CARRETA'])?>">
                                                                   	<option value="-1">SELECIONE</option>
                                                                       	<?php foreach($tempo as $locais){?>
                                                                        	<?php if($header['REGIONAL'] == $locais['LOCALIDADE']){?>
                                                                        		<option value="<?php echo($locais['LEADTIME'])?>" selected="selected" ><?php echo($locais['LOCALIDADE'])?></option>
                                                                        		<?php } else { ?>
                                                                        	<option value="<?php echo($locais['LEADTIME'])?>" ><?php echo($locais['LOCALIDADE'])?></option>
                                                                        	<?php } }?>
                                                                </select><?php } ?></td>
                                                                	


                                                                </td>

                                                                <?php if(!isset($header['DATA_LIMITE']))
                                                                {?>
                                                                <td colspan="2"><input type="button" class="button green" id="<?php echo('registrar_'.$header['COD_CARRETA'])?>"
                                                                name="<?php echo('registrar_'.$header['COD_CARRETA'])?>" value="Confirmar Leadtime" onclick="marcarLeadtime(id)"></td>
                                                                <?php } else {?><td> &nbsp;</td> <?php } ?>
                                                                
                                                                <?php  $linha_atual=""; $linha_antiga=""; ?>
																	<?php foreach ($cargasAgendamento as $row){?>
                                                                    	<?php $linha_atual = $row['COD_CARRETA'];
																		if($linha_atual  != $linha_antiga && $header['COD_CARRETA'] == $row['COD_CARRETA'])
																		{
																		?>
                                                                    
                                                                    <tr id="<?php echo("sub_th_".$row['COD_CARRETA'])?>" class="<?php echo("sub_th_".$row['COD_CARRETA'])?>" style="display:none;">
                                                                        	
																		<th>Carreta</th>
																		<th>Endere&ccedil;o</th>																
                                                                        
																		<th>Cliente</th>
																		<th>OC cliente</th>
																		<th>Cod. Item</th>
																		<th>Desc. Item</th>
																		<th>Qtd</th>
																		<th>Linha</th>
                                                                        <th>Data Agendamento</th>
                                                                        <th>Local de Carga</th>
                                                                        <th>Filial</th>
                                                                        <th>Data Cadastro</th>
																		<th>Peso Total(Kg)</th>
                                                                        <th>Vol. Total(M³)</th>
                                                                        <th>Reservas</th>
                                                                        <th>Retenção</th>
                                                                        <th>Distribuição</th>
                                                                        <th style="width:200px;">Status</th>
                                                                        <th>Prazo Limite (Dias)</th>
                                                                        <th>Dividir</th>
                                                                        <th>Ação</th>
                                                                        </tr>														
                                                                    <?php } $linha_antiga = $linha_atual;?>
                                                                    <?php if($header['COD_CARRETA'] == $row['COD_CARRETA'])
																	{?>
                                                                    
																		
																	  <tr id="<?php echo ("sub_tr_".$row['COD_CARRETA'])?>" class="<?php echo("sub_tr_".$row['COD_CARRETA'])." " .$row['COD_AGENDAMENTO']?>" style="display:none;" >
                                                                    <td ><?php echo $row['COD_CARRETA']?><input type="hidden" id="<?php echo ("carreta_".$row['COD_AGENDAMENTO'])?>" name="<?php echo ("carreta_".$row['COD_AGENDAMENTO'])?>" 
                                                                    value="<?php echo $row['COD_CARRETA']?>"></td>
																											 
                                                                        <td><?php echo $row['REGIONAL']?></td>																		
																		<td><?php echo $row['CLIENTE_ENTREGA'];?></td>
																		<td><?php echo $row['OC_CLIENTE'];?></td>
																		<td><?php echo $row['COD_ITEM'];?></td>
																		<td><?php echo $row['DEN_ITEM'];?></td>
																		<td><input type="text" id="<?php echo("qtd_".$row['COD_AGENDAMENTO'])?>" name="<?php echo("qtd_".$row['COD_AGENDAMENTO'])?>" value="<?php echo $row['QTD']?>"/></td>
																		<td><?php echo $row['LINHA'];?></td>
																		<td><?php echo $row['DATA_AGENDAMENTO'];?></td>
                                                                        <td><?php echo $row['LOCAL_CARGA'];?></td>
																		<td><?php echo $row['DEN_FILIAL'];?></td>
                                                                        <td><?php echo $row['DATA_CADASTRO'];?></td>
                                                                        <td><?php echo ($row['PESO'] * $row['QTD'])?></td>
                                                                        <td><?php echo ($row['CUBAGEM'] * $row['QTD'])?></td>
                                                                        <td><input name="<?php echo("reserva_".$row['COD_AGENDAMENTO'])?>" id="<?php echo("reserva_".$row['COD_AGENDAMENTO'])?>" 
                                                                        value="<?php if (isset($row['RESERVA'])){echo ($row['RESERVA']);}?>">                                                                
                                                                        </td>
                                                                        <td width="50px;"><select name="<?php echo("retencao_".$row['COD_AGENDAMENTO'])?>" id="<?php echo("retencao_".$row['COD_AGENDAMENTO'])?>">
                                                                        <option value="<?php echo ($row['RETENCAO'])?>" selected="selected"><?php echo $row['RETENCAO']?></option>
                                                                        <?php if ($row['RETENCAO']=="NAO"){?>
                                                                        <option value="SIM">SIM</option>
                                                                        <?php } else { ?>
                                                                        <option value="NAO">NAO</option>
                                                                        <?php  } ?>
                                                                        </select>
                                                                        </td>
                                                                        <td><input type="text" name="<?php echo('distribicao_'.$row['COD_AGENDAMENTO'])?>" id="<?php echo('distribicao_'.$row['COD_AGENDAMENTO'])?>" value="<?php echo ($row['COD_DISTRIBUICAO_EBS'])?>" /></td>
																		
																		<td style="width:200px; text-align:left;"> 
																			<?php if ($row['STATUS'] == "A"){																								
																						echo anchor("agendamento#tab-1", 'Agendado', "style=\"height: 30px;\" class=\"button orange\"");																																			
																					} 
																					 if ($row['STATUS'] == "B"){																								
																						echo anchor("agendamento#tab-1", 'Distribuido', "style=\"height: 30px;\" class=\"button green\"");																																			
																					} 
																					
																					if ($row['STATUS'] == "D"){?>
                                                                                    <li  style="list-style-type: none; height:30px; padding-bottom:5px;">
                                                                                    <a class="action1 thickbox" href="<?php echo base_url();?>index.php/agendamento/alterarAgendamento/<?php echo $row['COD_AGENDAMENTO'];?>?height=600&width=650" title="Atualizar" alt="Atualizar"></a><a class="cancelar" onclick="cancelar(<?php echo $row['COD_AGENDAMENTO'];?>);" title="Cancelar" alt="Cancelar"></a></li>
																						<li  style="list-style-type: none; float:left;">
																				      
																						</li>
																			<?php 			
																					}																					
																			?>																		
																		</td>
                                                                       	
																	<td style="width:50px; padding:0px;"><?php 
																	if (isset($row['DATA_LIMITE'])){
						if (isset ($row['LEADTIME']) && ($row['RESTANTE']> $row['LEADTIME']))
						{
							$cor = 'green';
						}
						if ( isset ($row['LEADTIME']) && ($row['RESTANTE'] == $row['LEADTIME']))
						{
							$cor = 'orange';
						}
						if (isset ($row['LEADTIME']) && ($row['RESTANTE']< $row['LEADTIME']))
						{
							$cor = 'red';
						}
																		?> <input type="button" 
                                                                        class="button<?php echo(' '.$cor)?>" id="devolver" style="font-size: 10px; float: left; margin:10px; padding:10px; height:40px;" value="<?php echo($row['RESTANTE']." Dias "); ?>" ><?php }
																		 else { echo ('não cadastrado');} ?></td>
                                                                        <td><input type="checkbox" id="<?php echo("quebra_".$row['COD_AGENDAMENTO'])?>" onclick="clonaLinha(this.id);"></td>
                                                                        <td><input type="button" class="<?php echo('button blue')?>" id="<?php echo('gravar_'.$row['COD_AGENDAMENTO'])?>" 
                                                                        style="font-size: 10px; float: left; margin:10px; padding:10px; height:40px;" value="Gravar" onclick="verificador(id);"></td>	
																	</tr>
																	<?php } ?>
                                                                    <?php } ?>
                                                                    <?php } ?>
                                                                    
                                                                    	
																
																
																</tbody>
															</table>
														</div>
                                                       
													</div>



				