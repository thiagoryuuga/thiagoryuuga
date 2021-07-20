<?php include 'inicio.inc.php';?>

<script language="javascript">
	function cancelar_carga(id) {
		if (confirm("Deseja cancelar esta carga?")) {
			window.location.href="<?php echo base_url()?>index.php/expedicao/cancelar/"+id;		
		}
	}
</script>
  
<style type="text/css">       
	.ui-widget-header{background: #B4D8EA; border: 1px solid #B4D8EA; color:#2E6E9E}	
</style>
                    <div class="section table_section">                   
                        <div class="title_wrapper">
                            <h2>Expedição - Todas as Entrada</h2>
                            <span class="title_wrapper_left"></span>
                            <span class="title_wrapper_right"></span>
                        </div> 
                        
                        <?php if(@$msg){ ?>
						<ul class="system_messages">
							<li class="green"><span class="ico"></span><strong class="system_title"><?php echo $msg;?></strong></li>
						</ul>
						<?php } ?>   
						                   
                         <div id="tabs_ajax_portaria" class="sct_right">											
							<ul>								
								<li><a href="#tab-1">Entrada</a></li>																					
								<li><a href="#tab-2">Liberadas</a></li>
								<li><a href="#tab-3">Recusadas</a></li>
								<li><a href="#tab-4">Carregando</a></li>
                                <li><a href="#tab-5">Lib. Romaneio</a></li>
                                <li><a href="#tab-6">Lib. Fiscal</a></li>
                                <li><a href="#tab-7">Complementos</a></li>
                                <li><a href="#tab-8">Auditoria</a></li>																	
							</ul>                                             
                        <div id="tab-1" style="padding: 0" class="section_content">
                            <div class="sct" style="overflow-x:scroll;">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">                                                
                                                <form action="<?php echo base_url()?>logistica/pesquisar" method="post">
                                                <fieldset style="padding:5px">												
                                                <div class="table_wrapper" >
													<div class="table_wrapper_inner" >
                                                   
                                                    <table cellpadding="0" cellspacing="0" border="0"  id="expedicao">													
                                                        <thead>
															<tr>                                                            
																<th style="text-align: center;font-size:10px;">Data Expedição</th>
																<th><a href="#">Cliente</a></th>
																<th><a href="#">Transportadora</a></th>
                                                                <th><a href="#">Operação</a></th>            
																<th><a href="#">UF</a></th>
																<th><a href="#">Cidade</a></th>
																<th style="text-align: center;"><a href="#">Placa Carreta</a></th>
																<th style="text-align:center;"><a href="#">Janela</a></th>
																<th><a href="#">Agendamento</a></th>
                                                                <th><a href="#">Carregar</a></th>
																<th>Status</th>
                                                                <th>Motorista</th>
                                                               	<th style="text-align: center; width:40px;">Ação</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($cargasExpedicao as $rowPendentes) {?>	
															<tr class="first">
																
																<td style="text-align: center;"><span><?php echo sqlToDataHora($rowPendentes['HORA_SAIDA_LOGISTICA']);?></span></td>
																<td><?php echo substr($rowPendentes['NOME_CLIENTE'],0,15)?></td>
																<td><?php echo substr($rowPendentes['NOME_TRANSPORTADORA'],0,15)?></td>
                                                                <td><?php echo $rowPendentes['DES_OPERACAO']?></td>
																<td><?php echo utf8_encode($rowPendentes['NOME_ESTADO'])?></td>
																<td><?php echo utf8_encode($rowPendentes['NOME_CIDADE'])?></td>
																<td style="text-align: center;"><?php echo $rowPendentes['PLACA_VEICULO']?></td>
																<td style="text-align:center; width: 300px; word-break:break-word;"><?php echo $rowPendentes['DOCUMENTOS']?></td>
																<td><?php if (isset($rowPendentes['DATA_SAIDA']))
                                                                { 
                                                                    echo toDate($rowPendentes['DATA_SAIDA']);
                                                                 }?></td>
                                                                <td><?php echo $rowPendentes['LOCAL_CARREGAMENTO']?></td>
																 <td> <?php echo ($rowPendentes['STATUS'])?></td>
                                                                 <?php
                                                                    $link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";
                                                                ?>
                                                                <td class="center"><a class="thickbox" href="<?echo $link?>">[Informações]</a></td>
																<td>																			
																	<div class="actions" style="width: 40px;">
																	<ul>
																	    <li><a class="action1 thickbox" href="<?php echo base_url()?>index.php/expedicao/ver/<?php echo $rowPendentes['ID_CARGA']?>?height=580&width=1100" title="visualizar"></a></li>
																		<li><a class="action4" href="#" onclick="cancelar_carga(<?php echo $rowPendentes['ID_CARGA']?>)" title="Cancelar Carga"></a></li>
																	</ul>
																	</div>																	
																</td>																
															</tr>
															<?php } ?>	                                                                                                               
														</tbody>
													</table>
                                                    </div>
                                                </div>                                                
                                                </fieldset>
                                                </form>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>                            
                        </div>                        
                        <div id="tab-2" style="padding: 0" class="section_content">                            
                            <div class="sct" style="overflow-x:scroll; overflow-wrap: break-word">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">
                                                
                                                <form action="<?php echo base_url()?>logistica/pesquisar" method="post">
                                                <fieldset style="padding:5px">												
                                                <div class="table_wrapper" >																							
                                                    <div class="table_wrapper_inner">                                                                                                        
                                                    	<?php $this->load->view('liberadas_expedicao');?>                                                    	                                                   
                                                    </div>
                                                </div>                                                                                               
                                                </fieldset>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                                                      
                            <span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
                        </div>                        
					

					<div id="tab-3" style="padding: 0" class="section_content">                            
                            <div class="sct">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">
                                                <div class="table_wrapper">																							
                                                    <div class="table_wrapper_inner">                                                                                                        
                                                    	<?php $this->load->view('recusadas_portaria');?>                                                    	                                                   
                                                    </div>
                                                </div>                                                                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                                                      
                            <span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
                     </div>

                     <div id="tab-4" style="padding: 0" class="section_content">                            
                            <div class="sct">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">
                                                <div class="table_wrapper">																							
                                                    <div class="table_wrapper_inner">                                                                                                        
                                                    	<?php $this->load->view('carregando_expedicao');?>                                                    	                                                   
                                                    </div>
                                                </div>                                                                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                            
                        <div id="tab-5" style="padding: 0" class="section_content">                            
                            <div class="sct">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">
                                                <div class="table_wrapper">                                                                                         
                                                    <div class="table_wrapper_inner">                                                                                                        
                                                        <?php $this->load->view('liberacao_romaneio');?>                                                                                                         
                                                    </div>
                                                </div>                                                                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-7" style="padding: 0" class="section_content">                            
                            <div class="sct">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">
                                                <div class="table_wrapper">                                                                                         
                                                    <div class="table_wrapper_inner">                                                                                                        
                                                        <?php $this->load->view('pendentesComplementos');?>                                                                                                         
                                                    </div>
                                                </div>                                                                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 

                        <div id="tab-8" style="padding: 0" class="section_content">                            
                            <div class="sct">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">
                                                <div class="table_wrapper">                                                                                         
                                                    <div class="table_wrapper_inner">
                                                        <?php $this->load->view('pesquisa_historico');?>                                                                                                        
                                                        <?php $this->load->view('auditoriaExpedicao');?>
                                                    </div>
                                                </div>                                                                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        
                        <div id="tab-6" style="padding: 0" class="section_content">                            
                            <div class="sct">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">
                                                <div class="table_wrapper">                                                                                         
                                                    <div class="table_wrapper_inner">                                                                                                        
                                                        <?php $this->load->view('liberacao_fiscal');?>                                                                                                         
                                                    </div>
                                                </div>                                                                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
                     </div>                        
				</div>                    	                      
<?php include 'final.inc.php'?>						