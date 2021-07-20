<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script>
function spoil(id)
{
	//alert(<?php echo base_url()?>);
	
	//var identificador = id.split("_");
	//alert(identificador[0]);
	//$('#'+identificador[1]).css('display','');
	
	if ($('.'+id).css('display')=='none')
	{
		$('.'+id).css('display','');
		$('#sub_'+id).css('display','');
		$('#img1_'+id).css('display','none');
		$('#img2_'+id).css('display','block');
	}
	else
	{
		$('.'+id).css('display','none');
		$('#sub_'+id).css('display','none');
		$('#img1_'+id).css('display','block');
		$('#img2_'+id).css('display','none');
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

<script type="text/javascript">
function enviar(id,cod_carreta)
{
	var identificador = id.split("_");
	
	var  datas = (document.getElementById('data_lead_time_'+identificador[1]).value);
	//var endereco =  (document.getElementById('endereco_'+identificador[1]).value);
	var reserva =  (document.getElementById('reserva_'+identificador[1]).value);
	var retencao =  (document.getElementById('retencao_'+identificador[1]).value);
	//alert(identificador);
	//alert (datas);
	//alert (id);
	//alert(cod_carreta);
	//alert(endereco);
	//alert(reserva);
	//alert(retencao);
	
	$.ajax({ url: "<?php echo site_url('agendamento/setLeadTime'); ?>",
				data: { cod_agendamento:id, date:datas,cod_carreta:cod_carreta,reservas:reserva,retencoes:retencao},
				dataType: "json",
				type: "POST",
				success: function(data){
									window.location.href = "<?php echo base_url();?>index.php/agendamento#logistica";															
				}
				
			});
	
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
                        
                        <h3 style="padding: 10px;" id="titulo">Distribuições geradas</h3>	
                          <!--	<form  enctype="multipart/form-data" action="<?php echo base_url() . 'index.php/importAgenda/importar'; ?>" method="post" id="importForm" accept-charset="UTF-8">
											
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
                                                </form>-->												
												
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
													
													<div class="table_wrapper"  >
												
														<div id="table_wrapper_inner" class="table_wrapper_inner" style="width:100%;" >
													
                                                            <table  cellpadding="0" cellspacing="0" border="0"  id="distribuicoes_geradas" style="width:100%;">
															
																<!--<thead style="font-size:7px;">-->
                                                                 <thead>
                                                                        	
																		<th>Distribuicao</th>
																		<th>Veiculo</th>
                                                                        <th>Transportadora</th>																
                                                                       
																		<th>QTD</th>
																		<th>Cod. Item</th>
																		<th>Den. Item</th>
																		<th><!--<a href="#">-->Cliente<!--</a>--></th>
																		
                                                                        </thead>														
																	
                                                                        		<!--</thead>-->
                                                               <tbody>                                                     
																	<?php foreach ($distribuicoesGeradas as $row){?>
                                                                   <tr id="<?php echo ("tr_".$row['COD_DISTRIBUICAO_EBS'])?>" 
                                                                   class="<?php echo("tr_".$row['COD_DISTRIBUICAO_EBS'])?>">
                                                                   <td><?php echo $row['COD_DISTRIBUICAO_EBS']?></td>
																   <td><?php echo $row['COD_CARRETA']?></td>
                                                                   <td><?php echo $row['TRANSPORTADORA']?></td>																		
																   <td><?php echo $row['QTD'];?></td>
																   <td><?php echo $row['COD_ITEM'];?></td>
																   <td><?php echo $row['DEN_ITEM'];?></td>
																   <td><?php echo $row['CLIENTE_ENTREGA'];?></td>                                                               	
																	
																	<?php } ?>	
                                                                    </tr>															
																</tbody>
															</table>
														</div>
													</div>
				