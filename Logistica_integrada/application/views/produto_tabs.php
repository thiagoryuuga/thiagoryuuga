<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include 'inicio.inc.php'?>
<style type="text/css">       
	.ui-widget-header{background: #B4D8EA; border: 1px solid #B4D8EA; color:#2E6E9E}	
	.ui-tabs .ui-tabs-panel{background-color: #E7F3F8; margin-top: 3px; }
	.dataTables_filter{text-align:left ;width: 145px;}
</style>
                	<div class="section table_section">                   
                        
                        <div class="title_wrapper">
                            <h2>Importação</h2>
                            <span class="title_wrapper_left"></span>
                            <span class="title_wrapper_right"></span>
                        </div>   
                                             
                        <div id="tabs_ajax_portaria" class="sct_right">
												
							<ul>
								<li><a href="#tab-1">Importação de produtos</a></li>
								<!--<li><a href="#tab-2">Gerar Distribuição</a></li>-->																												
							</ul>
														
							<div id="tab-1" style="padding: 0" class="section_content">                            
	                            <div class="sct">
	                                <div class="sct_left">
	                                    <div class="sct_right">
	                                        <div class="sct_left">
	                                            <div class="sct_right">	                                               							
	                                                <div class="table_wrapper">																								                                                                                                                                                       
	                                                    <?php $this->load->view('produto');?>                                                   	                                                   	                                                    
	                                                </div>                                                                                               	                                             
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>                                                        
							</div>
														
							                     
														
						</div>                                            
                                            
					                      
<?php include 'final.inc.php'?>						