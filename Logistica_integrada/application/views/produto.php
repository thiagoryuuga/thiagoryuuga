<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">

$(function(){
	
	
	$("#Limpar").click(function(){
		window.location.href = '<?php echo base_url();?>index.php/importProduto';
	});
	
	$("#record_length").html("Tabela");
	
	$("#importForm").validate();
	
	if($.browser.msie == true){
		$("#form").css("height",120+'px');
	}
	
	var msn = '<?php if (isset($msn)) echo $msn;?>';
		if(msn == "msn5"){
			window.location.href = '<?php echo base_url();?>'+'/index.php/produto';
			
			alert('Importação realizada com sucesso.');
		}else if(msn == "msn6"){
			window.location.href = '<?php echo base_url();?>'+'/index.php/produto';
			alert('Ocorreu um erro na importação, tente novamente.');
		}	
	$('.readonly').attr('readonly','readonly');
	
	
});
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
                        
                        <h3 style="padding: 10px;" id="titulo">Cadastro de produtos</h3>	
                           
                                                
													<form  enctype="multipart/form-data" action="<?php echo base_url() . 'index.php/importProduto/importar'; ?>" method="post" id="importForm" accept-charset="UTF-8">
											
                                                    <fieldset style="padding:5px">
                                                    
                                                    <div style="padding:25px; background-color:#B4D8EA; height: 120px;">  
                                                        <br/><br/>
                                                        <label>Selecione a planilha: </label><br />
                                                        <input class="button white text" style="width: 360px; height:30px;" name="arquivo" id="arquivo" type="file" class="required" /><br />													
                                                        <br /><br /><button class="button blue normal" type="submit" id="Cadastrar"  name="Cadastrar" value="Cadastrar" />Cadastrar</button>												
                                                                                                        
                                                    </div>
                                                    
                                                    </fieldset>
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
													
													<div class="table_wrapper"  >
												
														<div id="table_wrapper_inner" class="table_wrapper_inner">
													
                                                            <table cellpadding="1px;" cellpadding="0" cellspacing="0" border="0"  id="logistica_produtos">
															
																<thead >
																	<tr>
                                                                        	
																		<th><a href="#">Cod. Produto</a></th>
																		<th><a href="#">Desc. Produto</a></th>																
                                                                        <th><a href="#">EAN</a></th>
																		<th><a href="#">Comprmento (mm)</a></th>
																		<th><a href="#">Largura (mm)</a></th>
																		<th><a href="#">Altura (mm)</a></th>
																		<th><a href="#">Peso total (Kg)</a></th>
																		<th><a href="#">Volume total (m³)</a></th>
                                                                        <th><a href="#">Data de Importação</a></th>
																	</tr>
																</thead>
																<tbody>
                                                                  <?php  foreach ($cadastrados as $produtos)
                                                            {?>
                                                            <tr><td><?php echo ($produtos['COD_PRODUTO'])?></td>
                                                            <td><?php echo (iconv ('ISO-8859-1','UTF-8',$produtos['DESC_PRODUTO']))?></td>
                                                            <td><?php echo ($produtos['COD_EAN'])?></td>
                                                            <td><?php echo ($produtos['COMPRIMENTO'])?></td>
                                                            <td><?php echo ($produtos['LARGURA'])?></td>
                                                            <td><?php echo ($produtos['ALTURA'])?></td>
                                                            <td><?php echo ($produtos['PESO'])?></td>
                                                            <td><?php echo ($produtos['CUBAGEM'])?></td>
                                                            <td><?php echo ($produtos['DATA_IMPORT'])?></td></tr>
                                                          <?php  } ?>		
                                                                
                                                                				
																</tbody>
															</table>
                                                          
														</div>
												
				