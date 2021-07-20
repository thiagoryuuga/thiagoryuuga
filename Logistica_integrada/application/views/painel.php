<?php include_once 'inicio.inc.php';?>
  
<style type="text/css">       
	.ui-widget-header{background: #B4D8EA; border: 1px solid #B4D8EA; color:#2E6E9E}	
</style>
                    <div class="table_section">                   
                        <div class="title_wrapper">
                            <h2>Painel - Acompanhamento de Cargas</h2>
                            <span class="title_wrapper_left"></span>
                            <span class="title_wrapper_right"></span>
                        </div>                      
						                   
                         <!--<div id="tabs_ajax_portaria" class="sct_right">-->											
							<!--<ul>-->
								<!--<li><a href="#tab-1">Acompanhamento</a></li>-->
								<!--<li><a href="#tab-2">Entrada</a></li>
								<li><a href="#tab-3">Docas</a></li>
                                <li><a href="#tab-4">Saída</a></li>-->
                                																	
							<!--</ul> -->                                            
                                                
                        <div id="tab-1" class="section_content">                            
                            <div class="sct" style="overflow-x:scroll;">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">
                                                <div class="table_wrapper" >																							
                                                    <div class="table_wrapper_inner" style="height:700px;">                                                                                                        
                                                    	<?php $this->load->view('painel_chegada');?>                                                    	                                                   
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                                      
                            <span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
                        <!--</div>-->                        
					

					<!--<div id="tab-2" style="padding: 0" class="section_content">                            
                            <div class="sct">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">
                                                <div class="table_wrapper">																							
                                                    <div class="table_wrapper_inner">                                                                                                        
                                                    	<?php $this->load->view('painel_entrada');?>                                                    	                                                   
                                                    </div>
                                                </div>                                                                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                                                      
                            <span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
                     </div>-->

                     <!--<div id="tab-3" style="padding: 0" class="section_content">                            
                            <div class="sct">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">
                                                <div class="table_wrapper">																							
                                                    <div class="table_wrapper_inner">                                                                                                        
                                                    	 <?php $this->load->view('painel_docas');?>                                                  	                                                   
                                                    </div>
                                                </div>                                                                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>--> 
                            
                        <!--<div id="tab-4" style="padding: 0" class="section_content">                            
                            <div class="sct">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">
                                                <div class="table_wrapper">                                                                                         
                                                    <div class="table_wrapper_inner">                                                                                                        
                                                        <?php $this->load->view('painel_saida');?>                                                                                                         
                                                    </div>
                                                </div>                                                                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    <!--<span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>-->
            </div>                        
		</div>                    	                      
<?php include 'final.inc.php'?>						