
<script charset="utf-8">
$(function(){
	
	$(".data").datepicker();
	$(".data").mask("99/99/9999");
	
	$("#Limpar").click(function(){
		window.location.href = '<?php echo base_url();?>index.php/afastamento/index/'+$("#COD_ATEND_SOCIAL").val();	
	});
	
	$("#Voltar").click(function(){
		window.location.href = '<?php echo base_url();?>index.php/acidente/editar/'+$("#COD_ATEND_SOCIAL").val();	
	});
	
	$("#record_length").html("Tabela");
	
	$("#atendForm").validate();
	
	if($("#COD_AFASTAMENTO").val() != ""){
		$("#Cadastrar").val("Alterar");
		$("#Cadastrar").html("Alterar");
	}
	
	if($.browser.msie == true){
		$("#form").css("height",470+'px');
		$("#.adicionarCampo").css('margin-right',668+'px');
		$("#.adicionarCampo").css('margin-top',-49+'px');
	}
	
	var msn = '<?php if (isset($msn)) echo $msn;?>';
		if(msn == 'msn1'){
			alert('Cadastro realizado com sucesso.');
			//window.location.href = '<?php echo base_url();?>'+'/index.php/afastamento';
		}else if(msn == 'msn2'){
			alert('Alteração realizado com sucesso.');
			//window.location.href = '<?php echo base_url();?>'+'/index.php/afastamento';
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
			$("#antlink").attr('href','<?php echo base_url();?>index.php/afastamento/funcionario/'+$('#NUM_MATRICULA').val());
		}else{
			$("#link").css('display','inline');
			$("#antlink").css('display','none');
		}
	});
	$('#NUM_MATRICULA').blur(function(){
		if($('#NUM_MATRICULA').val() != ""){
			$("#antlink").css('display','inline');
			$("#link").css('display','none');
			$("#antlink").attr('href','<?php echo base_url();?>index.php/afastamento/funcionario/'+$('#NUM_MATRICULA').val());
		}else{
			$("#link").css('display','inline');
			$("#antlink").css('display','none');
		}
	});
	
	var status = '<?php  if(isset($atend)) echo $atend[0]['STATUS_AFASTAMENTO'];?>';
	if(status != '1' && status != ""){
		$(".normal").attr('disabled','disabled');
	}
	
	
});
$(function () {
var count;
var rep = '<?php if(isset($pericias)){ echo count($pericias);}?>';
var num_pericia = (rep*1);
if(rep != ""){
	$("#form").height($("#form").height()+(rep * 105));
	count = (rep*1)+1;
}else{
	count = 1;
	num_pericia = 1;
}
var most = '<?php if(isset($pericias)) { echo 1;}?>'; 
if(most == '1'){
	$(".removerCampo").show();
}else{
	$(".removerCampo").hide();
}
$("#NUM_PERICIA").val(1);	

$(".removerCampo").hide();
$(".removerCampo:last").show();
	function removeCampo() {
		$(".removerCampo").unbind("click");
		$(".removerCampo").bind("click", function () {	
			i= 0;
			$(".campos p.campoPeriento").each(function () {	
				i++;
			});
			if (i>1) {	
				$(this).parent().remove();
				$(".removerCampo:last").show();
				$("#form").height($("#form").height()-105);
				$("p.pericia:first").remove();
				$("p.peri:first").remove();
				num_pericia = num_pericia - 1;
				if(i == 2){
					$(".removerCampo").hide();
				}		
			}	
		});	
	}	
	
	removeCampo();	
	$(".adicionarCampo").click(function () {
		$("#form").height($("#form").height()+105);
		$(".removerCampo").show();
		campoPericia = $("p.pericia:first").clone();
		campoPericia.insertAfter("p.peri:last");
		campoPeri = $("p.peri:first").clone();
		campoPeri.insertAfter("p.pericia:last");
		novoCampo = $(".campos p.campoPeriento:first").clone();	
		novoCampo.find("input").val("");
		novoCampo.find("textarea").val("");
		novoCampo.find("input#DATA_PERICIA1").addClass('data');
		novoCampo.find("input#DATA_PERICIA1").attr('id','DATA_PERICIA'+count);
		novoCampo.find("input#DATA_CONSECAO_INDEFERIMENTO1").addClass('data');
		novoCampo.find("input#DATA_CONSECAO_INDEFERIMENTO1").attr('id','DATA_CONSECAO_INDEFERIMENTO'+count);
		num_pericia = num_pericia + 1;
		novoCampo.find("input#NUM_PERICIA").val(num_pericia);	
		novoCampo.insertAfter(".campos p.campoPeriento:last");
		$(".removerCampo").hide();
		novoCampo.find(".removerCampo").show();
		
		$(".data").mask("99/99/9999");
		count++;
		removeCampo();

	});
	
	$(".data").live("click", function() {
        $(this)
            .removeClass("hasDatepicker")
            .datepicker({showOn:"focus"})
            .focus();
    });
	
		
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
	.adicionarCampo{
		float:right;
		margin-top:-32px;
		margin-right:570px;
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
                      <form action="<?php echo base_url() . 'index.php/afastamento/manter'; ?>" method="post" id="atendForm" accept-charset="UTF-8">
                      <?php $exist = false; if(isset($funcionario) && $funcionario != null){ $exist= true;}?>
                      <div style="float:none; position:relative; height:480px;" id="form">
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
							<p><input class="required readonly" type="text" name="NUM_MATRICULA" id="NUM_MATRICULA" size="10" value="<?php if($exist) echo $funcionario[0]['NUM_MATRICULA']; ?>"/>
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
						<p><label for="DEN_AFASTAMENTO">N&deg; Beneficio: </label></p>
						<p style="height:60px;"><label for="OBSERVACAO_AFASTAMENTO">Observações Gerais: </label></p>
                        <hr/>
                        <?php if(isset($pericias)) {
									 foreach($pericias as $per){
									  ?>
                        <p class="pericia" style="position:relative; z-index:1;"><label for="COD_PERICIA">Nº Pericia: </label></p>
                        <p class="peri" style="height:70px; position:relative; z-index:1;"><label for="OBSERVACAO">Observação: </label></p>
                        <?php }}else{?>
                        <p class="pericia" style="position:relative; z-index:1;"><label for="COD_PERICIA">Nº Pericia: </label></p>
                        <p class="peri" style="height:70px; position:relative; z-index:1;"><label for="OBSERVACAO">Observação: </label></p>
                        <?php }?>
					 </div>
					 <div style="float:right; width:85.3%;" align="left">
                     	<?php $exist = false; if(isset($afastamentos) && $afastamentos != array()){ $exist = true;}?>                     
						<input class="normal" type="hidden" name="COD_AFASTAMENTO" id="COD_AFASTAMENTO" value="<?php if($exist) echo $afastamentos[0]['COD_AFASTAMENTO'];?>">
                        <input class="normal" type="hidden" name="COD_ATEND_SOCIAL" id="COD_ATEND_SOCIAL" value="<?php if(isset($COD_ATEND_SOCIAL)){ echo $COD_ATEND_SOCIAL;} else { echo $this->uri->segment(3);}?>">
					 <p><input type="text" class="normal required" name="NUM_BENEFICIO" id="NUM_BENEFICIO" size="20" value="<?php if($exist) echo $afastamentos[0]['NUM_BENEFICIO'];?>"/></p>
					 <p style="height: 60px;"><textarea class="normal" cols="95" rows="3" id="OBSERVACOES" name="OBSERVACOES"><?php if($exist && $afastamentos[0]['OBSERVACOES'] != NULL) echo getClob($afastamentos[0]['OBSERVACOES']);?></textarea></p>
                     <hr/>
                        <div id="campos" class="campos" style="position:relative; z-index:1; margin-top:10px;">
                                 <?php if(isset($pericias)) {
									 $i = 1;
									 foreach($pericias as $per){
									  ?>
                                  <p class="campoPeriento" style="height:100px;">
                                  <input class="normal" type="hidden" name="COD_PERICIA[]" id="COD_PERICIA" value="<?php echo $per['COD_PERICIA'];?>">
                                  <input type="text" class="normal required readonly" name="NUM_PERICIA[]" id="NUM_PERICIA" size="2" value="<?php echo $per['NUM_PERICIA'];?>"/> &nbsp;<label for="DATA_PERICIA">Data Pericia: </label>
                                 <input type="text" class="required data" name="DATA_PERICIA[]" id="DATA_PERICIA<?php echo $i;?>" size="8" value="<?php echo $per['DATA_PERICIA'];?>"/> &nbsp;<label for="DECISAO">Decisão: </label>
                                   <select id="DECISAO" name="DECISAO[]">
                                   	<option <?php if($per['DECISAO'] == "D"){echo "selected='selected'";};?> value="D">Deferido</option>
                                    <option <?php if($per['DECISAO'] == "I"){echo "selected='selected'";};?> value="I">Indeferido</option>
                                   </select>
                                 &nbsp;&nbsp;<label for="DATA_CONSECAO_INDEFERIMENTO">Data Decisão: </label>
                                 <input type="text" class="required data" name="DATA_CONSECAO_INDEFERIMENTO[]" id="DATA_CONSECAO_INDEFERIMENTO<?php echo $i;?>" size="8" value="<?php echo $per['DATA_CONSECAO_INDEFERIMENTO'];?>" style="margin-bottom:5px;"/><br/>
                                  <textarea class="normal" cols="95" rows="3" id="OBSERVACAO" name="OBSERVACAO[]"><?php if($per['OBSERVACAO'] != NULL) echo getClob($per['OBSERVACAO']);?></textarea>
                                   <a href="#campos" class="removerCampo"><img  src="<?php echo base_url();?>images/del.png" title="Remover Campo" /></a>
                          </p>
							<?php $i++;} }else{ ?>
                                <p class="campoPeriento" style="height:100px;">
                                  <input type="text" class="required readonly" name="NUM_PERICIA[]" id="NUM_PERICIA" size="2" value=""/> &nbsp;<label for="DATA_PERICIA">Data Pericia: </label>
                                 <input type="text" class="required data" name="DATA_PERICIA[]" id="DATA_PERICIA1" size="8" value=""/> &nbsp;<label for="DECISAO">Decisão: </label>
                                   <select id="DECISAO" name="DECISAO[]">
                                   	<option value="D">Deferido</option>
                                    <option value="I">Indeferido</option>
                                   </select>
                                 &nbsp;&nbsp;<label for="DATA_CONSECAO_INDEFERIMENTO">Data Decisão: </label>
                                 <input type="text" class="required data" name="DATA_CONSECAO_INDEFERIMENTO[]" id="DATA_CONSECAO_INDEFERIMENTO1" size="8" value="" style="margin-bottom:5px;"/><br/>
                                  <textarea class="normal" cols="95" rows="3" id="OBSERVACAO" name="OBSERVACAO[]"></textarea>
                                   <a href="#campos" class="removerCampo"><img  src="<?php echo base_url();?>images/del.png" title="Remover Campo" /></a>
                          <span class="novo"></span>
                          </p>
                                 <?php }?>
                                 <a href="#campos" class="adicionarCampo"><img  src="<?php echo base_url();?>images/add.png" title="Adicionar Campo" /></a> &nbsp;
                       </div>
						<p>
                        <button class="normal" type="submit" id="Cadastrar"  name="Cadastrar" value="Cadastrar" />Cadastrar</button>
                        <button type="button" id="Limpar"  name="Limpar" value="Limpar" />Limpar</button> &nbsp;
                          <button type="button" id="Voltar"  name="Voltar" value="Voltar" />Voltar</button>
                        </p>
					 </div>
                     </div>
					</form>
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
</div>
