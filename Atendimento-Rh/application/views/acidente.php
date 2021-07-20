
<script charset="utf-8">
$(function(){
	
//------- Função Limpar ---------//		
	$(".limpar").click(function(){
		window.location.href = '<?php echo base_url();?>index.php/acidente';
	});
//------------------------------//
	
//------- Acrescentar label no lugar de quantidade de resultados na tabela --------//
	$("#record_length").html("Atendimento");
	$("#record1_length").html("Acompanhamento");
//---------------------------------------------------------------------------------//

//------- Validar campos obrigatorios no formulario ---------//	
	//$("#atendForm").validate();
//-----------------------------------------------------------//

//------- Mudar label do campo cadastra para alterar caso exista o id ----------//	
	if($("#COD_ACOMPANHAMENTO").val() != ""){
		$("#cadastrar").html("&ensp;&ensp;Alterar&ensp;&ensp;&nbsp;");
	}
//-----------------------------------------------------------------------------//

//------- Verifa se o browser é o IE para aplicar o tamanho da div ------------//
	if($.browser.msie == true){
		$("#form").css("height", 200+'px');
		$("#.adicionarCampo").css('margin-right',578+'px');
		$("#.adicionarCampo").css('margin-top',-30+'px');
	}
//-----------------------------------------------------------------------------//

//------ De acorddo com o numero de mensagem que vem do controller mostra uma mensagem --------//	
	var msn = '<?php if (isset($msn)) echo $msn;?>';
		if(msn == 'msn1'){
			alert('Cadastro realizado com sucesso.');
			window.location.href = '<?php echo base_url();?>'+'/index.php/acidente';
		}else if(msn == 'msn2'){
			alert('Alteração realizado com sucesso.');
			window.location.href = '<?php echo base_url();?>'+'/index.php/acidente';
		}
	var funcio = '<?php if(isset($funcionario)){if($funcionario == array()){ echo "1";}}?>';
		if(funcio == '1'){
			alert('Matricula esta incorreta ou não existe.');
		}
//--------------------------------------------------------------------------------------------//
			
//------- Seta os campos com a class readonly para somente leitura -------//
	$('.readonly').attr('readonly','readonly');
//------------------------------------------------------------------------//

//------- chama o modal ou nção no caso do campo de pesquisa esta vazio ou não --------//
	$("#antlink").css('display','none');
	$('#NUM_MATRICULA').keyup(function(){
		$(".readonly").val("");
		if($('#NUM_MATRICULA').val() != ""){
			$("#antlink").css('display','inline');
			$("#link").css('display','none');
			$("#antlink").attr('href','<?php echo base_url();?>index.php/acidente/funcionario/'+$('#NUM_MATRICULA').val());
		}else{
			$("#link").css('display','inline');
			$("#antlink").css('display','none');
		}
	});
	$('#NUM_MATRICULA').blur(function(){
		if($('#NUM_MATRICULA').val() != ""){
			$("#antlink").css('display','inline');
			$("#link").css('display','none');
			$("#antlink").attr('href','<?php echo base_url();?>index.php/acidente/funcionario/'+$('#NUM_MATRICULA').val());
		}else{
			$("#link").css('display','inline');
			$("#antlink").css('display','none');
		}
	});
//-----------------------------------------------------------------------------------//

//------- verifica qual o status para que possa setar os campos somente leitura -------//
	var status = '<?php  if(isset($aciden)) echo $aciden[0]['STATUS_OCORRENCIA'];?>';
	if(status != '1' && status != ""){
		$(".normal").attr('disabled','disabled');
		$("#alterar").css('display','none')
		$("#cadastrar").css('display','none');
		$('#afastamento').css('display','none');
	}
//------------------------------------------------------------------------------------//

//------- Cria as mascaras dos campos data e seta o calendario para selecionar o dia --------//
	$("#DATA_OCORRENCIA" ).datepicker();
	$("#DATA_OCORRENCIA").mask('99/99/9999');
	$("#ALTA_MEDICA" ).datepicker();
	$("#ALTA_MEDICA").mask('99/99/9999');
	$("#RETORNO_TRABALHO" ).datepicker();
	$("#RETORNO_TRABALHO").mask('99/99/9999');
	$("#DATA_PROX_ACOMPANHAMENTO" ).datepicker();
	$("#DATA_PROX_ACOMPANHAMENTO").mask('99/99/9999');
	$("#VALOR_MEDICAMENTO").maskMoney({decimal:",", thousands:"."});
//-----------------------------------------------------------------------------------------//

//------- Cria as abas -------//			
	$( "#tabs" ).tabs();
//----------------------------//

//------- Chama a função de trocar a aba ativa --------//
	$("#prosseguir").click(function(){
			$("#acomp").click();	
	});
//-----------------------------------------------------//

//-------- Mostra a tabela de atendimento e oculta a tabela de acompanhamento ------------//
	$("#datatables").css('display','inline');
	$("#datatables_acomp").css('display','none');
//----------------------------------------------------------------------------------------//

//------- Se for editado o acompanhamento -------------//
	var acompa = "<?php if(isset($acom)) echo "1";?>";
	if(acompa == "1"){
		$("#acomp").click();
		$("#datatables").css('display','none');
		$("#datatables_acomp").css('display','inline');
	}
//-----------------------------------------------------//


//------- Realiza validações para poder trocar as abas --------//
	$("#acomp").click(function(){
		
		$("#datatables").css('display','none');
		$("#datatables_acomp").css('display','inline');
		
		var tipo = $("#TIPO_ATENDIMENTO").val();
		var ocorrencia = $("#OCORRENCIA").val();
		var data_ocor = $("#DATA_OCORRENCIA").val();
		
		if(tipo == ""){$("#tipo_req").html(" Este campo é necessário.");}else{$("#tipo_req").html("");}
		if(ocorrencia == ""){$("#ocor_req").html(" Este campo é necessário.");	}else{$("#ocor_req").html("");}
		if(data_ocor == ""){$("#data_req").html(" Este campo é necessário.");	}else{$("#data_req").html("");}
			
		
		if(ocorrencia == "" || data_ocor == "" || tipo == ""){		
			$("#acid").click();
		}
	});
	
	$("#ENCAMINHAMENTO").click(function(){
		if($("#ENCAMINHAMENTO").is(':checked') == true){
			$(".hospital").css('display','inline');
		}else{
			$(".hospital").css('display','none');
		}
	});

	var enc = '<?php if(isset($acom)){ echo $acom[0]['ENCAMINHAMENTO'];}?>';
	//alert(enc);
	if(enc != 'S'){$(".hospital").css('display','none');}
	else{$(".hospital").css('display','inline');}
	
	
//-------------------------------------------------------------//

//--------- Ao clicar na aba de acidente muda a tabela exibida -------//
	$("#acid").click(function(){
		$("#datatables").css('display','inline');
		$("#datatables_acomp").css('display','none');
	});
//--------------------------------------------------------------------//

//------- Função do bortão voltar para a aba de atendimento --------//		
	$("#voltar").click(function(){
		$("#acid").click();
	});
//------------------------------------------------------------------//	
	
// ------ Submete o formulario par a pagina de controle ------------//
	$("#cadastrar").click(function(){
		$("#atendForm").validate();
		$("#atendForm").submit();
	});
	
	$("#alterar").click(function(){
		$("#atendForm").submit();
	});
//-------------------------------------------------------------------//

//------- Limpa os campos do acompanhamento -------------------------//
	$("#limp").click(function(){
		$("#COD_ACOMPANHAMENTO").val("");
		$("#HOSPITAL").val("");
		$("#MEDICO").val("");
		$("#DATA_PROX_ACOMPANHAMENTO").val("");
		$("#OBSERVACOES_ACOMPANHAMENTO").val("");
		$("#DEN_MEDICAMENTO").val("");
		$(".medicamento").val("");
	})
//-------------------------------------------------------------------//	

//------ Chama a tela de afastamento --------------------------------//
	$("#afastamento").click(function(){
		if($("#COD_ATEND_SOCIAL").val() != ""){
			window.location.href = "<?php echo base_url().'index.php/afastamento/index/';?>" + $("#COD_ATEND_SOCIAL").val();
		}else{
			alert("Escolha um Atendimento para gerar o Afastamento");
		}
	});
//-------------------------------------------------------------------//
});	

 	

//------ Acrescentar/remover campos de medicamento e de custo -------//
$(function () {
$(".removerCampo").hide();	
var count = 1;
	function removeCampo() {
		$(".removerCampo").unbind("click");
		$(".removerCampo").bind("click", function () {	
			i= 0;
			$(".campos p.campoMedicamento").each(function () {	
				i++;
			});
			if (i>1) {	
				$(this).parent().remove();
				$("#acompanhamento").height($("#acompanhamento").height()-25);
				$("p.medicam:first").remove();
				if(i == 2){
					$(".removerCampo").hide();
				}		
			}	
		});	
	}	
	
	removeCampo();	
	$(".adicionarCampo").click(function () {
		$("#acompanhamento").height($("#acompanhamento").height()+25);
		$(".removerCampo").show();
		campoMedicam = $("p.medicam:first").clone();
		campoMedicam.insertAfter("p.medicam:last");
		novoCampo = $(".campos p.campoMedicamento:first").clone();	
		novoCampo.find("input").val("");	
		novoCampo.find("input#VALOR_MEDICAMENTO").attr('id','VALOR_MEDICAMENTO'+count);
		novoCampo.find("input#VALOR_MEDICAMENTO"+count).maskMoney({decimal:",", thousands:"."});	
		//novoCampo.find("input#VALOR_MEDICAMENTO"+count).val("");
		novoCampo.insertAfter(".campos p.campoMedicamento:last");
		count++;
		removeCampo();
	});	
});
//-------------------------------------------------------------------------//	


$(function(){
//-------- Faz aparecer o botão menos na edição do acompanhamento --------------------------------//
	var medicamento = "<?php if(isset($medicamentos) && count($medicamentos) > 0) echo "1";?>";
	if(medicamento == "1"){
		$(".removerCampo").show();
	}
//-----------------------------------------------------------------------------------------------//
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

	#record_length, #record1_length {
		font-size: 20px;
		font-weight: normal;
		line-height: 35px;
		margin: 0 0 0 13px;;
		padding: 0;
	}
	
	#antlink, #link, .btn_link{
		background: url('<?php echo base_url();?>images/entrar-btn.png') no-repeat;
		line-height:20px;
		padding: 3px 13.5px 3px 13.5px;
		color: #FFF !important;
		font-family:Arial;
		font-size: 12px;
		font-weight: bold;
		width: 94px;
		text-decoration:none;
		cursor:pointer;		
	}
	.requerido{
		color: red;
		font-weight: bold;
		font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	.adicionarCampo{
		float:right;
		margin-top:-29px;
		margin-right:500px;
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
                      <form action="<?php echo base_url() . 'index.php/acidente/manter'; ?>" method="post" id="atendForm" accept-charset="UTF-8">
                      <?php $exist = false; if(isset($funcionario) && $funcionario != null){ $exist= true;}?>
                      <div style="float:none; position:relative; height:180px;" id="form">
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
							<a  id="link" rel="superbox[iframe][750x550]" href="<?php echo base_url();?>index.php/funcionario/index/tela/acidente">
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
                
                     </div>
					
                     <div id="tabs" style="position:relative; height:auto; margin-bottom:20px; width:100%;">
                        <ul>
                            <li><a id="acid" href="#acidente" >Atendimento</a></li>
                            <li><a id="acomp" href="#acompanhamento" >Acompanhamento</a></li>
                        </ul>
                        <div id="acidente" style="border:#6A97A7 1px solid; height:300px;" >
                             <div style="float:left; width:14.5%;" align="right">
                                <p><label for="TIPO_ATENDIMETO">Tipo do Atendimento: </label></p>
                                <p><label for="OCORRENCIA">Assunto: </label></p>
                                <p><label for="STATUS_OCORRENCIA">Status: </label></p>
                                <p style="height:78px;"><label for="RELATO_OCORRENCIA">Relato: </label></p>
                                <p><label for="DEN_CID">Descrição do CID: </label></p>
                                <p><label for="DATA_OCORRENCIA">Data da Ocorrência: </label></p>
                             </div>
                             <div style="float:right; width:85.3%;" align="left">
                                <input class="normal" type="hidden" name="COD_ATEND_SOCIAL" id="COD_ATEND_SOCIAL" value="<?php if(isset($aciden)) echo $aciden[0]['COD_ATEND_SOCIAL'];?>">
                                <p><select class="normal required" name="TIPO_ATENDIMENTO" id="TIPO_ATENDIMENTO">
                                    <option value="">.:: Selecione ::.</option>
                                    <option <?php if(isset($aciden)) if($aciden[0]['STATUS_OCORRENCIA'] == 1) echo "selected='selected'";?> value="1">Acidente</option>
                                    <option  <?php if(isset($aciden)) if($aciden[0]['STATUS_OCORRENCIA'] == 2) echo "selected='selected'";?> value="2">Doença</option>
                                </select><span class="requerido" id="tipo_req"></span></p>
                                
                                <p><input type="text" class="normal" name="OCORRENCIA" id="OCORRENCIA" size="80" value="<?php if(isset($aciden)) echo utf8_encode(getClob($aciden[0]['OCORRENCIA']));?>"/><span class="requerido" id="ocor_req"></span></p>
                                <p><select class="normal" name="STATUS_OCORRENCIA" id="STATUS_OCORRENCIA">
                                    <option value="1" <?php if(isset($aciden)){ if($aciden[0]['STATUS_OCORRENCIA'] == 1){echo "selected='selected'"; }};?>>Em Andamento</option>
                                    <option value="2" <?php if(isset($aciden)){ if($aciden[0]['STATUS_OCORRENCIA'] == 2){echo "selected='selected'"; }};?>>Conclu&iacute;do</option>
                                    <option value="3" <?php if(isset($aciden)){ if($aciden[0]['STATUS_OCORRENCIA'] == 3){echo "selected='selected'"; }};?>>Cancelado</option>
                                </select></p>
                                <p style="height:42px;"><textarea class="normal" cols="81" rows="3" id="RELATO_OCORRENCIA" name="RELATO_OCORRENCIA"><?php if(isset($aciden) && $aciden[0]['RELATO_OCORRENCIA'] != NULL) echo utf8_encode(getClob($aciden[0]['RELATO_OCORRENCIA']));?></textarea></p>
                                <p></p>                      
                                <p><input type="text" class="normal"  name="DEN_CID" id="DEN_CID" size="80" value="<?php if(isset($aciden)) echo utf8_encode(getClob($aciden[0]['DEN_CID']));?>"/></p>
                                <p><input type="text" class="normal"  name="DATA_OCORRENCIA" id="DATA_OCORRENCIA" size="10" value="<?php if(isset($aciden)) echo $aciden[0]['DATA_OCORRENCIA'];?>"/>&nbsp;
                                 <label for="ALTA_MEDICA">Alta Médica: </label><input type="text" class="required" name="ALTA_MEDICA" id="ALTA_MEDICA" size="10" value="<?php if(isset($aciden)) echo $aciden[0]['ALTA_MEDICA'];?>"/>&nbsp;
                                <label for="RETORNO_TRABALHO">Retorno ao Trabalho: </label><input type="text" class="required" name="RETORNO_TRABALHO" id="RETORNO_TRABALHO" size="10" value="<?php if(isset($aciden)) echo $aciden[0]['RETORNO_TRABALHO'];?>"/>
                               </p>
                               <span class="requerido" id="data_req"></span>
                                <!--<button class="normal" type="submit" id="Prosseguir"  name="Prosseguir" value="Prosseguir" />Prosseguir</button>
                                <button type="button" id="Limpar"  name="Limpar" value="Limpar" />Limpar</button>-->
                               <br/>
                               <p>
                               <?php if(isset($aciden)){ ?>
                               <a  class="btn_link" id="alterar">
                                     &nbsp;&nbsp; &nbsp;&nbsp;Alterar&nbsp;&nbsp;&nbsp;&nbsp;
                            	</a>
                                &nbsp;
                                <?php }?>
                                <a  class="btn_link" id="prosseguir">
                                      Prosseguir&nbsp;
                            	</a>
                                &nbsp;
                                <a  class="btn_link" id="afastamento" >
                                      Afastamento
                            	</a>
                                &nbsp;
                                <a  class="btn_link limpar">
                                  &nbsp;&nbsp;&nbsp;&nbsp;Limpar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            	</a>
                                </p>
                             </div>
                           </div>
                           <div id="acompanhamento" style="border:#6A97A7 1px solid; height:300px;">
                           		<div style="float:left; width:14.5%;" align="right">
                                <p><label for="ENCAMINHAMENTO">Encaminhamento: </label></p>
                                <p class="hospital"><label for="HOSPITAL">Hospital: </label></p>
                                <p><label for="MEDICO">Medico: </label></p>
                                <p><label for="DATA_PROX_ACOMPANHAMENTO">Proxímo Retorno: </label></p>
                                <p style="height:75px;"><label for="OBSERVACOES_ACOMPANHAMENTO">Observações: </label></p>
                                <p class="medicam"><label for="DEN_MEDICAMENTO">Medicamento: </label></p>
                             </div>
                             <div style="float:right; width:85.3%;" align="left">
                                <input class="normal" type="hidden" name="COD_ACOMPANHAMENTO" id="COD_ACOMPANHAMENTO" value="<?php if(isset($acom)) echo $acom[0]['COD_ACOMPANHAMENTO'];?>">
                                <p style="margin-top:10px;"><input type="checkbox"  <?php if(isset($acom)) if($acom[0]['ENCAMINHAMENTO'] == 'S') echo 'checked="checked"';?>  name="ENCAMINHAMENTO" id="ENCAMINHAMENTO"></p>
                                <p class="hospital"><input type="text" class="normal" name="HOSPITAL" id="HOSPITAL" size="80" value="<?php if(isset($acom)) echo utf8_encode($acom[0]['HOSPITAL']);?>"/></p>
                                
                                <p><input type="text" class="normal required" name="MEDICO" id="MEDICO" size="80" value="<?php if(isset($acom)) echo utf8_encode($acom[0]['MEDICO']);?>"/></p>
                                <p><input type="text" class="required" name="DATA_PROX_ACOMPANHAMENTO" id="DATA_PROX_ACOMPANHAMENTO" size="10" value="<?php if(isset($acom)) echo $acom[0]['DATA_PROX_ACOMPANHAMENTO'];?>"/></p>
                                <p style="height:42px;"><textarea class="normal" cols="81" rows="3" id="OBSERVACOES_ACOMPANHAMENTO" name="OBSERVACOES_ACOMPANHAMENTO"><?php if(isset($acom) && $acom[0]['OBSERVACOES_ACOMPANHAMENTO'] != NULL) echo utf8_encode(getClob($acom[0]['OBSERVACOES_ACOMPANHAMENTO']));?></textarea></p>
                               <p></p>
                               
                                   
                                 <div id="campos" class="campos" style="position:inherit; float:none;">
                                 <?php if(!isset($medicamentos) || $medicamentos == NULL) { ?>
                                  <p class="campoMedicamento">
<input type="text" class="normal" name="DEN_MEDICAMENTO[]" id="DEN_MEDICAMENTO" size="52" value="<?php if(isset($medic)) echo utf8_encode($medic[0]['DEN_MEDICAMENTO']);?>"/> &nbsp;<label for="VALOR_MEDICAMENTO">Custo: </label>&nbsp;
                                   <input type="text"  class="normal medicamento" name="VALOR_MEDICAMENTO[]" id="VALOR_MEDICAMENTO" size="10" value="<?php if(isset($medic)) echo $medic[0]['VALOR_MEDICAMENTO'];?>"/>&nbsp;
                                   <a href="#campos" class="removerCampo"><img  src="<?php echo base_url();?>images/del.png" title="Remover Campo" /></a>
                                 </p>
							<?php }else{ 
								foreach($medicamentos as $med){?>
                                <p class="campoMedicamento">
                                <input type="text" class="normal medicamento" name="DEN_MEDICAMENTO[]" id="DEN_MEDICAMENTO" size="52" value="<?php echo utf8_encode($med['DEN_MEDICAMENTO']);?>"/> &nbsp;<label for="VALOR_MEDICAMENTO">Custo: </label>&nbsp;
                                   <input type="text"  class="normal medicamento" name="VALOR_MEDICAMENTO[]" id="VALOR_MEDICAMENTO" size="10" value="<?php echo $med['VALOR_MEDICAMENTO'];?>"/>&nbsp;
                                   <a href="#campos" class="removerCampo"><img  src="<?php echo base_url();?>images/del.png" title="Remover Campo" /></a>
                                   </p>
                                 <?php }}?>
                                 <a href="#campos" class="adicionarCampo"><img  src="<?php echo base_url();?>images/add.png" title="Adicionar Campo" /></a> &nbsp;
                               
                               </div>
                        						          
						        
                                                                <!--<button class="normal" type="submit" id="Prosseguir"  name="Prosseguir" value="Prosseguir" />Prosseguir</button>
                                <button type="button" id="Limpar"  name="Limpar" value="Limpar" />Limpar</button>-->
                               
                                <p>
                                <a class="btn_link" id="cadastrar">
                                     &nbsp; Cadastrar&nbsp;
                            	</a>
                                
                                &nbsp;
                                <a  class="btn_link" id="limp">
                                  &nbsp;&nbsp;&nbsp;&nbsp;Limpar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            	</a>
                                 &nbsp;
                                <a id="voltar"  class="btn_link">
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Voltar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            	</a>
                                </p>
                             </div>
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
   
  
   
   <div id="datatables" style="width:98.5%; margin-left:0.75%;">
      <table cellpadding="0" cellspacing="0" border="0" class="display" id="record">
        <thead>
          <tr>
          	 <th>Matricula</th>
          	 <th>Nome</th>
          	 <th>Data Atendimento</th>
          	 <th>Tipo Assunto</th>
             <th>Assunto</th>
             <th>Data Ocorrência</th>
             <th>Status</th>
             <th>A&ccedil;&otilde;es</th>
          </tr>
        </thead>
        <tbody>
         <?php foreach($acidentes as $row):?>
          <tr>
          	<td align="center"><?php echo $row['NUM_MATRICULA']; ?></td>
          	<td align="center"><?php echo $row['NOM_COMPLETO']; ?></td>
          	<td align="center"><?php echo toDate($row['DATA_INI_ATEND_SOC']); ?></td>
          	<td align="center"><?php echo utf8_encode($row['DEN_TIPO_ATENDIMENTO']); ?></td>
            <td><?php echo utf8_encode(getClob($row['OCORRENCIA']));?></td>
            <td align="center"><?php echo toDate($row['DATA_OCORRENCIA']); ?></td>
            <td align="center"><?php echo $row['STATUS']; ?></td>
            <td align="left" ><a href="<?php echo base_url() . 'index.php/acidente/editar/' . $row['COD_ATEND_SOCIAL']; ?>"><img style="border:0px;" src="<?php echo base_url(); ?>images/tables/action2.gif"></a></td>
          </tr>
         <?php endforeach; ?>
        </tbody>
      </table>  
   </div> 
   
    <div id="datatables_acomp" style="width:98.5%; margin-left:0.75%;">
      <table cellpadding="0" cellspacing="0" border="0" class="display" id="record1">
        <thead>
          <tr>
          	 <th>Médico</th>
          	 <th>Data Acompanhamento</th>
          	 <th>Proxímo Retorno</th>
          	 <th>Observações</th>
             <th>A&ccedil;&otilde;es</th>
          </tr>
        </thead>
        <tbody>
         <?php 
         if(isset($acompanhamentos)):
         foreach($acompanhamentos as $lin):?>
          <tr>
          	<td align="center"><?php echo utf8_encode($lin['MEDICO']); ?></td>
          	<td align="center"><?php echo $lin['DATA_ACOMPANHAMENTO']; ?></td>
          	<td align="center"><?php echo toDate($lin['DATA_PROX_ACOMPANHAMENTO']); ?></td>
          	<td align="center"><?php echo utf8_encode(getClob($lin['OBSERVACOES_ACOMPANHAMENTO'])); ?></td>
            <td align="left" ><a href="<?php echo base_url() . 'index.php/acidente/editar_acomp/' . $lin['COD_ACOMPANHAMENTO']; ?>"><img style="border:0px;" src="<?php echo base_url(); ?>images/tables/action2.gif"></a></td>
          </tr>
         <?php endforeach; endif;?>
        </tbody>
      </table>  
   </div> 
</div>
