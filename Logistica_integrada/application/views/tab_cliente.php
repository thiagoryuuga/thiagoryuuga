<?php include 'inicio.inc.php'?>					
<div class="section table_section">						
	<div class="title_wrapper">
		<h2>Clientes - Cadastro de clientes</h2>
		<span class="title_wrapper_left"></span>
		<span class="title_wrapper_right"></span>
	</div>						
	<div class="section_content">                          
		<div class="sct">
			<div class="sct_left">
				<div class="sct_right">
					<div class="sct_left">
						<div id="" class="sct_right">								
							<form class="search_form general_form" action="<?php echo base_url()?>index.php/cliente/cadastrar" method="post">									
								<fieldset>	
									<?php if (!empty($msgOK)) { ?>
										<div style="padding:15px; margin: 15px; background: #85E28C; border:1px solid green;"><?php echo $msgOK?></div>
									<?php } ?>
									<div class="forms" >											
										<div class="row" style="width:700px;float:left;padding:15px;" >
											<h3>Informações do Cliente</h3><br />
											<label>CNPJ/CPF </label><span class="input_wrapper" style="width:300px;"><input class="text" name="CGCCPF_CLIENTE" value="<?php echo $var = empty($row['CGCCPF_CLIENTE']) ? "" : $row['CGCCPF_CLIENTE'] ;?>" type="text" /></span>(somente números)<br /><font style="color:red;"><?php echo form_error('CGCCPF_CLIENTE'); ?></font><br />
											<label>Nome do Cliente</label><span class="input_wrapper" style="width:500px;"><input class="text" name="NOME_CLIENTE" value="<?php echo $var = empty($row['NOME_CLIENTE']) ? "" : utf8_encode($row['NOME_CLIENTE']) ;?>" type="text" /></span><br /><font style="color:red;"><?php echo form_error('NOME_CLIENTE'); ?></font><br />											
											<input type="hidden" name="ID_CLIENTE" id="ID_CLIENTE" value="<?php echo $var = empty($row['ID_CLIENTE']) ? "" : $row['ID_CLIENTE'] ;?>"/>
											<input type="submit" value="cadastrar">
										</div>																					
									</div>										
								</fieldset>																													
							</form>	

						<div class="table_wrapper">
							
							<div id="table_wrapper_inner" class="table_wrapper_inner">
								<table cellpadding="0" cellspacing="0" width="100%" border="0"  id="clientes">
								
									<thead>
										<tr>
											<th><a href="#">NOME DO CLIENTE</a></th>
											<th><a href="#">CNPJ / CPF</a></th>                                                            											
											<th><a href="#">AÇÃO</a></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($clientes as $cliente) {?>
										<tr class="first">
											<td><?php echo utf8_encode($cliente['NOME_CLIENTE'])?></td>
											<td><?php echo $cliente['CGCCPF_CLIENTE']?></td>											
											<td style="width: 96px;">																			
												
														<a class="" href="<?php echo base_url()?>index.php/cliente/detalhar/<?php echo $cliente['ID_CLIENTE'] ?>" title="visualizar">[Editar]</a>
																														
											</td>
											
										</tr>
									<?php } ?>										
									</tbody>
								</table>
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