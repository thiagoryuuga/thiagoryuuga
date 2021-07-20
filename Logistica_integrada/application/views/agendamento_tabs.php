<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php include 'inicio.inc.php' ?>
<style type="text/css">       
	.ui-widget-header{background: #B4D8EA; border: 1px solid #B4D8EA; color:#2E6E9E}	
	.ui-tabs .ui-tabs-panel{background-color: #E7F3F8; margin-top: 3px; }
	.dataTables_filter{text-align:left ; width:120px;}
</style>
<div class="section table_section">                   

	<div class="title_wrapper">
		<h2>Agendamento</h2>
		<span class="title_wrapper_left"></span>
		<span class="title_wrapper_right"></span>
	</div>   

	<div id="tabs_ajax_portaria" class="sct_right">

		<ul>
			<li><a href="#tab-1">Importação de dados</a></li>
			<li><a href="#tab-2">Ajustes de Carga</a></li>
			<li><a href="#tab-3">Asociação de Distribuição</a></li>
			<li><a href="#tab-4">Distribuições Geradas</a></li>
			
		</ul>
		
		<div id="tab-1" style="padding: 0" class="section_content">                            
			<div class="sct" id="sct_agendamento">
				<div class="sct_left">
					<div class="sct_right">
						<div class="sct_left">
							<div class="sct_right">	                                               							
								<div class="table_wrapper" style="overflow-x:auto;">
								                                               
									<?php $this->load->view('selecao_agendamento'); ?>                                                   	                                                   	                                                    
								</div>                                                                                               	                                             
							</div>
						</div>
					</div>
				</div>
			</div>                                                        
		</div>

		<div id="tab-2" style="padding: 0" class="section_content">                            
			<div class="sct" id="sct_agendamento">
				<div class="sct_left">
					<div class="sct_right">
						<div class="sct_left">
							<div class="sct_right">	                                               							
								<div class="table_wrapper" style="overflow-x:auto;">
																															                                                                                                                                                       
									<?php $this->load->view('agendamento'); ?>                                                   	                                                   	                                                    
								</div>                                                                                               	                                             
							</div>
						</div>
					</div>
				</div>
			</div>                                                        
		</div>

		


		<div id="tab-3" style="padding: 0" class="section_content">                            
			<div class="sct" id="sct_agendamento">
				<div class="sct_left">
					<div class="sct_right">
						<div class="sct_left">
							<div class="sct_right">	                                               							
								<div class="table_wrapper" style="overflow-x:auto;">
																											                                                                                                                                                       
									<?php $this->load->view('agendamento_gerar_distribuicao'); ?>                                                 	                                                   	                                                    
								</div>                                                                                               	                                             
							</div>
						</div>
					</div>
				</div>
			</div>                                                        
		</div>


		<div id="tab-4" style="padding: 0" class="section_content" >                            
			<div class="sct" id="sct_gerar_dist">
				<div class="sct_left">
					<div class="sct_right">
						<div class="sct_left">
							<div class="sct_right">	                                               							
								<div id="sct_table" class="table_wrapper" style="overflow-x:auto;">
									
									<?php $this->load->view('distribuicoes_geradas'); ?>  																								                                                                                                                                                         
								</div>                                                                                               	                                             
							</div>
						</div>
					</div>
				</div>
			</div>                                                        
		</div>
	</div>
</div>
	


		<?php include 'final.inc.php' ?>						