<?php
if($_SERVER['REMOTE_ADDR'] == '192.168.194.140'){
	//var_dump($historicoAtestado);
}





?>
<script charset="utf-8">
$(function(){
	$("#Limpar").click(function(){
		window.location.href = '<?php echo base_url();?>index.php/acompFaltas';
	});
	
	$("#record_length").html("Tabela");
	
	$("#atendForm").validate();
	
	if($("#COD_ACOMP_FALTAS").val() != ""){
		$("#Cadastrar").val("Alterar");
		$("#Cadastrar").html("Alterar");
	}
	
	if($.browser.msie == true){
		$("#form").css("height",3900+'px');
	}
	if($.browser.mozilla)
	{
		$("#form").css("height",5000+'px');
	}
	
	var msn = '<?php if (isset($msn)) echo $msn;?>';
		if(msn == 'msn1'){
			alert('Cadastro realizado com sucesso.');
			window.location.href = '<?php echo base_url();?>'+'/index.php/acompFaltas';
		}else if(msn == 'msn2'){
			alert('Alteração realizado com sucesso.');
			window.location.href = '<?php echo base_url();?>'+'/index.php/acompFaltas';
		}else if(msn == 'msn3'){
			alert('Exclusão realizada com sucesso!.');
			window.location.href = '<?php echo base_url();?>'+'/index.php/acompFaltas';
		}else if(msn == 'msn8'){
			alert('Já foi cadastrado uma entrevista para o funcionario e periodo selecionados.');
		}
	var funcio = '<?php if(isset($funcionario)){if($funcionario == array()){ echo "1";}}?>';
		if(funcio == '1'){
			alert('Matricula esta incorreta ou não existe.');
		}
	
	$('.readonly').attr('readonly','readonly');
	
	$("#antlink").css('display','none');
	$('#NUM_MATRICULA').keyup(function(){
		$(".readonly").val("");
		if($('#NUM_MATRICULA').val() != "" && $("#PERIODO").val() != ""){
			$("#antlink").css('display','inline');
			$("#link").css('display','none');
			$("#antlink").attr('href','<?php echo base_url();?>index.php/acompFaltas/funcionario/'+$('#NUM_MATRICULA').val()+'/'+$("#PERIODO").val().replace('/','-'));
		}else{
			$("#link").css('display','inline');
			$("#antlink").css('display','none');
		}
	});
	$('#NUM_MATRICULA').blur(function(){
		if($('#NUM_MATRICULA').val() != "" && $("#PERIODO").val() != ""){
			$("#antlink").css('display','inline');
			$("#link").css('display','none');
			$("#antlink").attr('href','<?php echo base_url();?>index.php/acompFaltas/funcionario/'+$('#NUM_MATRICULA').val()+'/'+$("#PERIODO").val().replace('/','-'));
		}else{
			$("#link").css('display','inline');
			$("#antlink").css('display','none');
		}
	});
	
	$("#PERIODO").click(function(){
		var periodo = $("#PERIODO").val().replace('/','-'); 
		$("#link").attr('href','<?php echo base_url();?>index.php/funcionario/index/tela/acompFaltas/periodo/'+periodo);
	});

	$("#antlink").click(function(){
		if($("#PERIODO").val() == ""){
			alert("Escolha um periodo.");
			window.location.href = '<?php echo base_url();?>'+'/index.php/acompFaltas';
		}
	});

	$("#link").click(function(){
		if($("#PERIODO").val() == ""){
			alert("Escolha um periodo.");
			window.location.href = '<?php echo base_url();?>'+'/index.php/acompFaltas';
		}
	});

	
	var status = '<?php  if(isset($atend)) echo $atend[0]['STATUS_ATENDIMENTO'];?>';
	if(status != '1' && status != ""){
		$(".normal").attr('disabled','disabled');
	}
	
	$("#vis_fal").click(function(){
		$("#dialog" ).dialog();
	});
	
	$("#vis_his_fal").click(function(){
		$("#dialog-historicoFaltas" ).dialog({width:900});
	});
	
	$("#vis_his_atestado").click(function(){
		$("#dialog-historicoAtestado" ).dialog({width:900});
	});
	
	
	$("#vis_his_med").click(function(){
		$("#dialog-historicoMedidas" ).dialog({width:900});
	});
	
	var motivo = '<?php if(isset($acomp)){ echo utf8_encode(getClob($acomp[0]['MOTIVO']));}?>';
	$("#MOTIVO").val(motivo);
	
	
	var nivel = '<?php echo $this->session->userdata('idLevel');?>';
	if(nivel == '5'){
		$("#SUPERVISOR").addClass("readonly");
		$("#LIDER").addClass("readonly");
		$("#MOTIVO").addClass("readonly");
		//$("#MOTIVO").attr("disabled","disabled");
		$("#INSAT_CHEFIA").addClass("readonly");
		$("#JUSTIFICATIVA").addClass("readonly");
		$("#SUGESTOES").addClass("readonly");
		$('.readonly').attr('readonly','readonly');
		$(".deletar").css('display','none');
	}
	
	var insat = '<?php if(isset($acomp) && $acomp[0]['PORQ_INSATISFACAO'] != NULL) {echo '1';} ?>';
	if(insat == '1'){
		$(".insatis").css('display','block');
		$("#form").css("height",900+'px');
	}else{
		$(".insatis").css('display','none');
		$("#form").css("height",900+'px');
	}

	$("#INSAT_CHEFIA_0").click(function(){
		$(".insatis").css('display','block');
			$("#form").css("height",900+'px');
	});
	$("#INSAT_CHEFIA_1").click(function(){
		$(".insatis").css('display','none');
			$("#form").css("height",900+'px');
	});
	
});

function deletar(COD_ACOMP_FALTAS,PERIODO){
	confirma = confirm('Deseja realmente excluir este registro!');
	if(confirma){
		window.location.href="<?php echo base_url().'index.php/acompFaltas/deletar/';?>"+COD_ACOMP_FALTAS+"/"+PERIODO;                                       
	}	
}	
		
</script>
<script>

$(document).ready(function() {
	$(function() {
		$("#LIDER").autocomplete({
		source: function(request, response) {
			$.ajax({ url: "<?php echo site_url('acompFaltas/lider'); ?>",
			data: { lid: $("#LIDER").val()},
			dataType: "json",
			type: "POST",
			success: function(data){
				response(data);
			}
		});
	},
	minLength: 4
	});
		
		$("#SUPERVISOR").autocomplete({
		source: function(request, response) {
			$.ajax({ url: "<?php echo site_url('acompFaltas/supervisor'); ?>",
			data: { su: $("#SUPERVISOR").val()},
			dataType: "json",
			type: "POST",
			success: function(data){
				response(data);
			}
		});
		},
		minLength: 4
		});
		
		
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
	.questoes
	{
		width:40%;
		padding-left:5%;
		padding-right:5%;
		float:left;
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
                    <?php  if(isset($faltas)) { $f = $faltas->num_rows(); if($f > 0) {$fal = $faltas->result_array();}}?>
                      <form action="<?php echo base_url() . 'index.php/acompFaltas/manter'; ?>" method="post" id="atendForm" accept-charset="UTF-8">
                      <?php $exist = false; if(isset($funcionario) && $funcionario != null){ $exist= true;}?>
                      <div style="float:none; position:relative; height:900px;" id="form">
                      <div id="colum_left" style="float:left; width:48%;" align="right">
                      	<div style="float:left; width:30%;" align="right">
                        <p><label for="PERIODO">Periodo: </label></p>
							<p><label for="NUM_MATRICULA">Matricula: </label></p>
							<p><label for="NOME">Nome: </label></p>
							<p><label for="SETOR">Setor: </label></p>
							<p><label for="CARGO_INI">Cargo Inicial: </label></p>
                            <p><label for="CARGO_ATU">Cargo Atual: </label></p>
							<p><label for="DATA_ADMISSAO">Data Admiss&atilde;o: </label></p>
                            <p><label for="TEMPO_FUNCAO">Tempo de fun&ccedil;&atilde;o: </label></p>
                            <p><label for="CONTATO">Contato: </label></p>
                            <p style="margin-top:70px;"><label for="SUPERVISOR">Supervisor: </label></p>
						   
                            <p><label for="LIDER">Lider: </label></p>
                            <p><label for="QTD_FALTAS">Quantidade de Faltas: </label></p>
                            <p style="height:20px;"><label for="DESLIGAMENTO">Desligamento Programado:</label></p>
						    <p><label for="MOTIVO">Motivo: </label></p>
                            <p style="height: 70px;"><label for="COMENTARIOS">Coment&aacute;rios</label></p>
                            <!--<p><label for="INSAT_CHEFIA">Insatisfação com a chefia?</label></p>-->
                           <!-- <p style="height: 63px;" class="insatis"><label for="PORQ_INSATISFACAO">Porque da Insatisfação: </label></p>-->
                            <p style="height: 63px;"><label for="JUSTIFICATIVA">Coment&aacute;rio do RH: </label></p>
                            <!--<p style="height: 63px;"><label for="SUGESTOES">Sugestões: </label></p>-->
                        <p><label for="FEEDBACK_SUPERVISOR">Feedback do Gestor: </label></p>
							
                        </div>
                        <div style="float:right; width:69.5%;" align="left">
							<p>
                            	<?php $datacalc =  explode('/',date('d/m/y'));?>
                                <select id="PERIODO" name="PERIODO">
                               		<option value="" selected="selected">.:: Selecione ::.</option>
                               		<?php for($i=13;$i>=0;$i--){
										$periodo = mesAno(calculaData("01/".$datacalc[1]."/".$datacalc[2],'-',0,$i,0));
									?>
                                 	<option <?php if(isset($acomp)){if($acomp[0]['PERIODO'] == $periodo){ echo "selected='selected'";}} else{ if(isset($fal)){ if($fal[0]['PERIODO'] == $periodo){ echo "selected='selected'"; }}} ?> value="<?php echo $periodo; ?>"><?php echo $periodo; ?></option>
                                    <?php }?>
                               </select>
                            </p>
                            <p><input class="required" type="text" name="NUM_MATRICULA" id="NUM_MATRICULA" size="10" value="<?php if($exist) echo $funcionario[0]['NUM_MATRICULA']; ?>"/>
							<a  id="link" href="#" rel="superbox[iframe][750x550]">
                                      PESQUISAR       	
                            </a>
                            <a  id="antlink"  href="#">
                                      PESQUISAR
                            </a>
                            </p>
							<p><input type="text" class="readonly required" name="NOME" id="NOME" size="50" value="<?php if($exist) echo $funcionario[0]['NOM_COMPLETO']; ?>"/></p>
							<p><input type="text" class="readonly" name="SETOR" id="SETOR" size="50" value="<?php if($exist) echo $funcionario[0]['DEN_UNI_FUNCIO']; ?>"/></p>
							<p><input type="text" class="readonly" name="CARGO_INI" id="CARGO_INI" size="50" value="<?php if($exist) echo $funcionario[0]['CARGO_INICIAL']; ?>"/></p>
							<p><input type="text" class="readonly" name="CARGO_ATU" id="CARGO_ATU" size="50" value="<?php if($exist) echo $funcionario[0]['CARGO_ATUAL']; ?>"/></p>
                            <p><input type="text" class="readonly" name="DATA_ADMISSAO" id="DATA_ADMISSAO" size="10" value="<?php if($exist) echo $funcionario[0]['DAT_ADMIS']; ?>"/></p>
                            <p><input type="text" class="readonly" name="DATA_ADMISSAO" id="CARGO_ATUAL" size="25" value="<?php if($exist) echo $funcionario[0]['TEMPO_FUNCAO']; ?>"/></p>
                            <p><input type="text" class="readonly" name="CONTATO" id="CONTATO" size="20"  value="<?php if($exist) echo $funcionario[0]['NUM_TELEF_RES']; ?>"/></p>
						<p><span id="vis_his_fal" style="text-decoration:underline !important; cursor:pointer;">Visualizar Historico de Faltas</span><br /><span id="vis_his_med" style="text-decoration:underline !important; cursor:pointer;">Visualizar Historico de Medidas Disiplinares</span><br /><span id="vis_his_atestado" style="text-decoration:underline !important; cursor:pointer;">Visualizar Histórico de Atestado</span>
                        	
                        </div>
                              
                      </div>
                      
                       <div id="colum_right" style="float:right; width:51%;" align="left">
						<div style="float:left; width:28%;" align="right">
							
							<p><label for="STATUS">Status: </label></p>
							<p><label for="GERENCIA">Gerência: </label></p>
							<p><label for="CIDADE">Cidade: </label></p>
							<p><label for="IDADE">Idade: </label></p>
                            <p><label for="ESCOLARIDADE">Escolaridade: </label></p>
							<p><label for="TEMPO_EMPRESA">Tempo de Empresa: </label></p>
                            <p><label for="PNE">PCD: </label></p>
                            <p><label for="EST_CIVIL">Estado Civil: </label></p>
                            
                        </div>
                        <div style="float:right; width:71.5%;" align="left">
							<p><input type="text" class="readonly" name="STATUS" id="STATUS" size="20"  value="<?php if($exist) echo $funcionario[0]['STATUS']; ?>"/></p>
							<p><input type="text" class="readonly" name="GERENCIA" id="GERENCIA" size="50" value="<?php if($exist) echo $funcionario[0]['DEN_ESTR_LINPROD']; ?>"/></p>
							<p><input type="text" class="readonly" name="CIDADE" id="CIDADE" size="50" value="<?php if($exist) echo $funcionario[0]['DEN_CIDADE']; ?>"/></p>
							<p><input type="text" class="readonly" name="IDADE" id="IDADE" size="20" value="<?php if($exist) echo $funcionario[0]['IDADE']; ?>"/></p>
                            <p><input type="text" class="readonly" name="IDADE" id="IDADE" size="20" value="<?php if($exist) echo $funcionario[0]['DES_GRAU_INSTR']; ?>"/></p>
                            <p><input type="text" class="readonly" name="TEMPO_EMPRESA" id="TEMPO_EMPRESA" size="20" value="<?php if($exist) echo $funcionario[0]['TEMPO_EMPRESA']; ?>"/></p>
							<p><input type="text" class="readonly" name="PNE" id="PNE" size="20" value="<?php if($exist) echo utf8_encode($funcionario[0]['PNE']); ?>"/></p>
                       		<p><input type="text" class="readonly" name="EST_CIVIL" id="EST_CIVIL" size="20" value="<?php if($exist) echo $funcionario[0]['IES_EST_CIVIL']; ?>"/></p>
                            <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        
                        
                            <input class="normal" type="hidden" name="COD_ACOMP_FALTAS" id="COD_ACOMP_FALTAS" value="<?php if(isset($acomp)) echo $acomp[0]['COD_ACOMP_FALTAS'];?>">
                        <p><input type="text" style="margin-left:-137%;" class="normal required" name="SUPERVISOR" id="SUPERVISOR" size="80" value="<?php if(isset($acomp)) echo utf8_encode($acomp[0]['SUPERVISOR'])." - ".$acomp[0]['MATRICULA_SUPERVISOR'];?>"/></p>
                        <p><input type="text" style="margin-left:-137%;" class="normal required" name="LIDER" id="LIDER" size="80" value="<?php if(isset($acomp)) echo utf8_encode($acomp[0]['LIDER'])." - ".$acomp[0]['MATRICULA_LIDER'];?>"/></p>
                         <p><input type="text" style="margin-left:-137%;" class="readonly" name="QTD_FALTAS" id="QTD_FALTAS" size="10" value="<?php if(isset($acomp)){ echo utf8_encode($acomp[0]['QTD_FALTAS']);}else{if($exist) echo $funcionario[0]['QTD'];}?>"/>&nbsp; <span id="vis_fal" style="text-decoration:underline !important; cursor:pointer;">Visualizar Datas das faltas</span></p>
                          
                   	    <p style="margin-left:-132%; ">
                      	   <label>
                      	     <input type="radio"  name="DESLIGAMENTO" <?php if(isset($acomp)){ if($acomp[0]['DESLIGAMENTO'] == "S"){ echo 'checked="checked"';}}?>  value="S" id="DESLIGAMENTO_0" />
                      	     Sim</label>
                      	   &nbsp;&nbsp;
                      	   <label>
                      	     <input type="radio"  checked="checked" name="DESLIGAMENTO" <?php if(isset($acomp)){ if($acomp[0]['DESLIGAMENTO'] == "N"){ echo 'checked="checked"';}}?> value="N" id="DESLIGAMENTO_1" />
                      	     Não</label>
                   	   </p>
						<p style="margin-left:-132%;">
                       	  <select  id="MOTIVO" name="MOTIVO" class="required" >
                            	<option value="ALCOOLISMO">Alcoolismo</option>
                                <option value="ASSEDIO_MORAL">Ass&eacute;dio moral</option>
                                <option value="CLIMA_ORGANIZACIONAL">Clima Organizacional</option>
                            	<option value="COMUNICACAO_DEFICIENTE">Comunica&ccedil;&atilde;o Deficiente</option>
                                <option value="DROGAS">Drogas</option>
                                <option value="FEEDBACK">Feedback</option>
                                <option value="IMATURIDADE_PROFISSIONAL">Imaturidade Profissional</option>
                                <option value="INFRAESTRUTURA_DEFICIENTE">Infraestrutura Deficiente</option>			
                                <option value="LIDERANCA_DESPREPARADA">Lideran&ccedil;a Despreparada</option>
                                <option value="METAS_INTANGIVEIS">Metas intang&iacute;veis</option>
                                <option value="OUTROS">Outros</option>
                                <option value="PREFERENCIALISMO">Preferencialismo</option>
                                <option value="PROBLEMAS_FAMILIARES">Problemas Familiares</option>
                                <option value="QUALIDADE_DE_VIDA">Qualidade de Vida</option>
                                
                                
                            </select>
                        </p>
                        <p style="height: 70px; margin-left:-132%;"> <textarea  class="normal required" cols="98" rows="4" id="COMENTARIOS" name="COMENTARIOS"><?php if(isset($acomp) && $acomp[0]['COMENTARIOS'] != NULL) echo utf8_encode(getClob($acomp[0]['COMENTARIOS']));?></textarea></p>
                        <!--<p style="margin-left:-132%;">
                       	  <label>
                        	  <input type="radio" name="INSAT_CHEFIA" <?php if(isset($acomp)){ if($acomp[0]['INSAT_CHEFIA'] == "S"){ echo 'checked="checked"';}}?> value="S" id="INSAT_CHEFIA_0" />
                        	  Sim</label>
							&nbsp;&nbsp;
                        	<label>
                        	  <input type="radio" checked="checked" name="INSAT_CHEFIA" <?php if(isset($acomp)){ if($acomp[0]['INSAT_CHEFIA'] == "N"){ echo 'checked="checked"';}}?> value="N" id="INSAT_CHEFIA_1" />
                        	  Não</label>

                        </p>-->
                       
                        <p class="insatis" style="height: 63px; margin-left:-132%;"><textarea class="normal required" cols="98" rows="4" id="PORQ_INSATISFACAO" name="PORQ_INSATISFACAO"><?php if(isset($acomp) && $acomp[0]['PORQ_INSATISFACAO'] != NULL) echo utf8_encode(getClob($acomp[0]['PORQ_INSATISFACAO']));?></textarea></p>
					   <p style="height: 63px; margin-left:-132%;"><textarea class="normal required" cols="98" rows="4" id="JUSTIFICATIVA" name="JUSTIFICATIVA"><?php if(isset($acomp) && $acomp[0]['JUSTIFICATIVA'] != NULL) echo utf8_encode(getClob($acomp[0]['JUSTIFICATIVA']));?></textarea></p>
                        <!--<p style="height: 63px; margin-left:-132%;"><textarea class="normal" cols="98" rows="4" id="SUGESTOES" name="SUGESTOES"><?php if(isset($acomp) && $acomp[0]['SUGESTOES'] != NULL) echo utf8_encode(getClob($acomp[0]['SUGESTOES']));?></textarea></p>-->
                        <p style="height: 25px; margin-left:-132%;"><textarea class="normal" cols="98" rows="4" id="FEEDBACK_SUPERVISOR" name="FEEDBACK_SUPERVISOR"><?php if(isset($acomp) && $acomp[0]['FEEDBACK_SUPERVISOR'] != NULL) echo utf8_encode(getClob($acomp[0]['FEEDBACK_SUPERVISOR']));?></textarea></p>
                        </div>       
                       </div>
					   </div>
                      <hr/>
                      
                     <div style="float:left; width:100%; text-align:left;">                
                       <p style="font-weight:bold; height:20px; margin-top:70px;">Pesquisa Socioeconômica</p>
                       <p>1. Qual é a renda total, mensal (aproximada) de sua família? Some o seu salário com o de seu cônjuge e com o de se seus filhos (que ainda dependem de você)</p>
                       <div style="width:100%; float:left;">
                       <select name="RENDA">
                       <?php if(isset($acomp) && $acomp[0]['RENDA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['RENDA'])?>"><?php echo($acomp[0]['RENDA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option value="1_SALARIO"/> 1 Sal&aacute;rio m&iacute;nimo</option>
                       <option value="ENTRE_1_E_2"/> Entre 1 e 2 sal&aacute;rios m&iacute;nimos</option>
                       <option value="ENTRE_2_E_3"/> Entre 2 e 3 sal&aacute;rios m&iacute;nimos</option>
                       <option value="ENTRE_3_E_4"/>Entre 3 e 4 sal&aacute;rios m&iacute;nimos</option>
                       <option value="ENTRE_4_E_5"/>
                       Entre 4 e 5 sal&aacute;rios m&iacute;nimos</option>
                       <option value="ENTRE_5_E_10"/>
                       Entre 5 e 10 sal&aacute;rios m&iacute;nimos</option>
                       <option value="ENTRE_10_E_15"/>
                       Entre 10 e 15 sal&aacute;rios m&iacute;nimos</option>
                       <option value="ENTRE_15_E_20"/>
                       Entre 15 e 20 sal&aacute;rios m&iacute;nimos</option>
                       <option value="ACIMA_DE_20"/>
                       Acima de 20 sal&aacute;rios m&iacute;nimos</option>
                       </select>
                       <hr />
                       </div>
                      
                       
                       
                       <div style="width:100%; float:left;">
                       
                       <p>2. Fora da empresa voc&ecirc; tem outra atividade remunerada?</p>
                       <div style="width:100%; float:left;">
                       <select name="OUTRA_RENDA">
                       <?php if(isset($acomp) && $acomp[0]['OUTRA_RENDA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['OUTRA_RENDA'])?>"><?php echo($acomp[0]['OUTRA_RENDA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option value="SIM">Sim</option>
                       <option value="NAO">N&atilde;o</option>
                       </select>&nbsp;&nbsp;&nbsp;Qual? <input type="text" id="NOME_OUTRA_RENDA" name="NOME_OUTRA_RENDA" maxlength="200" size="60" value="<?php if(isset($acomp) && $acomp[0]['NOME_OUTRA_RENDA'])
					   echo $acomp[0]['NOME_OUTRA_RENDA'];?>"/>
                       <hr />
                       </div></div>
                       
                       <div style="width:100%; float:left;">
                       <p>3. Quanto voc&ecirc; ganha por m&ecirc;s com a outra atividade?</p>
                       <p><input type="text" name="VALOR_OUTRA_REMUNERADA" width="13" maxlength="13" value="<?php if(isset($acomp)&&$acomp[0]['VALOR_OUTRA_REMUNERADA']) echo $acomp[0]['VALOR_OUTRA_REMUNERADA']; ?>" />R$</p>
                       <hr />
                       </div>
                       <!--<div style="width:100%; float:left;">
                       <p>4. Al&eacute;m de seu trabalho na empresa e da sua outra atividade remunerada, voc&ecirc; possui outra fonte de renda?</p>&nbsp;&nbsp;&nbsp;Qual?
                       <select name="POSSUI_OUTRA_RENDA">
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <option value="SIM">Sim
                       <option value="NAO">N&atilde;o
                       </select>
                        <input type="text" id="NOME_OUTRA_RENDA" name="NOME_OUTRA_RENDA" maxlength="200" size="60" />
                       
                       <hr />
                       </div>
                       <div style="width:100%; float:left;">
                       <p>5. Qual a sua renda total mensal (aproximada)? *some seu sal&aacute;rio com os outros ganos que voc&ecirc; tem fora da empresa.</p>
                       <input type="text" name="RENDA_TOTAL" width="13" maxlength="13" />R$<hr /></div>-->
                       <div style="width:100%; float:left;">
                       <p>4. Voc&ecirc; ajuda mensalmente no sustento de algum parente ou agregado?</p>
                       <div style="width:100%; float:left;">
                       <select name="SUSTENTO_AGREGADO">
                       <?php if(isset($acomp) && $acomp[0]['SUSTENTO_AGREGADO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['SUSTENTO_AGREGADO'])?>"><?php echo($acomp[0]['SUSTENTO_AGREGADO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option value="SIM"> Sim</option>
                       <option value="NAO"> N&atilde;o</option>
                       </select>
                       <hr />
                       </div>
                       </div>
                       <div style="width:100%; float:left;">
                       <p>5. Qual &eacute; o meio de transporte que voc&ecirc; utiliza para ir ao trabalho?</p>
                       <div style="width:100%;">
                       <select name="TRANSPORTE_EMPRESA">
                       <?php if(isset($acomp) && $acomp[0]['TRANSPORTE_EMPRESA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['TRANSPORTE_EMPRESA'])?>"><?php echo($acomp[0]['TRANSPORTE_EMPRESA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option  value="PE"> A P&eacute;</option>
                       <option  value="BICICLETA"> Bicicleta</option>
                       <option  value="CARONA"> Carona</option>
                       <option  value="CARRO_PROPRIO"> Carro Pr&oacute;prio</option>
                       <option  value="METRO"> Metr&ocirc;</option>
                       <option  value="MOTO"> Moto</option>
                       <option  value="ONIBUS"> &Ocirc;nibus</option>
                       <option  value="ROTA_ESMALTEC"> Rota da Esmaltec</option>
                       <option  value="TREM"> Trem</option>
                       </select>
                       <p>Quanto tempo voc&ecirc; gasta para chegar ao Trabalho? <input type="text" name="TEMPO_TRABALHO" size="5" maxlength="5" value="<?php if (isset($acomp[0]['TEMPO_TRABALHO']))
					   {
					   echo utf8_encode(($acomp[0]['TEMPO_TRABALHO']));}?>"> Horas
                       <hr />
                       </div></div>
                       <div style="width:100%; float:left;">
                       <p>6. E para voltar para casa?</p>
                       <div style="width:100%; float:left;">
                       <select name="TRANSPORTE_CASA">
                       <?php if(isset($acomp) && $acomp[0]['TRANSPORTE_CASA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['TRANSPORTE_CASA'])?>"><?php echo($acomp[0]['TRANSPORTE_CASA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option  value="PE"> A P&eacute;</option>
                       <option  value="BICICLETA"> Bicicleta</option>
                       <option  value="CARONA"> Carona</option>
                       <option  value="CARRO_PROPRIO"> Carro Pr&oacute;prio</option>
                       <option  value="METRO"> Metr&ocirc;</option>
                       <option  value="MOTO"> Moto</option>
                       <option  value="ONIBUS"> &Ocirc;nibus</option>
                       <option  value="ROTA_ESMALTEC"> Rota da Esmaltec</option>
                       <option  value="TREM"> Trem</option>
                       </select>
                       <p>Quanto tempo voc&ecirc; gasta para chegar em casa? <input type="text" name="TEMPO_CASA" size="5" maxlength="5" value="<?php if (isset($acomp[0]['TEMPO_CASA']))
					   {
					   echo utf8_encode(($acomp[0]['TEMPO_CASA']));}?>"> Horas</p>
                       <hr />
                       </div></div>
                       <!--<div style="width:100%; float:left;">
                      <p>9. Quanto tempo voc&ecirc; gasta?</p>
                       Para chegar ao Trabalho? <input type="text" name="TEMPO_TRABALHO" size="5" maxlength="5"> Horas &nbsp;
                       e para chegar em casa? <input type="text" name="TEMPO_CASA" size="5" maxlength="5"> Horas
                       <hr />
                       </div>-->
                       <div style="width:100%; float:left;">
                       <p style="font-weight:bold;">Pesquisa sobre ambiente de trabalho</p>
                       
                       <p>1. Os funcion&aacute;rios s&atilde;o tratados com respeito, independentemente dos seus cargos?</p>
                       <div style="width:100%;">
                       <select name="TRATAMENTO_RESPEITO">
                       <?php if(isset($acomp) && $acomp[0]['TRATAMENTO_RESPEITO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['TRATAMENTO_RESPEITO'])?>"><?php echo($acomp[0]['TRATAMENTO_RESPEITO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option>
                       </select>
                       </div> <hr /></div>
                       <div style="width:100%; float:left;">
                       <p>2. Voc&ecirc; considera a Esmaltec um bom lugar para trabalhar?</p>
                        <div style="width:100%;">
                         <select name="LUGAR_TRABALHO">
                         <?php if(isset($acomp) && $acomp[0]['LUGAR_TRABALHO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['LUGAR_TRABALHO'])?>"><?php echo($acomp[0]['LUGAR_TRABALHO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option>
                       </select>
                       
                       </div><hr /></div>
                       <div style="width:100%; float:left;">
                       <p>3. No dia a dia voc&ecirc; percebe a qualidade na fabrica&ccedil;&atilde;o dos produtos</p>
                        <div style="width:100%;"><select name="PERCEBE_QUALIDADE"> 
                        <?php if(isset($acomp) && $acomp[0]['PERCEBE_QUALIDADE'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['PERCEBE_QUALIDADE'])?>"><?php echo($acomp[0]['PERCEBE_QUALIDADE'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>                    
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option>
                       </select>
                      
                       </div><hr /></div>
                       <div style="width:100%; float:left;">
                        <p>4. O gestor imediato &eacute; aberto a receber as opini&otilde;es dos funcion&aacute;rios?</p>
                        <div style="width:100%;"><select name="OPINIOES">
                        <?php if(isset($acomp) && $acomp[0]['OPINIOES'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['OPINIOES'])?>"><?php echo($acomp[0]['OPINIOES'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option>
                       </select>
                       <p>Comente:</p>
                       <textarea id="COMENTARIO_OPINIAO_FUNCIONARIO" name="COMENTARIO_OPINIAO_FUNCIONARIO" rows="4" cols="98" placeholder="Escreva"><?php if(isset($acomp) && $acomp[0]['COMENTARIO_OPINIAO_FUNCIONARIO'] != NULL) echo utf8_encode(getClob($acomp[0]['COMENTARIO_OPINIAO_FUNCIONARIO']));?></textarea>
                       </div><hr /></div>
                       <div style="width:100%; float:left;">
                        <p>5. A quantidade &eacute; mais importante do que a qualidade em seu trabalho?</p>
                        <div style="width:100%;"><select name="QUALIDADE">
                       <?php if(isset($acomp) && $acomp[0]['QUALIDADE'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['QUALIDADE'])?>"><?php echo($acomp[0]['QUALIDADE'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>                     
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option></select>
                       </div><hr /></div>
                        <textarea id="COMENTARIO_QUALIDADE" name="COMENTARIO_QUALIDADE" rows="4" cols="98" placeholder="Escreva"><?php if(isset($acomp) && $acomp[0]['COMENTARIO_QUALIDADE'] != NULL) echo utf8_encode(getClob($acomp[0]
						['COMENTARIO_QUALIDADE']));?></textarea>
                       <div style="width:100%; float:left;">
                       <p>6. As orienta&ccedil;&otilde;es  que voc&ecirc; recebe sobre o seu trabalho s&atilde;o claras e objetivas?</p>
                       <div style="width:100%;"><select name="ORIENTACAO">
                       <?php if(isset($acomp) && $acomp[0]['ORIENTACAO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['ORIENTACAO'])?>"><?php echo($acomp[0]['ORIENTACAO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>                    
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option></select>
                       </div><hr /></div>
                       <div style="width:100%; float:left;">
                       <p>7. As pessoas comprometidas s&acirc;o as que t&ecirc;m as melhores oportunidades na empresa?</p>
                       <div style="width:100%;"><select name="OPORTUNIDADE">
                       <?php if(isset($acomp) && $acomp[0]['OPORTUNIDADE'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['OPORTUNIDADE'])?>"><?php echo($acomp[0]['OPORTUNIDADE'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>                      
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option></select>
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">
                                              
                        <p>8. Voc&ecirc; se sente realizado com o trabalho que faz?</p>
                       <div style="width:100%;"><select name="REALIZACAO">
                       <?php if(isset($acomp) && $acomp[0]['REALIZACAO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['REALIZACAO'])?>"><?php echo($acomp[0]['REALIZACAO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>                    
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option></select>
                       <p>Comente:</p>
                        <textarea id="COMENTARIO_REALIZACAO" name="COMENTARIO_REALIZACAO" rows="4" cols="98" placeholder="Escreva"> <?php if(isset($acomp) && $acomp[0]['COMENTARIO_REALIZACAO'] != NULL) echo utf8_encode(getClob($acomp[0]['COMENTARIO_REALIZACAO']));?></textarea>
                       </div><hr /></div>
                       
                      <div style="width:100%; float:left;">                       
                       <p>9. Voc&ecirc; considera que &eacute; sua responsabilidade contribuir para o sucesso do seu setor?</p>
                       <div style="width:100%;"><select name="SUCESSO">
                       <?php if(isset($acomp) && $acomp[0]['SUCESSO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['SUCESSO'])?>"><?php echo($acomp[0]['SUCESSO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>                    
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option></select>
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">                       
                       <p>10. Voc&ecirc; gostaria de trabalhar em outro setor da empresa?</p>
                       <div style="width:100%;"><select name="OUTRO_SETOR">
                       <?php if(isset($acomp) && $acomp[0]['OUTRO_SETOR'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['OUTRO_SETOR'])?>"><?php echo($acomp[0]['OUTRO_SETOR'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option  value="SIM"> Sim</option>
                       <option  value="NAO"> N&atilde;o</option></select>
                       <p>Comente:</p>
                        <textarea id="COMENTARIO_OUTRO_SETOR" name="COMENTARIO_OUTRO_SETOR" rows="4" cols="98" placeholder="Escreva"><?php if(isset($acomp) && $acomp[0]['COMENTARIO_OUTRO_SETOR'] != NULL) echo utf8_encode(getClob($acomp[0]['COMENTARIO_OUTRO_SETOR']));?></textarea>
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">                       
                       <p>12. Como voc&ecirc; compara o seu sal&aacute;rio ao de outras pessoas que executam tarefas semelhantes &agrave;s suas em outras empresas?</p>
                       <textarea id="SALARIO" name="SALARIO" rows="4" cols="98" placeholder="Escreva">
                       <?php if(isset($acomp) && $acomp[0]['SALARIO'] != NULL) echo utf8_encode(($acomp[0]['SALARIO']));?></textarea>
                       <hr />
                       </div>
                       
                       
                       
                       <div style="width:100%; float:left;">
                      <p>13. Os beneficios oferecidos pela Esmaltec atendem as suas necessidades?</p>
                        <div style="width:100%;"><select name="BENEFICIOS">
                        <?php if(isset($acomp) && $acomp[0]['BENEFICIOS'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['BENEFICIOS'])?>"><?php echo($acomp[0]['BENEFICIOS'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>                     
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option></select>
                       <p>Comente:</p>
                       <textarea id="BENEFICIO_SALARIO" name="BENEFICIO_SALARIO" rows="4" cols="98" placeholder="Escreva"><?php if(isset($acomp) && $acomp[0]['BENEFICIO_SALARIO'] != NULL) echo utf8_encode(getClob($acomp[0]['BENEFICIO_SALARIO']));?></textarea>
                       </div><hr /></div>
                       <div style="width:100%; float:left;">
                       <p>14. Voc&ecirc; tem id&eacute;ia de quanto os seus beneficios representam em rela&ccedil;&atilde;o ao seu sal&aacute;rio?</p>
                        <div style="width:100%;"><select name="IDEIA_BENEFICIO">
                         <?php if(isset($acomp) && $acomp[0]['IDEIA_BENEFICIO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['IDEIA_BENEFICIO'])?>"><?php echo($acomp[0]['IDEIA_BENEFICIO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option  value="SIM"> Sim</option>
                       <option  value="NAO"> N&atilde;o</option></select>
                       <p>Comente:</p>
                       <textarea id="COMENTARIO_SALARIO" name="COMENTARIO_SALARIO" rows="4" cols="98" placeholder="Escreva"><?php if(isset($acomp) && $acomp[0]['SALARIO'] != NULL) echo utf8_encode(($acomp[0]['SALARIO']));?></textarea>
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">           
                       <p>15. Voc&ecirc; indicaria um amigo para trabalhar na Esmaltec?</p>
                        <div style="width:100%;"><select name="INDICACAO">
                        <?php if(isset($acomp) && $acomp[0]['INDICACAO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['INDICACAO'])?>"><?php echo($acomp[0]['INDICACAO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option value="SIM"> Sim</option>
                       <option  value="NAO"> N&atilde;o</option></select>
                       <p>Comente:</p>
                       <textarea id="COMENTARIO_AMIGO" name="COMENTARIO_AMIGO" rows="4" cols="98" placeholder="Escreva">
                       <?php if(isset($acomp) && $acomp[0]['COMENTARIO_AMIGO'] != NULL) echo utf8_encode(getClob($acomp[0]['COMENTARIO_AMIGO']));?></textarea>
                       
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">                       
                       <p>16. Os gestores d&atilde;o bons exemplos aos seus funcion&aacute;rios?</p>
                       <div style="width:100%;"><select name="EXEMPLO">
                       <?php if(isset($acomp) && $acomp[0]['EXEMPLO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['EXEMPLO'])?>"><?php echo($acomp[0]['EXEMPLO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>                      
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option></select>
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">                       
                       
                       
                       <div style="width:100%; float:left;">                       
                       <p>17. Voc&ecirc; gosta do que faz?</p>
                       <div style="width:100%;"><select name="GOSTA">
                       <?php if(isset($acomp) && $acomp[0]['GOSTA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['GOSTA'])?>"><?php echo($acomp[0]['GOSTA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option  name="GOSTA" value="SIM"> Sim</option>
                       <option  name="GOSTA" value="NAO"> N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option></select>
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">              
                       <p>18. Voc&ecirc; gostaria que sua fam&iacute;lia conhecesse melhor a empresa?</p>
                       <div style="width:100%;"><select name="FAMILIA_EMPRESA">
                       <?php if(isset($acomp) && $acomp[0]['FAMILIA_EMPRESA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['FAMILIA_EMPRESA'])?>"><?php echo($acomp[0]['FAMILIA_EMPRESA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option value="SIM"> Sim</option>
                       <option value="NAO"> N&atilde;o</option></select>
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">
                       <p>19. Os equipamentos de seguran&ccedil;a s&atilde;o adequados para proteger os funcion&aacute;rios no trabalho?</p>
                       <div style="width:100%;"><select name="EQUIPAMENTOS">
                       <?php if(isset($acomp) && $acomp[0]['EQUIPAMENTOS'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['EQUIPAMENTOS'])?>"><?php echo($acomp[0]['EQUIPAMENTOS'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>                  
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option></select>
                       </div><hr /></div>
                       
                      <div style="width:100%; float:left;"> 
                      <p>20. No seu setor de trabalho h&aacute; algum funcion&aacute;rio &quot;protegido&quot; pelo seu chefe</p>
                       <div style="width:100%;"><select name="PROTEGIDO">
                       <?php if(isset($acomp) && $acomp[0]['PROTEGIDO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['PROTEGIDO'])?>"><?php echo($acomp[0]['PROTEGIDO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>                     
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option></select>
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">
                       <p>21. Seu chefe informa sobre os fatos importantes que est&atilde;o acontecendo na empresa?</p>
                       <div style="width:100%;"><select name="CHEFE_INFORMA">
                       <?php if(isset($acomp) && $acomp[0]['CHEFE_INFORMA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['CHEFE_INFORMA'])?>"><?php echo($acomp[0]['CHEFE_INFORMA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>                     
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option></select>
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">                       
                       <p>22. Como voc&ecirc; se imagina daqui a dois anos?</p>
                        <div style="width:100%;"><select name="FUTURO">
                        <?php if(isset($acomp) && $acomp[0]['FUTURO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['FUTURO'])?>"><?php echo($acomp[0]['FUTURO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option value="MESMA_EMPRESA_MESMO_CARGO"> Trabalhando na mesma empresa, no mesmo cargo</option>
                       <option value="MESMA_EMPRESA_CARGO_MELHOR"> Trabalhando na mesma empresa,  num cargo melhor</option>
                       <option  value="OUTRA_EMPRESA_MESMO_CARGO"> Trabalhando em outra empresa, no mesmo cargo</option>
                       <option  value="OUTRA_EMPRESA_CARGO_MELHOR"> Trabalhando em outra empresa, num cargo melhor</option>
                       <option  name="FUTURO" value="CONTA_PROPRIA"> Trabalhando por conta pr&oacute;pria</option>
                       <option  name="FUTURO" value="SEM_OPINIAO"> Sem opini&atilde;o</option>
                       </select>
                       </div><hr /></div>
                       <div style="width:100%; float:left;">
                       <p>23. Onde voc&ecirc; encontra as informa&ccedil;&otilde;es que deseja saber sobre a Esmaltec? Assinale a principal alternativa.</p>
                       <div style="width:100%;"><select name="FONTE_INFORMACOES">
                       <?php if(isset($acomp) && $acomp[0]['FONTE_INFORMACOES'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['FONTE_INFORMACOES'])?>"><?php echo($acomp[0]['FONTE_INFORMACOES'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option   value="CONVERSA_CORREDORES">Conversa nos Corredores</option>
                       <option  value="QUADRO_AVISO"> Quadro de Avisos</option>
                       <option   value="COLEGAS_TRABALHO"> Colegas de Trabalho</option>
                       
                       <option  value="JORNAL_INTERNO"> Jornal Interno</option>
                       <option value="SUPERIOR_IMEDIATO"> Superior Imediato</option>
                       <option  "RECURSOS_HUMANOS"> Recursos Humanos</option>
                       </select>
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">                       
                       <p>24. Onde geralmente voc&ecirc;e resolve os problemas do trabalho que lhe afetam?</p>
                       <div style="width:100%;"><select name="PROBLEMAS_TRABALHO">
                       <?php if(isset($acomp) && $acomp[0]['PROBLEMAS_TRABALHO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['PROBLEMAS_TRABALHO'])?>"><?php echo($acomp[0]['PROBLEMAS_TRABALHO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option  alue="SINDICATO"> No sindicato</option>
                       <option value="SUPERIOR_IMEDIATO">Com o superior imediato</option>
                       
                       <option  value="DEPARTAMENTO_PESSOAL"> No departamento pessoal</option>
                       <option value="COLEGAS_TRABALHO"> Com colegas de trabalho</option></select>
                       
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">
                      <p>25. Voc&ecirc; conhece os descontos que s&atilde;o lan&ccedil;ados no seu contracheque?</p>
                       <div style="width:100%;"><select name="DESCONTOS">
                       <?php if(isset($acomp) && $acomp[0]['DESCONTOS'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['DESCONTOS'])?>"><?php echo($acomp[0]['DESCONTOS'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option  value="SIM"> Sim</option>
                       
                       <option value="NAO"> N&atilde;o</option>
                       </select>
                       </div><hr /></div>
                       
                       <div style="width:100%; float:left;">                       
                       <p>26. A empresa poderia melhorar se: Assinale a alternativa que tem maior impacto.</p>
                       <div style="width:100%;"><select name="EMPRESA_MELHORAR">
                       <?php if(isset($acomp) && $acomp[0]['EMPRESA_MELHORAR'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['EMPRESA_MELHORAR'])?>"><?php echo($acomp[0]['EMPRESA_MELHORAR'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option value="MELHORES_SALARIOS"> Pagasse melhores sal&aacute;rios
                       <option value="EMPRESA_MELHORAR" value="ESTABILIDADE_EMPREGO"/> Proporcionasse mais estabilidade no emprego</option>
                       <option value="TRATASSE_MELHOR"> Tratasse melhor os funcion&aacute;rios</option>
                       <option value="OPORTUNIDADE_CRESCIMENTO"> Proporcionasse mais oportunidades de crescimento</option>
                       <option  value="MAIS_TREINAMENTOS"> Realizasse mais treinamentos</option>
                       <option  value="MAIS_BENEFICIOS"> Oferecesse mais benef&iacute;cios</option></select></div><hr /></div>
                       <div style="width:100%; float:left;">
                       <p>27. Voc&ecirc; acha que seu chefe avalia seu trabalho de forma justa?</p>
                       <div style="width:100%;"><select name="AVALIACAO_TRABALHO">
                       <?php if(isset($acomp) && $acomp[0]['AVALIACAO_TRABALHO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['AVALIACAO_TRABALHO'])?>"><?php echo($acomp[0]['AVALIACAO_TRABALHO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>                     
                        <option  value="SIM">Sim</option>
                       <option  value="NAO">N&atilde;o</option>
                       <option  value="AS_VEZES">As Vezes</option></select>
                       </div><hr /></div>
                       
                      <!-- <div style="width:100%; float:left;">                       
                        <p>28. Ao realizar seu trabalho, voc&ecirc; procura obter resultados melhores do que aqueles esperados pela empresa?</p>
                        <div style="width:100%;"><select name="RESULTADO_EMPRESA">
                        <option value="">Escolha uma op&ccedil;&atilde;o</option>
                      <option value="SEMPRE"> Sempre</option>
                      <option value="QUASE_SEMPRE"/> Quase Sempre</option>
                      <option value="RARAMENTE"> Raramente</option>
                      <option value="NUNCA"> Nunca</option></select>
                       </div><hr/></div>-->
                       
                       <div style="width:100%; float:left;">                      
                       <p>29. Indique as duas principais raz&otilde;es pelas quais voc&ecirc; trabalha na empresa. Marque a principal e a segunda mais importante</p>
                       <div style="width:45%;">
                       <p>Raz&atilde;o Principal</p>
                       <select name="RAZAO_PRINCIPAL">
                       <?php if(isset($acomp) && $acomp[0]['RAZAO_PRINCIPAL'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['RAZAO_PRINCIPAL'])?>"><?php echo($acomp[0]['RAZAO_PRINCIPAL'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option value="SALARIO">Sal&aacute;rio</option>
                       <option value="ESTABILIDADE">Estabilidade no Emprego</option>
                       <option value="TRABALHO">O trabalho que realizo</option>
                       <option value="AMBIENTE">Ambiente de Trabalho</option> 
                       <option value="AUTONOMIA">Autonomia no Trabalho</option>
                       <option value="RECONHECIMENTO">Reconhecimento</option>
                       <option value="BENEFICIOS">Benef&iacute;cios oferecidos pela empresa</option>
                       <option value="RELACIONAMENTO">Relacionamento com a chefia</option>
                       <option value="OPCAO">A falta de op&ccedil;&atilde;o de um outro emprego</option>
                       <option value="PRESTIGIO">Prest&iacute;gio da empresa</option>
                       <option value="TREINAMENTO" >Possibilidade de treinamento</option>
                       <option value="PROGRESSO">As chances de progresso profissional</option>
                       </select></div>
                      
                         <div style="width:45%;">
                         <p>Raz&atilde;o Secund&atilde;ria</p>
                         <select name="RAZAO_SECUNDARIO">
                       <?php if(isset($acomp) && $acomp[0]['RAZAO_SECUNDARIO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['RAZAO_SECUNDARIO'])?>"><?php echo($acomp[0]['RAZAO_SECUNDARIO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option value="SALARIO">Sal&aacute;rio</option>
                       <option value="ESTABILIDADE">Estabilidade no Emprego</option>
                       <option value="TRABALHO">O trabalho que realizo</option>
                       <option value="AMBIENTE">Ambiente de Trabalho</option> 
                       <option value="AUTONOMIA">Autonomia no Trabalho</option>
                       <option value="RECONHECIMENTO">Reconhecimento</option>
                       <option value="BENEFICIOS">Benef&iacute;cios oferecidos pela empresa</option>
                       <option value="RELACIONAMENTO">Relacionamento com a chefia</option>
                       <option value="OPCAO">A falta de op&ccedil;&atilde;o de um outro emprego</option>
                       <option value="PRESTIGIO">Prest&iacute;gio da empresa</option>
                       <option value="TREINAMENTO" >Possibilidade de treinamento</option>
                       <option value="PROGRESSO">As chances de progresso profissional</option>
                         </select>
                         </div></div>
                         
                         <div style="width:100%; float:left;">                         
                         <p>30. De modo geral. Como você classifica a empresa em relação ao que ela era quando você começou a trabalhar aqui?</p>
                         <div style="width:100%;"><select name="EMPRESA_ANTES">
                         <?php if(isset($acomp) && $acomp[0]['EMPRESA_ANTES'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['EMPRESA_ANTES'])?>"><?php echo($acomp[0]['EMPRESA_ANTES'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha uma op&ccedil;&atilde;o</option>
                       <?php }?>
                       <option  value="MELHOR"> Melhor que antes</option>
                       <option value="IGUAL"> Igual</option>
                       <option   value="PIOR"> Pior que antes</option></select>
                       </div>
                        <div style="width:100%;">
                       <p>Coment&aacute;rio</p>
                       <textarea id="COMENTARIO_EMPRESA_ANTES" name="COMENTARIO_EMPRESA_ANTES" rows="4" cols="98" placeholder="Escreva"><?php if(isset($acomp) && $acomp[0]['COMENTARIO_EMPRESA_ANTES'] != NULL) echo utf8_encode(getClob($acomp[0]['COMENTARIO_EMPRESA_ANTES']));?></textarea></div><hr /></div>
                       <div style="width:100%; float:left;">
                       <p>31. As condições ambientais do seu local de trabalho são satisfatórias ?</p>
                        <div style="width:100%;">
                       Ilumina&ccedil;&atilde;o e Ventila&ccedil;&atilde;o
                       <select name="ILUMINACAO_VENTILACAO">
                       <?php if(isset($acomp) && $acomp[0]['ILUMINACAO_VENTILACAO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['ILUMINACAO_VENTILACAO'])?>"><?php echo($acomp[0]['ILUMINACAO_VENTILACAO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option value="SIM">Sim</option> <option value="NAO">N&atilde;o</option></select>
                      
                       Temperatura
                        <select name="TEMPERATURA">
                        <?php if(isset($acomp) && $acomp[0]['TEMPERATURA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['TEMPERATURA'])?>"><?php echo($acomp[0]['TEMPERATURA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option value="SIM">Sim</option> 
                       <option  value="NAO">N&atilde;o</option></select>
                       
                       Espa&ccedil;o f&iacute;sico
                        <select name="ESPACO_FISICO">
                        <?php if(isset($acomp) && $acomp[0]['ILUMINACAO_VENTILACAO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['ILUMINACAO_VENTILACAO'])?>"><?php echo($acomp[0]['ILUMINACAO_VENTILACAO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option value="SIM">Sim</option> 
                       <option value="NAO">N&atilde;o</option>
                       </select>
                       
                       Higiene / Limpeza
                        <select name="HIGIENE_LIMPEZA">
                        <?php if(isset($acomp) && $acomp[0]['HIGIENE_LIMPEZA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['HIGIENE_LIMPEZA'])?>"><?php echo($acomp[0]['HIGIENE_LIMPEZA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option value="SIM">Sim</option> 
                       <option value="NAO">N&atilde;o</option>
                       </select>
                       Banheiros / Vesti&aacute;rios
                        <select name="BANHEIROS_VESTIARIOS">
                        <?php if(isset($acomp) && $acomp[0]['BANHEIROS_VESTIARIOS'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['BANHEIROS_VESTIARIOS'])?>"><?php echo($acomp[0]['BANHEIROS_VESTIARIOS'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option value="SIM">Sim</option> 
                       <option  value="NAO">N&atilde;o</option>
                       </select>
                       </div><hr /></div>
                       
                   <div style="width:100%; float:left;">
                   <div style="width:100%;">
                     <p>33. Como você avalia os itens abaixos:</p>
                      <div style="width:33.5%; float:left;">
                     <p>Assist&ecirc;ncia m&eacute;dica</p>
                     <div style="width:33%.5;"><select name="ASSISTENCIA_MEDICA"> 
                       <?php if(isset($acomp) && $acomp[0]['ASSISTENCIA_MEDICA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['ASSISTENCIA_MEDICA'])?>"><?php echo($acomp[0]['ASSISTENCIA_MEDICA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option value="MUITO_BOM">Muito Bom</option>
                       <option value="SATISFATORIO"> Satisfat&oacute;rio</option>
                       <option  value="INSUFICIENTE"> Insuficiente</option></select>
                       </div><hr /></div></div>
                       <div style="width:33.5%; float:left;">
                       <p>Assist&ecirc;ncia odontol&oacute;liga</p>  
                        <div style="width:33.5%;"><select name="ASSISTENCIA_ODONTOLOGICA"> 
                     <?php if(isset($acomp) && $acomp[0]['ASSISTENCIA_ODONTOLOGICA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['ASSISTENCIA_ODONTOLOGICA'])?>"><?php echo($acomp[0]['ASSISTENCIA_ODONTOLOGICA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                     <option  value="MUITO_BOM">Muito Bom</option>
                     <option  value="SATISFATORIO"> Satisfat&oacute;rio</option>
                     <option  value="INSUFICIENTE"> Insuficiente</option></select>
                       </div><hr /></div>
                       <div style="width:33%; float:left;">
                       <p>Atendimento RH</p>  
                       <div style="width:33%;"><select name="ATENDIMENTO_RH"> 
                     <?php if(isset($acomp) && $acomp[0]['ATENDIMENTO_RH'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['ATENDIMENTO_RH'])?>"><?php echo($acomp[0]['ATENDIMENTO_RH'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                     <option  value="MUITO_BOM">Muito Bom</option>
                     <option  value="SATISFATORIO"> Satisfat&oacute;rio</option>
                     <option  value="INSUFICIENTE"> Insuficiente</option></select>
                       </div><hr /></div>
                       <div style="width:33%; float:left;">
                       <p>Conv&ecirc;nios</p>  
                        <div style="width:33%;"><select name="CONVENIOS"> 
                    <?php if(isset($acomp) && $acomp[0]['CONVENIOS'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['CONVENIOS'])?>"><?php echo($acomp[0]['CONVENIOS'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                     <option  value="MUITO_BOM">Muito Bom</option>
                     <option  value="SATISFATORIO"> Satisfat&oacute;rio</option>
                     <option  value="INSUFICIENTE"> Insuficiente</option></select>
                       </div><hr /></div>
                       <div style="width:33.5%; float:left;">
                       <p>Empr&eacute;stimos</p>  
                        <div style="width:33.5%;"><select name="EMPRESTIMOS"> 
                      <?php if(isset($acomp) && $acomp[0]['EMPRESTIMOS'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['EMPRESTIMOS'])?>"><?php echo($acomp[0]['EMPRESTIMOS'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option  value="MUITO_BOM">Muito Bom</option>
                       <option  value="SATISFATORIO"> Satisfat&oacute;rio</option>
                       <option  value="INSUFICIENTE"> Insuficiente</option></select>
                       </div><hr /></div>
                       
                       <div style="width:33.5%; float:left;">
                       <p>Lojinha</p>
                         <div style="width:33.5%;"><select name="LOJINHA"> 
                       <?php if(isset($acomp) && $acomp[0]['LOJINHA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['LOJINHA'])?>"><?php echo($acomp[0]['LOJINHA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option value="MUITO_BOM">Muito Bom</option>
                       <option value="SATISFATORIO"> Satisfat&oacute;rio</option>
                       <option value="INSUFICIENTE"> Insuficiente</option></select>
                        </div><hr /></div>
                       
                       
                       <div style="width:33%; float:left;">
                     <p>Medicina / Ambulat&oacute;rio</p>
                       <div style="width:33%;"><select name="MEDICINA_AMBULATORIO"> 
                       <?php if(isset($acomp) && $acomp[0]['MEDICINA_AMBULATORIO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['MEDICINA_AMBULATORIO'])?>"><?php echo($acomp[0]['MEDICINA_AMBULATORIO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option  value=" MUITO_BOM">Muito Bom</option>
                       <option value="SATISFATORIO"> Satisfat&oacute;rio</option>
                       <option  value="INSUFICIENTE"> Insuficiente</option></select>
                        </div><hr /></div>
                        
                       <div style="width:33.5%; float:left;">
                        <p>Posto banc&aacute;rio</p>
                         <div style="width:33.5%;"><select name="POSTO_BANCARIO"> 
                       <?php if(isset($acomp) && $acomp[0]['POSTO_BANCARIO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['POSTO_BANCARIO'])?>"><?php echo($acomp[0]['POSTO_BANCARIO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option  value="MUITO_BOM">Muito Bom</option>
                       <option  value="SATISFATORIO"> Satisfat&oacute;rio</option>
                       <option  value="INSUFICIENTE"> Insuficiente</option></select>
                       </div><hr /></div>
                       
                       <div style="width:33.5%; float:left;">
                        <p>Qualidade de Vida</p>
                         <div style="width:33.5%;"><select name="QUALIDADE_VIDA"> 
                       <?php if(isset($acomp) && $acomp[0]['QUALIDADE_VIDA'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['QUALIDADE_VIDA'])?>"><?php echo($acomp[0]['QUALIDADE_VIDA'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option value="MUITO_BOM">Muito Bom</option>
                       <option value="SATISFATORIO"> Satisfat&oacute;rio</option>
                       <option value="INSUFICIENTE"> Insuficiente</option></select>
                       </div><hr /></div>
                       
                       <div style="width:33%; float:left;">
                       <p>Recrutamento e Sele&ccedil;&atilde;o</p>
                        <div style="width:33%;"><select name="RECRUTAMENTO_SELECAO"> 
                       <?php if(isset($acomp) && $acomp[0]['RECRUTAMENTO_SELECAO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['RECRUTAMENTO_SELECAO'])?>"><?php echo($acomp[0]['RECRUTAMENTO_SELECAO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option  value="MUITO_BOM">Muito Bom</option>
                       <option   value="SATISFATORIO"> Satisfat&oacute;rio</option>
                       <option   value="INSUFICIENTE"> Insuficiente</option></select>
                        </div><hr /></div>
                        
                        <div style="width:33%; float:left;">
                       <p>Refeit&oacute;rio</p> 
                        <div style="width:33%;"><select name="REFEITORIO"> 
                      <?php if(isset($acomp) && $acomp[0]['REFEITORIO'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['REFEITORIO'])?>"><?php echo($acomp[0]['REFEITORIO'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option value="MUITO_BOM">Muito Bom</option>
                        
                       <option value="SATISFATORIO"> Satisfat&oacute;rio</option>
                       <option value="INSUFICIENTE"> Insuficiente</option></select> 
                       </div><hr /></div>
                        
                        <div style="width:33.5%; float:left;">
                       <p>Transporte</p>
                        <div style="width:33.5%;"><select name="TRANSPORTE"> 
                       <?php if(isset($acomp) && $acomp[0]['TRANSPORTE'])
                       {?>
                       <option selected="selected" value="<?php echo($acomp[0]['TRANSPORTE'])?>"><?php echo($acomp[0]['TRANSPORTE'])?> </option>
                       <?php }else{?>
                       
                       <option value="">Escolha</option>
                       <?php }?>
                       <option  value="MUITO_BOM">Muito Bom</option>
                       <option  value="SATISFATORIO"> Satisfat&oacute;rio</option>
                       <option  value="INSUFICIENTE"> Insuficiente</option></select>
                       </div><hr /></div></div> 
                        <div style="width:33%; float:left; ">
                       <p style="height:15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <div style="width:33%; float:left;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       </div></div></div>                     
                       
                       
					 <div style="float:left; width:14.5%;" align="right">
						              
                       
					 </div>
					 <div style="float:right; width:85.3%;" align="left">
						<!--<input class="normal" type="hidden" name="COD_ACOMP_FALTAS" id="COD_ACOMP_FALTAS" value="<?php if(isset($acomp)) echo $acomp[0]['COD_ACOMP_FALTAS'];?>">
                        <p><input type="text" class="normal required" name="SUPERVISOR" id="SUPERVISOR" size="80" value="<?php if(isset($acomp)) echo utf8_encode($acomp[0]['SUPERVISOR'])." - ".$acomp[0]['MATRICULA_SUPERVISOR'];?>"/></p>
                        <p><input type="text" class="normal required" name="LIDER" id="LIDER" size="80" value="<?php if(isset($acomp)) echo utf8_encode($acomp[0]['LIDER'])." - ".$acomp[0]['MATRICULA_LIDER'];?>"/></p>
                         <p><input type="text" class="readonly" name="QTD_FALTAS" id="QTD_FALTAS" size="10" value="<?php if(isset($acomp)){ echo utf8_encode($acomp[0]['QTD_FALTAS']);}else{if($exist) echo $funcionario[0]['QTD'];}?>"/>&nbsp; <span id="vis_fal" style="text-decoration:underline !important; cursor:pointer;">Visualizar Datas das faltas</span></p>
                   	    <p>
                      	   <label>
                      	     <input type="radio" name="DESLIGAMENTO" <?php if(isset($acomp)){ if($acomp[0]['DESLIGAMENTO'] == "S"){ echo 'checked="checked"';}}?>  value="S" id="DESLIGAMENTO_0" />
                      	     Sim</label>
                      	   &nbsp;&nbsp;
                      	   <label>
                      	     <input type="radio" checked="checked" name="DESLIGAMENTO" <?php if(isset($acomp)){ if($acomp[0]['DESLIGAMENTO'] == "N"){ echo 'checked="checked"';}}?> value="N" id="DESLIGAMENTO_1" />
                      	     Não</label>
                   	   </p>
						<p>
                       	  <select id="MOTIVO" name="MOTIVO" class="required">
                            	<option value="ALCOOLISMO">Alcoolismo</option>
                                <option value="ASSEDIO_MORAL">Ass&eacute;dio moral</option>
                                <option value="CLIMA_ORGANIZACIONAL">Clima Organizacional</option>
                            	<option value="COMUNICACAO_DEFICIENTE">Comunica&ccedil;&atilde;o Deficiente</option>
                                <option value="DROGAS">Drogas</option>
                                <option value="FEEDBACK">Feedback</option>
                                <option value="IMATURIDADE_PROFISSIONAL">Imaturidade Profissional</option>
                                <option value="INFRAESTRUTURA_DEFICIENTE">Infraestrutura Deficiente</option>			
                                <option value="LIDERANCA_DESPREPARADA">Lideran&ccedil;a Despreparada</option>
                                <option value="METAS_INTANGIVEIS">Metas intang&iacute;veis</option>
                                <option value="OUTROS">Outros</option>
                                <option value="PREFERENCIALISMO">Preferencialismo</option>
                                <option value="PROBLEMAS_FAMILIARES">Problemas Familiares</option>
                                <option value="QUALIDADE_DE_VIDA">Qualidade de Vida</option>
                                
                                
                            </select>
                        </p>
                        <p style="height: 63px;"> <textarea  class="normal required" cols="98" rows="4" id="COMENTARIOS" name="COMENTARIOS"><?php if(isset($acomp) && $acomp[0]['COMENTARIOS'] != NULL) echo utf8_encode(getClob($acomp[0]['COMENTARIOS']));?></textarea></p>
                        <p>
                       	  <label>
                        	  <input type="radio" name="INSAT_CHEFIA" <?php if(isset($acomp)){ if($acomp[0]['INSAT_CHEFIA'] == "S"){ echo 'checked="checked"';}}?> value="S" id="INSAT_CHEFIA_0" />
                        	  Sim</label>
							&nbsp;&nbsp;
                        	<label>
                        	  <input type="radio" checked="checked" name="INSAT_CHEFIA" <?php if(isset($acomp)){ if($acomp[0]['INSAT_CHEFIA'] == "N"){ echo 'checked="checked"';}}?> value="N" id="INSAT_CHEFIA_1" />
                        	  Não</label>

                        </p>
                        <p class="insatis" style="height: 63px;"><textarea class="normal required" cols="98" rows="4" id="PORQ_INSATISFACAO" name="PORQ_INSATISFACAO"><?php if(isset($acomp) && $acomp[0]['PORQ_INSATISFACAO'] != NULL) echo utf8_encode(getClob($acomp[0]['PORQ_INSATISFACAO']));?></textarea></p>
					   <p style="height: 63px;"><textarea class="normal required" cols="98" rows="4" id="JUSTIFICATIVA" name="JUSTIFICATIVA"><?php if(isset($acomp) && $acomp[0]['JUSTIFICATIVA'] != NULL) echo utf8_encode(getClob($acomp[0]['JUSTIFICATIVA']));?></textarea></p>
                        <p style="height: 63px;"><textarea class="normal" cols="98" rows="4" id="SUGESTOES" name="SUGESTOES"><?php if(isset($acomp) && $acomp[0]['SUGESTOES'] != NULL) echo utf8_encode(getClob($acomp[0]['SUGESTOES']));?></textarea></p>
                        <p style="height: 25px;"><textarea class="normal" cols="98" rows="4" id="FEEDBACK_SUPERVISOR" name="FEEDBACK_SUPERVISOR"><?php if(isset($acomp) && $acomp[0]['FEEDBACK_SUPERVISOR'] != NULL) echo utf8_encode(getClob($acomp[0]['FEEDBACK_SUPERVISOR']));?></textarea></p>
						<p>
						<p>
                        <br/>-->
                        <div style="width:100%; float:left;">
                       <p style="margin-left:-17%;">34. Que sugestões você daria para tonar a empresa um lugar melhor para se trabalhar ?</p>
                       
                       <div style="width:100%;">
                       <div style="width:100%; float:left;">
                       <textarea style="margin-left:-17%;" id="COMENTARIO_INSATISFACAO" name="COMENTARIO_INSATISFACAO" rows="4" cols="98" placeholder="Escreva"><?php if(isset($acomp) && $acomp[0]['COMENTARIO_INSATISFACAO'] != NULL) echo utf8_encode(getClob($acomp[0]['COMENTARIO_INSATISFACAO']));?></textarea></p>
                       <p style="margin-left:-17%" id="DATA_ENTREVISTA">Data da entrevista: <input type="text" id="data_cadastro" name="data_cadastro" maxlength="19" size="19" value="<?php if(isset($acomp) && $acomp[0]['DATA_ACOMP'] != NULL) echo utf8_encode(($acomp[0]['DATA_ACOMP']));?>"/></p></div><hr />
                       
                        <div style="width:100%;">
						<button class="normal" type="submit" id="Cadastrar"  name="Cadastrar" value="Cadastrar" />Cadastrar</button>
                        <button type="button" id="Limpar"  name="Limpar" value="Limpar" />Limpar</button>
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
   <div id="datatables" style="width:98.5%; margin-left:0.75%;">
      <table cellpadding="0" cellspacing="0" border="0" class="display" id="record">
        <thead>
          <tr>
          	 <th>Matricula</th>
          	 <th>Nome</th>
          	 <th>Supervisor</th>
          	 <th>Líder</th>
             <th>Faltas</th>
             <th>Periodo</th>
             <th>Adv.</th>
             <th>Sus.</th>
             <th>Data do Acompanhamento</th>
             <th>A&ccedil;&otilde;es</th>
          </tr>
        </thead>
        <tbody>
         <?php foreach($acompFaltas as $row):?>
         	<?php
					$mat = $row['NUM_MATRICULA'];
					$suspensao = $advertencia = 'xx';
					if($_SERVER['REMOTE_ADDR'] == '192.168.194.140'){
						//Debug::dump($row);	
					}
					if(is_array($medidaMatArray)){
						if(array_key_exists($mat,$medidaMatArray)){
							$row_medida = $medidaMatArray[$mat];
							$suspensao = isset($row_medida['SUSPENSAO']) ? $row_medida['SUSPENSAO'] : 'XX';	
							$advertencia = isset($row_medida['ADVERTENCIA']) ? $row_medida['ADVERTENCIA'] : 'XX';	
						}
					}
					?>
          <tr>
          	<td align="center"><?php echo $row['NUM_MATRICULA']; ?></td>
          	<td align="center"><?php echo utf8_encode($row['NOM_COMPLETO']); ?></td>
          	<td align="center"><?php echo utf8_encode($row['SUPERVISOR']); ?></td>
          	<td align="center"><?php echo utf8_encode($row['LIDER']); ?></td>
            <td align="center"><?php echo $row['QTD_FALTAS'];?></td>
            <td align="center"><?php echo utf8_encode($row['PERIODO']); ?></td>
            <td align="center"><?php echo $advertencia;?></td>
            <td align="center"><?php echo $suspensao;?></td>
            <td align="center"><?php echo toDate($row['DATA_ACOMP']); ?></td>
            <td align="left" ><a href="<?php echo base_url() . 'index.php/acompFaltas/editar/' . $row['COD_ACOMP_FALTAS'].'/'.str_replace('/','-',$row['PERIODO']); ?>"><img style="border:0px;" src="<?php echo base_url(); ?>images/tables/action2.gif"></a>
            <span class="deletar" style="cursor:pointer;" onclick="deletar('<?php echo $row['COD_ACOMP_FALTAS']; ?>','<?php echo str_replace('/','-',$row['PERIODO']);?>');"><img style="border:0px;" src="<?php echo base_url(); ?>images/tables/action4.gif"></span>
            </td>
          </tr>
         <?php endforeach; ?>
        </tbody>
      </table>  
   </div> 
</div>

<div id="dialog" style="display:none;" title="Datas das Faltas">
   <?php if(isset($fal)){
	   $color = "#E1F0FF";
		foreach($fal as $row){
			if($color == "#E1F0FF")
				$color = "#FFFFFF";
			else
				$color = "#E1F0FF";
			echo "<p style='background:".$color."; magin:0; padding:5px;'>".$row['DAT_FALTA']."</p>";
		}
	}?>
</div>
<div id="dialog-historicoFaltas" style="display:none; width:500px;" title="Historico de Faltas">
   <?php if(isset($historicoQtdFaltas)){
	   $color = "#E1F0FF";
		
		$tabela = "<table border='1' bordercolor='#000000' style='border:1px solid;'>
					<tr bgcolor='".$color."'>
						<td width='80px' align='center'>Janeiro</td>
						<td width='80px' align='center'>Fevereiro</td>
						<td width='80px' align='center'>Março</td>
						<td width='80px' align='center'>Abril</td>
						<td width='80px' align='center'>Maio</td>
						<td width='80px' align='center'>Junho</td>
						<td width='80px' align='center'>Julho</td>
						<td width='80px' align='center'>Agosto</td>
						<td width='80px' align='center'>Setembro</td>
						<td width='80px' align='center'>Outubro</td>
						<td width='80px' align='center'>Novembro</td>
						<td width='80px' align='center'>Dezembro</td>
					</tr>";
		
		foreach($historicoQtdFaltas as $row){
		$tabela.=  "<tr>
						<td align='center'>".$row['JANEIRO']."</td>
						<td align='center'>".$row['FEVEREIRO']."</td>
						<td align='center'>".$row['MARCO']."</td>
						<td align='center'>".$row['ABRIL']."</td>
						<td align='center'>".$row['MAIO']."</td>
						<td align='center'>".$row['JUNHO']."</td>
						<td align='center'>".$row['JULHO']."</td>
						<td align='center'>".$row['AGOSTO']."</td>
						<td align='center'>".$row['SETEMBRO']."</td>
						<td align='center'>".$row['OUTUBRO']."</td>
						<td align='center'>".$row['NOVEMBRO']."</td>
						<td align='center'>".$row['DEZEMBRO']."</td>
					</tr>";
		}
		
		$tabela .= " </table>";
		
		if(isset($historicoDatasFaltas)){
			$datas = "";
			foreach($historicoDatasFaltas as $dat){
				$datas .= $dat['DAT_FALTA'].' , ';
			}
			$datas = substr($datas,0,(strlen($datas)-3));
			$tabelaDatas = "<p><table border='1' bordercolor='#000000' style='border:1px solid;'>
							<tr>
							  <td bgcolor='".$color."' width='150px' align='center'>Datas Das Faltas</td>
							  <td> ".$datas." </td>
							</tr>
						</table></p>
						";
		}
		
		echo $tabela;
		echo $tabelaDatas;
	}?>
</div>

<div id="dialog-historicoMedidas" style="display:none; width:500px;" title="Historico de Medidas Disiplinares">
   <?php if(isset($historicoMedidas)){
	   $color = "#E1F0FF";
		
		$tabela = "<table border='1' bordercolor='#000000' style='border:1px solid;'>
					<tr bgcolor='".$color."'>
						<td width='80px' align='center'>Medida</td>
						<td width='80px' align='center'>Janeiro</td>
						<td width='80px' align='center'>Fevereiro</td>
						<td width='80px' align='center'>Março</td>
						<td width='80px' align='center'>Abril</td>
						<td width='80px' align='center'>Maio</td>
						<td width='80px' align='center'>Junho</td>
						<td width='80px' align='center'>Julho</td>
						<td width='80px' align='center'>Agosto</td>
						<td width='80px' align='center'>Setembro</td>
						<td width='80px' align='center'>Outubro</td>
						<td width='80px' align='center'>Novembro</td>
						<td width='80px' align='center'>Dezembro</td>
					</tr>";
		$medida = "";
		foreach($historicoMedidas as $row){
			if($row['OQUE'] == 'S'){$medida = "SUSPENSÃO";} else if($row['OQUE'] == 'A'){$medida = "ADVERTÊNCIA";}
		$tabela.=  "<tr>
						<td align='center'>".$medida."</td>
						<td align='center'>".$row['JANEIRO']."</td>
						<td align='center'>".$row['FEVEREIRO']."</td>
						<td align='center'>".$row['MARCO']."</td>
						<td align='center'>".$row['ABRIL']."</td>
						<td align='center'>".$row['MAIO']."</td>
						<td align='center'>".$row['JUNHO']."</td>
						<td align='center'>".$row['JULHO']."</td>
						<td align='center'>".$row['AGOSTO']."</td>
						<td align='center'>".$row['SETEMBRO']."</td>
						<td align='center'>".$row['OUTUBRO']."</td>
						<td align='center'>".$row['NOVEMBRO']."</td>
						<td align='center'>".$row['DEZEMBRO']."</td>
					</tr>";
		}
		
		$tabela .= " </table>";
		
		$tabelaDatas = "<p>";
		$tipo = "";
		if(isset($historicoMedidas[0]['SUSPENSAO'])){
			$tabelaDatas .= "<table border='1' bordercolor='#000000' style='border:1px solid;'>
							<tr>
							  <td bgcolor='".$color."' width='180px' align='center'>Datas Das Suspensões</td>
							  <td> ".$historicoMedidas[0]['SUSPENSAO']." </td>
							</tr>
						</table>
						";		
		}
		if(isset($historicoMedidas[0]['ADVERTENCIA'])){
			$tabelaDatas .= "<table border='1' bordercolor='#000000' style='border:1px solid;'>
							<tr>
							  <td bgcolor='".$color."' width='180px' align='center'>Datas Das Advertências</td>
							  <td> ".$historicoMedidas[0]['ADVERTENCIA']." </td>
							</tr>
						</table>
						";		
		}
		$tabelaDatas .= "</p>";
		echo $tabela;
		echo $tabelaDatas;
	}?>
</div>

<div id="dialog-historicoAtestado" style="display:none; width:500px;" title="Histórico de Atestado">

	<!--table border="1" bordercolor="#000000" style="border:1px solid;">
    <tbody><tr bgcolor="#E1F0FF">
    	<td width="40px" align="center">#</td>
    	<td width="80px" align="center">Período</td>
      <td width="80px" align="center">Saída</td>
      <td width="80px" align="center">Retorno</td>
      <td width="80px" align="center">Qtd. Dias</td>
      <td width="80px" align="center">CID</td>
      <td width="240px" align="center">Descrição</td>
      <td width="80px" align="center">CRM</td>
      <td width="160px" align="center">Médico</td>
  
    </tr><tr>
    	<td align="center">1</td>
    	<td align="center">Julho</td>
      <td align="center">01/07/2014</td>
      <td align="center">03/07/2014</td>
      <td align="center">2</td>
      <td align="center">G43.0</td>
      <td align="center">Enxaqueca sem aura (enxaqueca comum)</td>
      <td align="center">CRM-XXXX</td>
      <td align="center">Fulano do Beltrano Ciclano</td>
    </tr> 
    <tr>
    	<td align="center">2</td>
    	<td align="center">Junho</td>
      <td align="center">10/06/2014</td>
      <td align="center">24/06/2014</td>
      <td align="center">13</td>
      <td align="center">K08.8</td>
      <td align="center">Outros transtornos dos dentes e de suas estruturas de sustentação</td>
      <td align="center">CRO-XXXX</td>
      <td align="center">Ciclano do Fulano</td>
    </tr> 
  </tbody>
  
</table-->
	<?php
	if(isset($historicoAtestado)){
		if(is_array($historicoAtestado) && (count($historicoAtestado))){
			$i = 1;
			?>
			<table border="1" bordercolor="#000000" style="border:1px solid;">
    		<tbody><tr bgcolor="#E1F0FF">
          <td width="40px" align="center">#</td>
          <td width="80px" align="center">Período</td>
          <td width="80px" align="center">Saída</td>
          <td width="80px" align="center">Retorno</td>
          <td width="80px" align="center">Qtd. Dias</td>
          <td width="80px" align="center">CID</td>
          <td width="240px" align="center">Descrição</td>
          <td width="80px" align="center">CRM</td>
          <td width="160px" align="center">Médico</td>
      	</tr>
        <?php
			foreach($historicoAtestado as $atestado){
				?>
        <tr style="font-size:11px;">
          <td align="center"><?php echo $i;?></td>
          <td align="center"><?php echo $atestado['periodo'];?></td>
          <td align="center"><?php echo $atestado['saida'];?></td>
          <td align="center"><?php echo $atestado['retorno'];?></td>
          <td align="center"><?php echo $atestado['qtd_dia'];?></td>
          <td align="center"><?php echo $atestado['cid'];?></td>
          <td align="center"><?php echo utf8_encode($atestado['cid_descricao']);?></td>
          <td align="center"><?php echo $atestado['crm'];?></td>
          <td align="center"><?php echo $atestado['medico'];?></td>
        </tr> 
        <?php
				$i++;
			}
			?>
      </tbody></table>
      <?php
		}else{
			echo $historicoAtestado;		
		}
	}else{
		echo 'Sem resultados';
	}
	?>

</div>