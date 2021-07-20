
<script charset="utf-8">
	$(function(){
	$("#Limpar").click(function(){
		$("#TIPO_ATENDIMENTO").val("");
		$("#NUM_MATRICULA").val("");
		$("#COD_ATENDIMENTO").val("");
		$("#DEN_ATENDIMENTO").val("");
		$("#STATUS_ATENDIMENTO").val("1");
		$("#OBSERVACAO_ATENDIMENTO").val("");
		$(".readonly").val("");
		$("#Cadastrar").val("Cadastrar");
		$("#Cadastrar").html("Cadastrar");
		window.location.href = '<?php echo base_url();?>index.php/atendimento';
		
	});
	
	
	
	$("#Historico").click(function(){
		
		$.ajax({ url: "<?php echo site_url('atendimento/funcionario'); ?>",
			data: { NUM_MATRICULA: $("#NUM_MATRICULA").val()},
			dataType: "html",
			type: "POST",
			success: function(data){
				//alert($("#NUM_MATRICULA").val());
				$("#at_hist").add(data);
			}
		});
		
	});
	
	$("#record_length").html("Tabela");
	
	$("#atendForm").validate();
	
	if($("#COD_ATENDIMENTO").val() != ""){
		$("#Cadastrar").val("Alterar");
		$("#Cadastrar").html("Alterar");
	}
	
	if($.browser.msie == true){
		$("#form").css("height",400+'px');
	}
	
	var msn = '<?php if (isset($msn)) echo $msn;?>';
		if(msn == 'msn1'){
			alert('Cadastro realizado com sucesso.');
			window.location.href = '<?php echo base_url();?>'+'/index.php/atendimento';
		}else if(msn == 'msn2'){
			alert('Alteração realizado com sucesso.');
			window.location.href = '<?php echo base_url();?>'+'/index.php/atendimento';
		}
	var funcio = '<?php if(isset($funcionario)){if($funcionario == array()){ echo "1";}}?>';
		if(funcio == '1'){
			alert('Matricula esta incorreta ou não existe.');
		}
	
	$('.readonly').attr('readonly','readonly');
	
	$("#antlink").css('display','none');
	$('#NUM_MATRICULA').keyup(function(){
		$(".readonly").val("");
		if($('#NUM_MATRICULA').val() != ""){
			$("#antlink").css('display','inline');
			$("#link").css('display','none');
			$("#antlink").attr('href','<?php echo base_url();?>index.php/atendimento/funcionario/'+$('#NUM_MATRICULA').val());
		}else{
			$("#link").css('display','inline');
			$("#antlink").css('display','none');
		}
	});
	$('#NUM_MATRICULA').blur(function(){
		if($('#NUM_MATRICULA').val() != ""){
			$("#antlink").css('display','inline');
			$("#link").css('display','none');
			$("#antlink").attr('href','<?php echo base_url();?>index.php/atendimento/funcionario/'+$('#NUM_MATRICULA').val());
		}else{
			$("#link").css('display','inline');
			$("#antlink").css('display','none');
		}
	});
	
	var status = '<?php  if(isset($atend)) echo $atend[0]['STATUS_ATENDIMENTO'];?>';
	if(status != '1' && status != ""){
		$(".normal").attr('disabled','disabled');
	}
});
		
		
</script>
<style>
	body {
		margin: 0;
		padding: 0;
	}
	p {
		margin: 5px;
		height: 25px;
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
	
	#antlink, #link{
		background: url('<?php echo base_url();?>images/entrar-btn.png') no-repeat;
		line-height:20px;
		padding: 3px 13.5px 3px 13.5px;
		color: #FFF;
		font-family:Arial;
		font-size: 12px;
		font-weight: bold;
		width: 94px;		
	}
</style>
<?php $url = base_url(); ?>

<div class="tudo">
   <!--inicio da div section-->
   <div class="section" style="width:98.5%; margin-left:0.75%; margin-right:0.75%;">
      <!--inicio da div title_wrapper-->
     <div class="title_wrapper">
         <h2>Formulario</h2>
         <span class="title_wrapper_left"></span>
         <span class="title_wrapper_right"></span>
     <!--fim da div title_wrapper-->
     </div>
     <!--inicio da div section_content-->
     <div class="section_content">
       <!--[if !IE]>start section content top<![endif]-->
       <div class="sct">
          <div class="sct_left">
             <div class="sct_right">
                <div class="sct_left">
                    <div class="sct_right" align="center">
                    <!--[if !IE]>start dashboard menu<![endif]-->
                      <form action="<?php echo base_url() . 'index.php/atendimento/manter'; ?>" method="post" id="atendForm" accept-charset="UTF-8">
                      <?php $exist = false; if(isset($funcionario) && $funcionario != null){ $exist= true;}?>
                      <div style="float:none; position:relative; height:800px;" id="form">
                      <div id="colum_left" style="float:left; width:48%;" align="right">
                      	<div style="float:left; width:30%;" align="right">
							<p><label for="NUM_MATRICULA">Matricula: </label></p>
							<p><label for="NOME">Nome: </label></p>
							<p><label for="SETOR">Setor: </label></p>
							<p><label for="CARGO">Cargo: </label></p>
							<p><label for="DATA_ADMISSAO">Data Admiss&atilde;o: </label></p>
							<p><label for="IDENTIDADE">Identidade: </label></p>
                        </div>
                        <div style="float:right; width:69.5%;" align="left">
							<p><input class="required" type="text" name="NUM_MATRICULA" id="NUM_MATRICULA" size="10" value="<?php if($exist) echo $funcionario[0]['NUM_MATRICULA']; ?>"/>
							<a  id="link" rel="superbox[iframe][750x550]" href="<?php echo base_url();?>index.php/funcionario/index/tela/atendimento">
                                      PESQUISAR       	
                            </a>
                            <a  id="antlink"  href="">
                                      PESQUISAR
                            </a>
                            </p>
							<p><input type="text" class="readonly required" name="NOME" id="NOME" size="50" value="<?php if($exist) echo $funcionario[0]['NOM_COMPLETO']; ?>"/></p>
							<p><input type="text" class="readonly" name="SETOR" id="SETOR" size="50" value="<?php if($exist) echo $funcionario[0]['DEN_UNI_FUNCIO']; ?>"/></p>
							<p><input type="text" class="readonly" name="CARGO" id="CARGO" size="50" value="<?php if($exist) echo $funcionario[0]['DEN_CARGO']; ?>"/></p>
							<p><input type="text" class="readonly" name="DATA_ADMISSAO" id="DATA_ADMISSAO" size="10" value="<?php if($exist) echo $funcionario[0]['DAT_ADMIS']; ?>"/></p>
							<p><input type="text" class="readonly" name="IDENTIDADE" id="IDENTIDADE" size="20" value="<?php if($exist) echo $funcionario[0]['NUM_CART_IDENT']; ?>"/></p>
                        </div>
                      </div>
                       <div id="colum_right" style="float:right; width:51%;" align="left">
						<div style="float:left; width:18%;" align="right">
							<p><label for="CONTATO">Contato: </label></p>
							<p><label for="ENDERECO">Endere&ccedil;o: </label></p>
							<p><label for="BAIRRO">Bairro: </label></p>
							<p><label for="CIDADE">Cidade: </label></p>
							<p><label for="CPF">CPF: </label></p>
							<p><label for="PIS">PIS: </label></p>
                        </div>
                        <div style="float:right; width:81.5%;" align="left">
							<p><input type="text" class="readonly" name="CONTATO" id="CONTATO" size="20"  value="<?php if($exist) echo $funcionario[0]['NUM_TELEF_RES']; ?>"/></p>
							<p><input type="text" class="readonly" name="ENDERECO" id="ENDERECO" size="50" value="<?php if($exist) echo $funcionario[0]['END_FUNCIO']; ?>"/></p>
							<p><input type="text" class="readonly" name="BAIRRO" id="BAIRRO" size="50" value="<?php if($exist) echo $funcionario[0]['DEN_BAIRRO']; ?>"/></p>
							<p><input type="text" class="readonly" name="CIDADE" id="CIDADE" size="20" value="<?php if($exist) echo $funcionario[0]['DEN_CIDADE']; ?>"/></p>
							<p><input type="text" class="readonly" name="CPF" id="CPF" size="20" value="<?php if($exist) echo $funcionario[0]['NUM_CPF']; ?>"/></p>
							<p><input type="text" class="readonly" name="PIS" id="PIS" size="20" value="<?php if($exist) echo $funcionario[0]['NUM_PIS']; ?>"/></p>
                        </div>       
                       </div>
					   <div style="float:left; width:100%;" align="right"><hr/></div>
					 <div style="float:left; width:14.5%;" align="right">
						<p><label for="COD_TIPO_ATENDIMENTO">Tipo do Atendimento: </label></p>
						<p><label for="DEN_ATENDIMENTO">Assunto: </label></p>
						<p><label for="STATUS_ATENDIMENTO">Status: </label></p>
						<p><label for="OBSERVACAO_ATENDIMENTO">Observações: </label></p>
					 </div>
					 <div style="float:right; width:85.3%;" align="left">
						<input class="normal" type="hidden" name="COD_ATENDIMENTO" id="COD_ATENDIMENTO" value="<?php if(isset($atend)) echo $atend[0]['COD_ATENDIMENTO'];?>">
						<p><select class="norma required" name="TIPO_ATENDIMENTO" id="TIPO_ATENDIMENTO">
							<option value="">.:: Selecione ::.</option>
							<?php if(isset($tipo)){
									foreach($tipo as $tip){?>
							<option value="<?php echo $tip['COD_TIPO_ATENDIMENTO']?>" <?php if(isset($atend)){ if($atend[0]['TIPO_ATENDIMENTO'] == $tip['COD_TIPO_ATENDIMENTO']){ echo "selected='selected'";}}?>><?php echo utf8_encode($tip['DEN_TIPO_ATENDIMENTO']);?></option>
							<?php }} ?>
						</select></p>
						<p><input type="text" class="normal required" name="DEN_ATENDIMENTO" id="DEN_ATENDIMENTO" size="80" value="<?php if(isset($atend)) echo utf8_encode(getClob($atend[0]['DEN_ATENDIMENTO']));?>"/></p>
						<p><select class="normal" name="STATUS_ATENDIMENTO" id="STATUS_ATENDIMENTO">
							<option value="1" <?php if(isset($atend)){ if($atend[0]['STATUS_ATENDIMENTO'] == 1){echo "selected='selected'"; }};?>>Em Andamento</option>
							<option value="2" <?php if(isset($atend)){ if($atend[0]['STATUS_ATENDIMENTO'] == 2){echo "selected='selected'"; }};?>>Conclu&iacute;do</option>
							<option value="3" <?php if(isset($atend)){ if($atend[0]['STATUS_ATENDIMENTO'] == 3){echo "selected='selected'"; }};?>>Cancelado</option>
						</select></p>
						<p style="height: 55px;"><textarea class="normal" cols="98" rows="4" id="OBSERVACAO_ATENDIMENTO" name="OBSERVACAO_ATENDIMENTO"><?php if(isset($atend) && $atend[0]['OBSERVACAO_ATENDIMENTO'] != NULL) echo utf8_encode(getClob($atend[0]['OBSERVACAO_ATENDIMENTO']));?></textarea></p>
						<p>
                        <br/>
						<button class="normal" type="submit" id="Cadastrar"  name="Cadastrar" value="Cadastrar" />Cadastrar</button>
                        <button type="button" id="Limpar"  name="Limpar" value="Limpar" />Limpar</button>
                        <button type="button" id="Historico"  name="Historico" value="Historico" style="display:none;"/>Historico</button>
                        </p>
					 </div>
                     </div>
					</form>
                    <div id="at_hist"></div>
                    <!--[if !IE]>end dashboard menu<![endif]-->
                    </div>
                 </div>
              </div>
           </div>
        </div>
        <!--[if !IE]>end section content top<![endif]-->
        <!--[if !IE]>start section content bottom<![endif]-->
        <span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
        <!--[if !IE]>end section content bottom<![endif]-->
        <!--fim da div section_content-->   
     </div>
     <!--fim da div section--> 
   </div>
   <div id="datatables" style="width:98.5%; margin-left:0.75%;">
      <table cellpadding="0" cellspacing="0" border="0" class="display" id="record">
        <thead>
          <tr>
          	 <th>Matricula</th>
          	 <!--<th>Nome</th>-->
          	 <th>Data Atendimento</th>
          	 <th>Tipo Assunto</th>
             <th>Assunto</th>
             <th>Descrição</th>
             <th>Status</th>
             <th>A&ccedil;&otilde;es</th>
                       </tr>
        </thead>
        <tbody>
         <?php foreach($atendimentos as $row):?>
          <tr>
          <td align="center"><?php echo $row['NUM_MATRICULA']; ?></td> 
          	<!--	<td align="center"><?php echo $row['NOM_COMPLETO']; ?></td>-->
          	<td align="center"><?php echo toDate($row['DATA_INI_ATENDIMENTO']); ?></td>
          	<td align="center"><?php echo utf8_encode($row['DEN_TIPO_ATENDIMENTO']); ?></td>
            <td><?php echo utf8_encode(getClob($row['DEN_ATENDIMENTO']));?></td>
            <td><?php echo utf8_encode(getClob($row['OBSERVACAO_ATENDIMENTO']));?></td>
            <td align="center"><?php echo $row['STATUS']; ?></td>
            <td align="left" ><a href="<?php echo base_url() . 'index.php/atendimento/editar/' . $row['COD_ATENDIMENTO']; ?>"><img style="border:0px;" src="<?php echo base_url(); ?>images/tables/action2.gif"></a> 
			<!--<a href="<?php echo base_url() . 'index.php/atendimento/imprimir/' . $row['COD_ATENDIMENTO']; ?>"><img style="border:0px;" src="<?php echo base_url(); ?>images/tables/d9.gif"></a>-->
			<a href="<?php echo base_url() . 'index.php/atendimento/imprimir/' . $row['COD_ATENDIMENTO']; ?>" rel="superbox[iframe][800x600]"><img style="border:0px;" src="<?php echo base_url(); ?>images/tables/d9.gif"></a></td>
			
          </tr>
         <?php endforeach; ?>
        </tbody>
      </table>  
   </div> 
</div>
