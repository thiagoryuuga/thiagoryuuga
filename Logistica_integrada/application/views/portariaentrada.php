<?php include ('inicio.inc.php') ?>
					<div class="section">
						<!--[if !IE]>start title wrapper<![endif]-->
						<div class="title_wrapper">
							<h2>Logística</h2>
							
							<!--[if !IE]>start section menu<![endif]-->
							<!--<ul class="section_menu">
								<li><a href="#"><span><span>Inactive Tab</span></span></a></li>
								<li><a class="active" href="#"><span><span>Active Tab</span></span></a></li>
								<li><a href="#"><span><span>Products</span></span></a></li>
								<li><a href="#"><span><span>Last One</span></span></a></li>
							</ul>
							-->
							<!--[if !IE]>end section menu<![endif]-->
							
							
							<span class="title_wrapper_left"></span>
							
						<span class="title_wrapper_right" style="display: block;"></span></div>
						<!--[if !IE]>end title wrapper<![endif]-->
						<!--[if !IE]>start section content<![endif]-->
						<div class="section_content">
							<!--[if !IE]>start section content top<![endif]-->
							<div class="sct">
								<div class="sct_left">
									<div class="sct_right">
										<div class="sct_left">
										
										
										
											<div class="sct_right">
												<!--
												<h3>Subtitle will go here if needed</h3>
														
												<p>
													Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec blandit, nisl sit amet iaculis ullamcorper, orci tellus feugiat est, at dapibus massa dui vel lectus. Sed felis nunc, pharetra ullamcorper, fermentum nec, cursus nec, ipsum. Nunc porta blandit risus. Proin pharetra. Proin ultrices viverra lorem. Phasellus tellus enim, accumsan et, luctus vitae, mattis in, diam. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate, arcu consectetur auctor lacinia, justo est suscipit massa, a 
												</p>
												-->
												
												<!--[if !IE]>start system messages<![endif]-->
												
												<!--
												<ul class="system_messages">
													<li class="white"><span class="ico"></span><strong class="system_title">White bar can be used for system messages and other neutral things.</strong> defwe fwef we</li>
													<li class="red"><span class="ico"></span><strong class="system_title">This is a negative error message !</strong></li>
													<li class="blue"><span class="ico"></span><strong class="system_title">Blue bar can be used for tips, information etc.</strong></li>
													<li class="green"><span class="ico"></span><strong class="system_title">This is a positive message !</strong></li>
													<li class="yellow"><span class="ico"></span><strong class="system_title">This is a warning !</strong></li>
												</ul>
												-->
												<!--[if !IE]>end system messages<![endif]-->
												
												
												<!--[if !IE]>start forms<![endif]-->
												<form class="search_form general_form" action="#">
													<!--[if !IE]>start fieldset<![endif]-->
													<fieldset>
														<!--[if !IE]>start forms<![endif]-->
														<div class="forms" >
														
															<div class="row" style="width:30%;float:left;" >
															<label>Data:</label><span class="input_wrapper"><input class="text" name="data_atual" id="data_atual" type="text" /></span><br />
															<label>Transportadora:</label>
															
															<select name="id_trasportadora">			
																<option value="">selecione...</option>									
																<?php
																
																foreach ($trasportadoras as $rows) { 
																if ($rows['id_transportadora'] == $row['id_transportadora']){
																	$selecionado = " selected=\"selected\" ";
																} else {
																	$selecionado = "";
																}
																					
																?>
																	<option value="<?php echo $rows['id_transportadora']?>" <?php echo $selecionado ?>><?php echo $rows['nome_transportadora']?></option>									
																<?php }?>
															</select>
															<br/>
															
															
															
															<label>Estado (UF)</label>
															<?php
																$options = array ('' => 'Escolha');
																foreach($estados as $estado)
																	$options[$estado->id] = $estado->nome;
																echo form_dropdown('estado', $options);
															?>
															<br />
															<label>Cidade (Destino)</label>
															<?php echo form_dropdown('cidade', array('' => 'Escolha um Estado'), '','id="cidade"' ); ?>															
															<script type="text/javascript">
																var path = '<?php echo site_url(); ?>'
															</script>
															
															
															
															<br />
															<label>Veículo:</label><span class="input_wrapper"><input class="text" name="veiculo" type="text" /></span><br />
															<label>Placa:</label><span class="input_wrapper"><input class="text" name="placa_veiculo" type="text" /></span><br />
															<label style="width:120px;">Hora da Chegada:</label><span class="input_wrapper"><input class="text" name="" type="text" /></span>															
															</div>
															 
															<div style="width:30%;float:left;">

																<label>Cliente:</label>
																<select name="id_cliente">			
																	<option value="">selecione...</option>									
																	<?php
																	
																	foreach ($clientes as $rows) { 
																	if ($rows['id_cliente'] == $row['id_cliente']){
																		$selecionado = " selected=\"selected\" ";
																	} else {
																		$selecionado = "";
																	}
																						
																	?>
																		<option value="<?php echo $rows['id_cliente']?>" <?php echo $selecionado ?>><?php echo $rows['nome_cliente']?></option>									
																	<?php }?>
																</select>
															<br />
																<label>Codigo:</label><span class="input_wrapper"><input class="text" name="codigo" type="text" /></span><br />
																<label>Quantidade:</label><span class="input_wrapper"><input class="text" name="quantidade" type="text" /></span><br />
																<label>Nota Fiscal :</label><span class="input_wrapper"><input class="text" name="nota_fiscal" type="text" /></span><br />
																<label>Carga:</label><span class="input_wrapper"><input class="text" name="carga" type="text" /></span><br/>
																<label>Observação:</label><span class="input_wrapper"><input class="text" name="observacao" type="text" /></span>																
															</div>
															
															<div style="width:30%;float:left;">
															
																
																<label>Data:</label><span class="input_wrapper"><input class="text" name="" type="text" /></span><br />																
																<label>Hora Entrada:</label><span class="input_wrapper"><input class="text" name="" type="text" /></span><br />
																<label style="width:120px;">Faturamento: <input class="text" name="Sim" value="S" type="radio" />Sim</label>
															
															</div>
														
														
														</div>
														<!--[if !IE]>end forms<![endif]-->
														
													</fieldset>
													<!--[if !IE]>end fieldset<![endif]-->
													
													<div class="table_menu">
                                                    <ul class="left">
                                                        <li><a href="#" class="button add_new"><span><span>Cadastrar Caminhão</span></span></a></li>
                                                    </ul>
                                                    <ul class="right">
                                                        <li><a href="#" class="button check_all"><span><span>Encaminha para expedição</span></span></a></li>
                                                        
                                                        <li><span class="button approve"><span><span>Autorizar entrada</span></span></span></li>
                                                    </ul>
                                                </div>
													
													
												</form>
												<!--[if !IE]>end forms<![endif]-->	
												
												
														
												
												
												
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--[if !IE]>end section content top<![endif]-->
							<!--[if !IE]>start section content bottom<![endif]-->
							<span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
							<!--[if !IE]>end section content bottom<![endif]-->
							
						</div>
						<!--[if !IE]>end section content<![endif]-->
					</div>

                    <!--[if !IE]>start section<![endif]-->	
                    <div class="section table_section">
                        <!--[if !IE]>start title wrapper<![endif]-->
                        <div class="title_wrapper">
                            <h2>Todos</h2>
                            <span class="title_wrapper_left"></span>
                            <span class="title_wrapper_right"></span>
                        </div>
                        <!--[if !IE]>end title wrapper<![endif]-->
                        <!--[if !IE]>start section content<![endif]-->
                        <div class="section_content">
                            <!--[if !IE]>start section content top<![endif]-->
                            <div class="sct">
                                <div class="sct_left">
                                    <div class="sct_right">
                                        <div class="sct_left">
                                            <div class="sct_right">
                                                
                                                <form action="#">
                                                <fieldset>
												
												<div style="padding:25px;border:1px solid #7AA2B1; background-color:#B4D8EA;">
													<label>Data: </label><span class="input_wrapper"><input class="text" name="data_pesquisa" id="data_pesquisa" type="text" /></span>&nbsp;&nbsp;&nbsp;
													<label>Estado</label>
													<?php
														$options = array ('' => 'Escolha');
														foreach($estados as $estado)
															$options[$estado->id] = $estado->nome;
														echo form_dropdown('estado', $options);
													?>
													
													<label>Cidade</label>
													<?php echo form_dropdown('cidade', array('' => 'Escolha um Estado'), '','id="cidade"' ); ?>															
													<script type="text/javascript">
														var path = '<?php echo site_url(); ?>'
													</script>
													
													</span>&nbsp;&nbsp;&nbsp;
													<label>Transportadora: </label>
													<select name="id_trasportadora">			
																<option value="">selecione...</option>									
																<?php
																
																foreach ($trasportadoras as $rows) { 
																if ($rows['id_transportadora'] == $row['id_transportadora']){
																	$selecionado = " selected=\"selected\" ";
																} else {
																	$selecionado = "";
																}
																					
																?>
																	<option value="<?php echo $rows['id_transportadora']?>" <?php echo $selecionado ?>><?php echo $rows['nome_transportadora']?></option>									
																<?php }?>
													</select>
													
													<input type="submit" name="pesquisar"/>
													
												
												</div>
												<br />
                                                <!--[if !IE]>start table_wrapper<![endif]-->
                                                <div class="table_wrapper">
												
												
												
                                                    <div class="table_wrapper_inner">
                                                    <table cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody><tr>
                                                            
															<th><a href="#">Id</a></th>
															<th>Data</th>
                                                            <th><a href="#">Transportadora</a></th>
                                                            <th><a href="#" class="asc">Placa</a></th>
                                                            <th><a href="#" class="desc">UF</a></th>
                                                            <th><a href="#">Cidade</a></th>
                                                            
                                                            <th style="width: 96px;">Ação</th>
                                                        </tr>
                                                        <tr class="first">
                                                            <td>1.</td>
                                                            <td><span class="approved">03/05/2012</span></td>
                                                            <td>Express Cargas</td>
                                                            <td>FB-H061</td>
                                                            <td>Santa Catarina</td>
                                                            <td>PR</td>
                                                            <td style="width: 96px;">
                                                                <div class="actions">
                                                                    <ul>
                                                                        <li><a class="action1" href="#" title="visualizar">1</a></li>
                                                                        <!--<li><a class="action2" href="#">2</a></li>
                                                                        <li><a class="action3" href="#">3</a></li>
                                                                        <li><a class="action4" href="#">4</a></li>
                                                                        <li><input class="radio" name="" type="checkbox" value="" /></li>
																		-->
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                    </tbody></table>
                                                    </div>
                                                </div>
                                                <!--[if !IE]>end table_wrapper<![endif]-->
                                                
                                                <!--[if !IE]>start table menu<![endif]-->
                                                <!--
												<div class="table_menu">
                                                    <ul class="left">
                                                        <li><a href="#" class="button add_new"><span><span>ADD NEW</span></span></a></li>
                                                    </ul>
                                                    <ul class="right">
                                                        <li><a href="#" class="button check_all"><span><span>CHECK ALL</span></span></a></li>
                                                        <li><a href="#" class="button uncheck_all"><span><span>UNCHECK ALL</span></span></a></li>
                                                        <li><span class="button approve"><span><span>APPROVE</span></span></span></li>
                                                    </ul>
                                                </div>
												-->
                                                <!--[if !IE]>end table menu<![endif]-->
                                                
                                                
                                                </fieldset>
                                                </form>
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--[if !IE]>end section content top<![endif]-->
                            <!--[if !IE]>start section content bottom<![endif]-->
                            <span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
                            <!--[if !IE]>end section content bottom<![endif]-->
                            
                        </div>
                        <!--[if !IE]>end section content<![endif]-->
<?php include 'final.inc.php'?>						