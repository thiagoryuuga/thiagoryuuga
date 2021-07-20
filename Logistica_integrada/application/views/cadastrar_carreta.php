<?php include 'inicio.inc.php';?>
  
<style type="text/css">       
	.ui-widget-header{background: #B4D8EA; border: 1px solid #B4D8EA; color:#2E6E9E}	
</style>
<div class="section table_section">                   
    <div class="title_wrapper">
        <h2>Cadastro de Carretas</h2>
            <span class="title_wrapper_left"></span>
            <span class="title_wrapper_right"></span>
    </div> 
<?php if(@$msg){ ?>
    <ul class="system_messages">
	    <li class="green"><span class="ico"></span><strong class="system_title"><?php echo $msg;?></strong></li>
	</ul>
<?php } ?>   
<div id="tabs_ajax_carretas" class="sct_right">											
							<ul>								
								<li><a href="#tab-1">Entrada</a></li>
                                <!--<li><a href="#tab-2">Dados Cadastrados</a></li>-->
                               													
							</ul>                                             
                        <div id="tab-1" style="padding: 0" class="section_content">
                            <div class="sct" style="overflow-x:scroll;">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">                                                
                                               <div class="table_wrapper" >
													<div class="table_wrapper_inner" >                                                  
                                                    <?php $this->load->view('form_carreta')?>
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
				</div>
                    	                      
<?php include 'final.inc.php'?>						