<?php include ("inicio.inc.php");?>
<style type="text/css">       
	.ui-widget-header{background: #B4D8EA; border: 1px solid #B4D8EA; color:#2E6E9E}	
	.ui-tabs .ui-tabs-panel{background-color: #E7F3F8; margin-top: 3px; }
	.dataTables_filter{text-align:left ;width: 120px;}
</style>
                	<div class="section table_section">                   
                        
                        <div class="title_wrapper">
                            <h2>Portaria</h2>
                            <span class="title_wrapper_left"></span>
                            <span class="title_wrapper_right"></span>
                        </div>   
                                             
                        <div id="tabs_ajax_portaria" class="sct_right" style="width:auto;">
												
							<ul>
								<li><a href="#tab-1">Entrada</a></li>
								<li><a href="#tab-2">Pendentes</a></li>
								<li><a href="#tab-3">Autorizadas</a></li>										
								<li><a href="#tab-4">Recusadas</a></li>
								<li><a href="#tab-5">Saídas</a></li>
								<li><a href="#tab-6">Dif. Peso</a></li>																												
							</ul>
							
							<div id="tab-1" >
																
								<h3>Entrada - Cadastrar Caminhão</h3>												  
								<?php $this->load->view('entrada');?>
																				  
							</div>
							
							<div id="tab-2" style="padding: 0" class="section_content">                            
	                            <div class="sct">
	                                <div class="sct_left">
	                                    <div class="sct_right">
	                                        <div class="sct_left">
	                                            <div class="sct_right">	                                               							
	                                                <div class="table_wrapper" >																								                                                                                                                                                         
	                                                    <?php $this->load->view('pendentes');?>                                                    	                                                   	                                                    
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
	                                                <div class="table_wrapper" >																								                                                                                                                                                         
	                                                    <?php $this->load->view('diferenca_peso');?>                                                    	                                                   	                                                    
	                                                </div>                                                                                               	                                             
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>                                                        
							</div>
							
							<div id="tab-3" style="padding: 0" class="section_content">                            
	                            <div class="sct">
	                                <div class="sct_left">
	                                    <div class="sct_right">
	                                        <div class="sct_left">
	                                            <div class="sct_right">	                                               							
	                                                <div class="table_wrapper" >																								                                                                                                                                                         
	                                                    <?php $this->load->view('autorizados');?>                                                    	                                                   	                                                    
	                                                </div>                                                                                               	                                             
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>                                                        
							</div>

							<div id="tab-4" style="padding: 0" class="section_content">                            
	                            <div class="sct">
	                                <div class="sct_left">
	                                    <div class="sct_right">
	                                        <div class="sct_left">
	                                            <div class="sct_right">	                                               							
	                                                <div class="table_wrapper" >																								                                                                                                                                                         
	                                                    <?php $this->load->view('recusadas_portaria');?>                                                        	                                                   	                                                    
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
	                                                <div class="table_wrapper" >																								                                                                                                                                                         
	                                                    <?php $this->load->view('saidas_expedicao');?>                                                        	                                                   	                                                    
	                                                </div>                                                                                               	                                             
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>                                                        
							</div>

							

							
							
							

						</div>                                            
                       </div>                
					                      
<?php include ('final.inc.php');?>						