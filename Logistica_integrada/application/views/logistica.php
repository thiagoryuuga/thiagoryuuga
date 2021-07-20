<?php include 'inicio.inc.php'?>

<script language="javascript">
	function cancelar_carga(id) {
		if (confirm("Deseja cancelar esta carga?")) {
			window.location.href="<?php echo base_url()?>index.php/logistica/cancelar/"+id;		
		}
	}
	
	

	
</script> 

<style type="text/css">       	   
	.ui-widget-header{background: #B4D8EA; border: 1px solid #B4D8EA; color:#2E6E9E}	
</style>
<!--<p><a id="load" href="#" title="Clique aqui para carregar o arquivo" rel="alternate">Carregar arquivo</a></p>-->
					

                    <!--[if !IE]>start section<![endif]-->	
                                                    
                    <div class="section table_section">
                        <!--[if !IE]>start title wrapper<![endif]-->
                        <div class="title_wrapper">
                            <h2>Distribui&ccedil;&atilde;o - Busca de Cargas</h2>
                            <span class="title_wrapper_left"></span>
                            <span class="title_wrapper_right"></span>
                        </div>
                        <!--[if !IE]>end title wrapper<![endif]-->
                        <div>
                        	<a name="page" > </a>
                        </div>
                         <div id="tabs_ajax_logistica" class="sct_right">											
							<ul>								
								<li><a href="#tab-1">Pendentes</a></li>																					
								<li><a href="#tab-2">Distribuidas</a></li>																	
							</ul>                                             
                        <div id="tab-1" style="padding: 0" class="section_content">
                            <div class="sct">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">  
                                            <?php echo $this->load->view('pesquisa') ?>                                              
                                                <form action="<?php echo base_url()?>logistica/pesquisar" method="post">
                                                <fieldset style="padding:5px; width:100%;">												
                                                <div class="table_wrapper" style="width:100%;">
                                              		<div class="table_wrapper_inner" style="width:100%;">
                                                    <div class="dashboard_menu" id="m_transp" style="font-size:16px; display:none; width:100%; "><label> Selecione a Transportadora </label><select id="transp" name="transp"><option value="-1">Escolha</option><?php foreach ($trasportadoras as $transportadoras){?>
         <option value="<?php echo($transportadoras['ID_TRANSPORTADORA'])?>"><?php echo($transportadoras['NOME_TRANSPORTADORA']." - ".$transportadoras['CNPJ_TRANSPORTADORA'])?></option>  <?php } ?>  
                                                
         </select></div>
         <table cellpadding="0" cellspacing="0"  style="margin-right:10px;"border="0"  id="expedicao">													
         	<thead style="width:100%; font-size:12px;">
			<tr>
            <th style="text-align: center; width:auto;">Id carga</th>                                                            
				<th style="text-align: center; width:auto;">Data Expedição</th>
				<th style="text-align: center; width:auto;"><a href="#">Cliente</a></th>
				<th style="text-align: center; width:auto;"><a href="#">Transportadora</a></th> 
				<th style="text-align: center; width:auto;"><a href="#">Operação</a></th>                                                            
				<th style="text-align: center; width:auto;"><a href="#">UF</a></th>
				<th style="text-align: center; width:auto;"><a href="#">Cidade</a></th>
				<th style="text-align: center; width:auto;"><a href="#">Placa Carreta</a></th>
				<th style="text-align: center; width:auto;"><a href="#">Agendamento</a></th>
				<th style="text-align: center; width:auto;"><a href="#">Status</a></th>
                <th style="text-align: center; width:auto;"><a href="#">Motorista</a></th>
                <th style="text-align: center; width:40px;"><a href="#">Ação</a></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($cargasLogistica as $rowPendentes) { ?>	
				<tr class="first" style="width:100%;">
					<th style="text-align:center;"><?php echo $rowPendentes['ID_CARGA']; ?></th>
					<td style="text-align: center;"><span><?php echo substr($rowPendentes['DATA_ATUAL_2'], 0,10). " as ".$rowPendentes['HORA_CHEGADA']?></span></td>
					<td style="text-align: center;">
					<?php if (isset ($rowPendentes['NOME_CLIENTE']))
					{ 
						echo ("");
					} 
					else{ 
						echo("");
						}  ?>
					</td>
					<td style="text-align: center;"><?php if (isset ($rowPendentes['NOME_TRANSPORTADORA']))
					{ 
						echo $rowPendentes['NOME_TRANSPORTADORA'];
					} 
					else{ 
							echo("");
						}?>
						</td>
						<td style="text-align: center;"><?php echo utf8_encode($rowPendentes['DES_OPERACAO'])?></td>
						<td style="text-align: center;"><?php echo utf8_encode($rowPendentes['NOME_ESTADO'])?></td>
						<td style="text-align: center;"><?php echo utf8_encode($rowPendentes['NOME_CIDADE'])?></td>
						<td style="text-align: center;"><?php echo $rowPendentes['PLACA_VEICULO']?></td>
						<td style="text-align: center;"><?php echo toDate($rowPendentes['DATA_SAIDA'])?></td>
						<td><?php echo $rowPendentes['STATUS']?></td>
						<?php
							$link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";
						?>
						<td class="center"><a class="thickbox" href="<?echo $link?>">[Informações]</a></td>
						<td>																			
						<div class="actions" style="width:40px;">
						<ul>
								<li><a class="action1 thickbox" href="<?php echo base_url()?>index.php/logistica/ver/<?php echo $rowPendentes['ID_CARGA']?>?height=580&width=1100" title="visualizar"></a> </li>
								<li><a class="action4" href="#" onClick="cancelar_carga(<?php echo $rowPendentes['ID_CARGA']?>)" title="Cancelar Carga"></a></li>
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
                            <div class="sct">
                                <div class="sct_left">
                                    <div class="sct_right">
                                    <?php echo($this->load->view('pesquisa'))?>
                                          	<?php $this->load->view('liberadas_expedicao');?> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                                                      
                            <span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
                        </div>                        
					</div>
<?php include 'final.inc.php'?>						