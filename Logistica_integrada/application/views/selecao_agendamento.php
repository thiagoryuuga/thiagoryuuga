<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript">
function excluir(id)
{
    
	var id = id.toString();
	var ide = id.split("_");

	$.ajax({ url: "<?php echo site_url('agendamento/descartaAgendamento'); ?>",
							data: {cod_agendamento:ide[1]},
							dataType: "json",
							type: "POST",
							success: function(data){
												alert('Dados Excuídos');
												window.location.reload(true);
												}
										});				
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


function agendador(id)
{	
	var id = id.toString();
	var ide = id.split("_");

	$.ajax({ url: "<?php echo site_url('agendamento/transfereAgendamento'); ?>",
			data: { cod_agendamento:ide[1]},
			dataType: "json",
			type: "POST",
			success: function(data){				
								alert('Dados Importados');
								window.location.reload(true);
								}
					});	
}




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
                        
                        <h3 style="padding: 10px;" id="titulo">Agendamento - Cadastro de Carga</h3>
                        
                                                
													<form  enctype="multipart/form-data" action="<?php echo base_url() . 'index.php/importAgenda/importar'; ?>" method="post" id="importForm" accept-charset="UTF-8">
											
                                                    <fieldset id="setfield" style="padding:5px">
                                                    
                                                    <div style="padding:25px; background-color:#B4D8EA; height: 120px;">  
                                                       <label style="margin-left:10px;">Filial </label><br />
                                                        &nbsp;&nbsp;&nbsp;
                                                                                                                                                        
                                                    
                                                        <select name="filial" id="filial">
                                                         <?php $numFilial = count($filial);  if($numFilial > 1){?>
                                                               <option value="">.:: Selecione ::.</option>
                                                         <?php } ?>
                                                             <?php
															 	
															  	foreach($filial as $row){?>
                                                            	
                                                                <option value="<?php echo $row['DEN_FILIAL'];?>"><?php echo $row['DEN_FILIAL'];?></option>
                                                             <?php } ?>  																			
                                                        </select>			
                                                        <br/><br/>
                                                        <label>Selecione a planilha: </label><br />
                                                        <input class="button white text" style="width: 360px; height:30px;" name="arquivo" id="arquivo" type="file" class="required"/><br />													
                                                        <br /><br /><button class="button blue normal" type="submit" id="Cadastrar"  name="Cadastrar" value="Cadastrar"  />Cadastrar</button>												
                                                                                                        
                                                    </div>
                                                </form>												
												
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
															
																<!--<thead style="font-size:7px;">-->
																	
                                                                        		<!--</thead>-->
                                                               <tbody>
                                                                <?php foreach ($resumoAgendamentosImport as $header) {?>
                                                               
                                                                <tr id="<?php echo("sub_th_".$header['COD_CARRETA'])?>" class="sub_tr_test"><td>
                                                                <ul style="list-style-type: none;">
                                                                	<li>
                                                                    			<img src="<?php echo base_url()."images/plus.png"?>"  id="<?php echo("img1_sub_th_".$header['COD_CARRETA'])?>" style="display:block;" onclick="changer(this.id);"></li>
                                                                	<li>
                                                                			  <img src="<?php echo base_url()."images/minus.png"?>" id="<?php echo("img2_sub_th_".$header['COD_CARRETA'])?>" style="display:none;" onclick="changer(this.id);"></li>
                                                                </ul>
                                                                
                                                                </td><td colspan="2" style="height:45PX; font-size:14px;"><?php echo('Dados gerais: ');?></td>
                                                                 <td  colspan="3"><?php echo("CARRETA -> ".$header['COD_CARRETA'])?></td>
                                                                 <td colspan="3"><?php echo("REGIONAL -> ".$header['REGIONAL']); ?></td> 
                                                                 <td colspan="4"><?php echo ("CLIENTE ->".$header['CLIENTE_ENTREGA'])?></td>
                                                                 <td colspan="2"> <?php echo("DATA DE AGENDAMENTO ->".$header['DATA_AGENDAMENTO'])?> </td>
                                                                                                                   
                                                                <?php  $linha_atual=""; $linha_antiga=""; ?>
																	<?php foreach ($cargasAgendamentoImport as $row){?>
                                                                    	<?php $linha_atual = $row['COD_CARRETA'];
																		if($linha_atual  != $linha_antiga && $header['COD_CARRETA'] == $row['COD_CARRETA'])
																		{
																		?>
                                                                    
                                                                    <tr id="<?php echo("sub_th_".$row['COD_CARRETA'])?>" class="<?php echo("sub_th_".$row['COD_CARRETA'])?>" style="display:none;">
                                                                        	
																		<th>Carreta</th>
																		<th>Endereço</th>																
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
																		<!--<th>Reservas</th>-->
                                                                        <th>Retenção</th>
                                                                        <th style="width:130px;">Ação</th>
                                                                        </tr>														
                                                                    <?php } $linha_antiga = $linha_atual;?>
                                                                    <?php if($header['COD_CARRETA'] == $row['COD_CARRETA'])
																	{?>
                                                                    
																		
																	  <tr id="<?php echo ("sub_tr_".$row['COD_CARRETA'])?>" class="<?php echo("sub_tr_".$row['COD_CARRETA'])?>" style="display:none;" >
                                                                    <td ><?php echo $row['COD_CARRETA']?><input type="hidden" id="<?php echo ("carreta_".$row['COD_AGENDAMENTO'])?>" name="<?php echo ("carreta_".$row['COD_AGENDAMENTO'])?>" 
                                                                    value="<?php echo $row['COD_CARRETA']?>"></td>
																											 
                                                                        <td><?php echo $row['REGIONAL']?></td>																		
																		<!--<td><?php echo $row['DATA_SOLICITACAO'];?></td>-->
                                                                        <td><?php echo $row['CLIENTE_ENTREGA'];?></td>
																		<td><?php echo $row['OC_CLIENTE'];?></td>
																		<td><?php echo $row['COD_ITEM'];?></td>
																		<td><?php echo $row['DEN_ITEM'];?></td>
																		<td><?php echo $row['QTD'];?></td>
																		<!--<td><input type="text" id="<?php echo("qtd_".$row['COD_AGENDAMENTO'])?>" name="<?php echo("qtd_".$row['COD_AGENDAMENTO'])?>" value="<?php echo $row['QTD']?>"/></td>-->
																		<td><?php echo $row['LINHA'];?></td>
																		<td><?php echo $row['DATA_AGENDAMENTO'];?></td>
                                                                        <td><?php echo $row['LOCAL_CARGA'];?></td>
																		<td><?php echo $row['DEN_FILIAL'];?></td>
                                                                        <td><?php echo $row['DATA_CADASTRO'];?></td>
                                                                       <td><?php echo $row['RETENCAO']?></td>
                                                                       <td style="width:130px;"><input type="button" class="<?php echo('button blue')?>" id="<?php echo('gravar_'.$row['COD_AGENDAMENTO'])?>" 
                                                                        style="font-size: 10px; float:left; margin:10px; padding:4px; height:24px;" value="Gravar" onclick="agendador(id);">
                                                                        <input type="button" class="<?php echo('button red')?>" id="<?php echo('excluir_'.$row['COD_AGENDAMENTO'])?>" 
                                                                        style="font-size: 10px; margin:10px; padding:4px; height:24px;" value="Excluir" onclick="excluir(id);"></td>
                                                                        
																	</tr>
																	<?php } ?>
                                                                    <?php } ?>
                                                                    <?php } ?>
                                                                    
                                                                    	
																
																
																</tbody>
															</table>
														</div>
                                                       
													</div>

				