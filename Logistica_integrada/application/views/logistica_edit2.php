<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="imagetoolbar" content="no" />
<title>Administration Panel</title>

<link media="screen" rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/admin.css"  />
<script type="text/javascript" src="<?php echo base_url();?>js/ui/development-bundle/jquery-1.7.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/ui/development-bundle/ui/jquery.ui.autocomplete.js"></script>
<script src="<?php echo base_url();?>js/jquery-ui-1.8.10.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/ui/development-bundle/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/ui/development-bundle/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/ui/development-bundle/ui/jquery.ui.tabs.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/ui/development-bundle/ui/jquery.ui.dialog.js"></script>
<!--[if lte IE 6]><link media="screen" rel="stylesheet" type="text/css" href="css/admin-ie.css" /><![endif]-->
<link rel="stylesheet" href="<?php echo base_url();?>js/ui/development-bundle/themes/redmond/jquery.ui.all.css">	
<link rel="stylesheet" href="<?php echo base_url();?>js/ui/development-bundle/demos/demos.css">
<link rel="stylesheet" href="<?php echo base_url();?>js/thickbox/thickbox.css">
<link rel="stylesheet" href="<?php echo base_url();?>js/jquery-ui-1.8.20.custom.css">
<link rel="stylesheet" href="<?php echo base_url();?>js/datatables/estilo/table_jui.css" />
<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/jquery.mask.js"></script>
<script src="<?php echo base_url();?>js/jquery-validation-1.9.0/jquery.validate.js"></script>
<script src="<?php echo base_url();?>js/thickbox/thickbox.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/cep.js"></script>
<script>
$(function(){
	//$.localise('ui-multiselect', {/*language: 'en',*/ path: 'js/locale/'});
	//$(".multiselect").multiselect();
	//$('#switcher').themeswitcher();
		});
$(document).ready( function() {
	$("#formLogistica").validate({
		// Define as regras
		rules:{
			id_cliente:{
				// campoNome será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true
			},
			quantidade:{
				// campoNome será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true
			},
			nota_fiscal:{
				// campoEmail será obrigatório (required) e precisará ser um e-mail válido (email)
				required: true
			},
			carga:{
				// campoMensagem será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true
			},
			data_saida:{
				// campoMensagem será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true,
			},
			hora_entrada:{
				// campoMensagem será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true,
			},
			tipo_documento:{
				// campoMensagem será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true,
			},
			local_carregamento:{
				required: true,
			},
			doca:{
				required: true,
			},
			conferente:{
				required: true,
			},
			documentos:{
				// campoMensagem será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true,
			}
			
		},
		// Define as mensagens de erro para cada regra
		messages:{
			id_cliente:{
				required: "Selecione um Cliente",
				minLength: "O seu nome deve conter, no mínimo, 2 caracteres"
			},
			quantidade:{
				required: "Digite a quantidade",
				minLength: "O seu nome deve conter, no mínimo, 2 caracteres"
			},
			nota_fiscal:{
				required: "Digite o numero da NF",
				email: "Digite um e-mail válido"
			},
			carga:{
				required: "Qual tipo de carga?",
				minLength: "A sua mensagem deve conter, no mínimo, 2 caracteres"
			},
			data_saida:{
				required: "Campo Obrigatório",
				minLength: "A sua mensagem deve conter, no mínimo, 2 caracteres"
			},
			hora_entrada:{
				required: "Campo Obrigatírio",
				minLength: "A sua mensagem deve conter, no mínimo, 2 caracteres"
			},
			tipo_documento:{
				required: "Campo Obrigatório",
				minLength: "A sua mensagem deve conter, no mínimo, 2 caracteres"
			},
			local_carregamento:{
				required: "Campo Obrigatório",
			},
			doca:{
				required: "Campo Obrigatório",
			},
			conferente:{
				required: "Campo Obrigatório",
				
			},
			documentos:{
				required: "Campo Obrigatório",
				minLength: "A sua mensagem deve conter, no mínimo, 2 caracteres"
			}
			
		}
	});
	
});


	
$(document).ready(function() {
		
	$(function() {
						
		$("#id_cliente").autocomplete({
			source: function(request, response) {
				$.ajax({ url: "<?php echo site_url('logistica/clientes'); ?>",
				data: { term: $("#id_cliente").val()},
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


	
	$(function() {
		$('#data_saida').mask('99/99/9999');
		$('#data_atual').mask('99/99/9999');
		$('#data_pesquisa1').mask('99/99/9999');
		$('#data_pesquisa2').mask('99/99/9999');
		$('#hora_chegada').mask('99:99');
		$('#hora_entrada').mask('99:99');
		$('#placa_veiculo').mask('AAA 9999');
		$('#placa_cavalo').mask('AAA 9999');
				
		// outras opções de máscara
		$('.date_time').mask('99/99/9999 99:99:??');
		$('.date_time').mask('99/99/9999 99:99');
		$('.cep').mask('99999-999');
		$('.phone').mask('9999-9999');
		$('#telefone_contato').mask('(99) 99999-9999');
		$('.phone_us').mask('(999) 999-9999');
		
		var options =  {onComplete: function(cep) {
						  alert('Mask is done!:' + cep);
						},
						 onKeyPress: function(cep, event, keyCode){
						  alert('An key was pressed!:' + cep + ' event: ' + event + ' keyCode: ' + keyCode);
						}};

		$('.cep_with_callback').mask('00000-000', options);

		var options =  {onKeyPress: function(cep){
						var masks = ['00000-000', '0-00-00-00'];
						  mask = (cep.length>7) ? masks[1] : masks[0];
						$('.crazy_cep').mask(mask, this);
					  }};

		$('.crazy_cep').mask('00000-000', options);

		$('.cpf').mask('999.999.999-99', {reverse: true});
		$('.money').mask('000.000.000.000.000,00', {reverse: true});

	});	
	function validar() {
		
		if($('#tamanho_veiculo').val()=='')
		{
			alert('tamanho do veículo obrigatorio');
			return false;
		}

		var id_cliente = formLogistica.id_cliente.value;
		var acao = formLogistica.acao.value;
		var doca = formLogistica.doca.value;
		var local_carga = formLogistica.local_carregamento.value;
		var conferencia = formLogistica.conferente.value;
		var capataz = formLogistica.capatazia.value;
		var status_carga = $("#STATUS_CARGA").val();
		
		if (local_carga == "" && status_carga =="EXP") {
			alert('Defina o local para carregamento');
			formLogistica.local_carregamento.focus();
			return false;		
		}
		if (local_carga == "" && acao =="ATU") {
			alert('Defina o local para carregamento');
			formLogistica.local_carregamento.focus();
			return false;		
		}
		if (id_cliente == "") {
			alert('Preencha o campo Cliente');
			formLogistica.id_cliente.focus();
			return false;		
		}

		if(doca == "" && status_carga=="EXP" ){
			alert('Defina uma Doca');
			formLogistica.doca.focus();
			return false;
		}

		if(doca == "" && acao =="ATU" ){
			alert('Defina uma Doca');
			formLogistica.doca.focus();
			return false;
		}		

		if(conferencia =="" && status_carga =="EXP"){
			alert('Defina o conferente da carga');
			formLogistica.conferente.focus();
			return false;
		}

		if(conferencia =="" && acao =="ATU"){
			alert('Defina o conferente da carga');
			formLogistica.conferente.focus();
			return false;
		}

		if(capataz =="" && status_carga =="EXP"){
			alert('Defina a Capatazia');
			formLogistica.capatazia.focus();
			return false;
		}

		if(capataz =="" && acao=="ATU"){
			alert('Defina a Capatazia');
			formLogistica.capatazia.focus();
			return false;
		}

		if (acao == "") {
			alert('Selecione uma opção do campo [Ação]');
			formLogistica.acao.focus();
			return false;	
		}		
		
		if(id_cliente != "" && acao != ""){
			val = validDistrib();
			if(val == "true"){
				return true;
			}else{
				//alert("Escolha uma distribuição para a carga.");
				//return false;
				return true;
			}
		 }
		
	}
	
	function validDistrib(){
	
		$.ajax({ url: "<?php echo site_url('logistica/verificDistrib'); ?>",
				data: { ID_CARGA: $("#ID_CARGA").val()},
				dataType: "html",
				type: "POST",
				async: false,
				success: function(data){
					$("#return").val(data);	
				}	
		});
		
		return $("#return").val();
	}

</script>

<script type="text/javascript">
	function complementa_carga(info)
	{
		var opt = info.toString();
		if (opt == 'COM')
		{
			$("#span_complementar").css('display','');
		}
		else
		{
			$("#span_complementar").css('display','none');	
		}
	}
</script>

<style type="text/css">
            
	label { display: block; margin-top: 10px; }
	label.error { width:200px;  color: red; margin: -7px 0.5em 0 110px; vertical-align: top; font-size: 10px }
	p { clear: both; }
	.submit { margin-top: 1em; }
	em { font-weight: bold; padding-right: 1em; vertical-align: top; }

	input.text { 
		border: 1px solid #cecece;
		display: block;
		background: #fefefe ;
		float: left;
		padding: 2px 4px;
		width: 194px;
		margin: 5px 8px 0 0;
		line-height: normal;
	}
	
	
			
			
			
</style>

</head>
<body>
<form>
<input name="return" id="return" type="hidden" value="" />
</form>
<div class="section">
						
					
<div class="section_content">

<br /><h3>Datalhes da Carga</h3><br />
	<ul class="system_messages">
		<?php //if ($PortariaOk){ 
		?>
	
		<!--<li class="green"><span class="ico"></span><strong class="system_title">Cadastro realizado com sucesso! Aguarde autorização</strong></li>-->
	<?php //}?>
	
	<?php if (validation_errors()) { ?>
	<li class="red"><span class="ico"></span><strong class="system_title"><?php echo validation_errors(); ?> </strong></li>
	<? } ?>
</ul>


<? if ($ExpedicaoOk){
	
?>
	<form id="formLogistica" class="search_form general_form" action="<?php echo base_url();?>index.php/expedicao/editar" method="post">
<?php } else {
	
?>
	<form id="formLogistica" class="search_form general_form" action="<?php echo base_url();?>index.php/logistica/editar" method="post">
<?php } ?>
                                              
 <input name="ID_CARGA" id="ID_CARGA" type="hidden" value="<?php echo $var = empty($carga[0]['ID_CARGA']) ? "" : $carga[0]['ID_CARGA'] ;?>" />
 <input name="COD_RECEBEDOR" type="hidden" value="<?php echo $var = empty($carga[0]['COD_RECEBEDOR']) ? "" : $carga[0]['COD_RECEBEDOR'] ;?>" />
 <input name="STATUS_CARGA" id="STATUS_CARGA" type="hidden" value="<?php echo $var = empty($carga[0]['STATUS']) ? "" : $carga[0]['STATUS'] ;?>" />
 
                                                 <input name="return" type="hidden" value="" />
	<!--[if !IE]>start fieldset<![endif]-->
	<fieldset>
		<!--[if !IE]>start forms<![endif]-->
		<div class="forms">											
			<div class="row" style="width:350px;float:left;" >
			<!---Informações cadastrada pela Portaria-->
			<label style="line-height:5px;padding-right:20px">Data</label>
                                                            
			<?php if(!empty($carga[0]['DATA_ATUAL'])){ 
				echo sqlToDate($carga[0]['DATA_ATUAL']);
			} else { ?>
				<span class="input_wrapper"><input class="text" name="data_atual" id="data_inicial" type="text" value="<?php echo $var = empty($carga[0]['data_atual']) ? "" : $carga[0]['data_atual'] ;?>" /></span>
			<?php }?>
			<br />
		<label style="width:120px;line-height:5px;">Transportadora</label><br />			
			
				<select class="input_wrapper" name="id_trasportadora" style="width:300px;">	
				
				<option value="">selecione...</option>									
				<?php
				
				foreach ($trasportadoras as $rows) { 
				if ($rows['ID_TRANSPORTADORA'] == $tran[0]['ID_TRANSPORTADORA'] && $rows['TIPO'] == $carga[0]['TIPO_TRANSPORTADORA']){					
					$selecionado = " selected=\"selected\" ";
				
				
				} else if($rows['ID_TRANSPORTADORA'] == $tran[0]['ID_TRANSPORTADORA']){
					$selecionado = " selected=\"selected\" ";
				} else {
						$selecionado = "";
				}			
									
				?>
					<option value="<?php echo $rows['ID_TRANSPORTADORA']?>" <?php echo $selecionado ?>><?php echo $rows['NOME_TRANSPORTADORA']?></option>									
				<?php }?>
				</select>
				
			
			<br /><br />

			<label style="line-height:5px; padding-right:20px;">Operação</label>
			<?php 
				if(!empty($carga[0]['DES_OPERACAO']))
				{
					echo($carga[0]['DES_OPERACAO']);				
				}
				?>
				<br /><br />	
			<input type="hidden" name="TIPO_OPERACAO" id="TIPO_OPERACAO" value="<?php echo $carga[0]['TIPO_OPERACAO']?>">
			<label style="line-height:5px; padding-right:20px;">Estado (UF)</label>
			<?php if (!empty($carga[0]['NOME_ESTADO'])) {?>					
				
				<div id="lastest">
				<?php
				;				$options = array ('' => 'Escolha');
				foreach($estados as $estado)				
					$options[$estado->ID] = utf8_encode($estado->NOME);					
					$selected_uf = $carga[0]['ID_UF'];			
				echo form_dropdown('estado', $options, $selected_uf);				
				
				?>
				
				</div>
			<?php } else { 
				$options = array ('' => 'Escolha');
				foreach($estados as $estado)
				$options[$estado->ID] = utf8_encode($estado->NOME);					
					$selected_uf = $carga[0]['ID_UF'];
					
				echo form_dropdown('estado', $options, $selected_uf);
			} ?>			 
			<br />
			
			<label style="width:120px;line-height:5px;">Cidade (Destino)</label>
			
			<?php if (!empty($carga[0]['NOME_CIDADE'])){ //Edição ?>
				<div id="monitor_overlay">			
				<?php 
				
				foreach($cidades as $cidade)				
					$options[$cidade->ID] = utf8_encode($cidade->NOME);					
					$selected_cid = $carga[0]['CIDADE'];			
				echo form_dropdown('cidade', $options, $selected_cid);
				
				?>
				</div>
				<br />
			<?php } else { //
				
				$selected_cid = $carga[0]['CIDADE'];
				echo form_dropdown('cidade', array('' => 'Escolha um Estado'),$selected_cid, 'class="input_wrapper"', 'id="cidade"' );
			} ?>
			<script type="text/javascript">
				var path = '<?php echo site_url(); ?>'
			</script>
			
			
			<label style="line-height:5px;float:left;">Tipo de veículo</label>			
			<?php if (empty($carga[0]['VEICULO'])){
				echo $carga[0]['VEICULO'];
			} else {?>		
				<select name="VEICULO" class="input_wrapper">
				<option name="C_BAU" value="C_BAU" <?php echo @$var = $carga[0]['VEICULO'] == "C_BAU" ? " selected=\"selected\" " : "" ;?>>Carreta baú</option>
				<option name="C_BAU_REB" value="C_BAU_REB" <?php echo @$var = $carga[0]['VEICULO'] == "C_BAU_REB" ? " selected=\"selected\" " : "" ;?>>Carreta baú rebaixada</option>
				<option name="GRANELEIRA" value="GRANELEIRA" <?php echo @$var = $carga[0]['VEICULO'] == "GRANELEIRA" ? " selected=\"selected\" " : "" ;?>>Carreta graneleira</option>
				<option name="SIDER" value="SIDER" <?php echo @$var = $carga[0]['VEICULO'] == "SIDER" ? " selected=\"selected\" " : "" ;?>>Carreta sider</option>				
				<option name="C_TRUCK" value="C_TRUCK" <?php echo @$var = $carga[0]['VEICULO'] == "C_TRUCK" ? " selected=\"selected\" " : "" ;?>>Truck baú</option>
				<option name="CONTAINER_20" value="CONTAINER_20" <?php echo @$var = $carga[0]['VEICULO'] == "CONTAINER_20" ? " selected=\"selected\" " : "" ;?>>Container 20”</option>
				<option name="CONTAINER_40" value="CONTAINER_40" <?php echo @$var = $carga[0]['VEICULO'] == "CONTAINER_40" ? " selected=\"selected\" " : "" ;?>>Container 40”</option>
				<option name="IVECO" value="IVECO" <?php echo @$var = $carga[0]['VEICULO'] == "IVECO" ? " selected=\"selected\" " : "" ;?>>Iveco</option>
				<option name="FURGOVAN" value="FURGOVAN" <?php echo @$var = $carga[0]['VEICULO'] == "FURGOVAN" ? " selected=\"selected\" " : "" ;?>>Furgovan</option>
				<option name="LEVE" value="LEVE" <?php echo @$var = $carga[0]['VEICULO'] == "LEVE" ? " selected=\"selected\" " : "" ;?>>Veiculo leve</option>				
			</select>			
			<?php }?>
			
			<br /><br />
			<label style="line-height:5px; padding-right:20px;">Placa Veículo</label>
			<?php if (empty($carga[0]['PLACA_VEICULO'])){
				echo $carga[0]['PLACA_VEICULO'];
			} else {?>
			<span style="width:120px;" class="input_wrapper"><input class="text" name="PLACA_VEICULO" id="placa_cavalo" type="text" value="<?php echo $var = empty($carga[0]['PLACA_VEICULO']) ? "" : $carga[0]['PLACA_VEICULO'] ;?>"/></span>
			<?php } ?>
						
			<br /><br />
			<label style="line-height:5px; padding-right:20px;">Placa Cavalo</label>
			<?php if (empty($carga[0]['PLACA_CAVALO'])){
				echo $carga[0]['PLACA_CAVALO'];
			} else {?>
			<span style="width:120px;" class="input_wrapper"><input class="text" name="" id="placa_veiculo" type="text" value="<?php echo $var = empty($carga[0]['PLACA_CAVALO']) ? "" : $carga[0]['PLACA_CAVALO'] ;?>"/></span>
			<?php } ?>
						
			<br />
			<label style="line-height:5px; padding-right:20px;">Num. Container</label>
			<?php if (empty($carga/*[0]*/['NUM_CONTAINER'])){
				echo $carga[0]['NUM_CONTAINER'];
			} else {?>
			<span style="width:120px;" class="input_wrapper"><input class="text" name="" type="text" value="<?php echo $var = empty($carga[0]['NUM_CONTAINER']) ? "" : $carga[0]['NUM_CONTAINER'] ;?>"/></span>
			<?php } ?>			
			
			<br />
			<label style="width:130px;line-height:5px;">Hora da Chegada</label>
			<?php if (!empty($carga[0]['HORA_CHEGADA'])){
				echo $carga[0]['HORA_CHEGADA'];
			} else {?>
			<span class="input_wrapper"><input class="text" name="" type="text" value="<?php echo $var = empty($carga[0]['HORA_CHEGADA']) ? "" : $carga[0]['HORA_CHEGADA'] ;?>"/></span>
			<?php }?>
			<br />
			<label style="width:130px;line-height:15px;">Motivo da Recusa (Se Houver)</label>
			<?php if (!empty($carga[0]['OBSERVACAO_RECUSA'])){
				echo (utf8_encode($carga[0]['OBSERVACAO_RECUSA']));

			} ?>
			<br />
			
			<br />
			<label style="width:130px;line-height:15px;">Complemento de Carga</label>
			<?php if (!empty($carga[0]['COMPLEMENTO'])){
				echo ('SIM');
			}
			else echo('N&Atilde;O'); ?>
			<br />
			
			</div>
			<!--- FIM - Informações cadastrada pela Portaria-->
			
			<!---Informações cadastrada pelo setor de logística-->
			<div style="width:350px;float:left;">
				
                                                                
                                                               
                                                                
                                                                <label style="line-height:5px;width:50px;">Cliente</label>
				
				
				<select class="input_wrapper" id="id_cliente" name="ID_CLIENTE" style="width: 250px">
					<option value="">selecione...</option>									
					<?php					
					foreach ($clientes as $rows) { 
					if ($rows['ID_CLIENTE'] == $carga[0]['ID_CLIENTE']){
						$selecionado = " selected=\"selected\" ";
					} else {
						$selecionado = "";
					}
										
					?>
						<option value="<?php echo $rows['ID_CLIENTE']?>" <?php echo $selecionado ?>><?php echo $rows['NOME_CLIENTE']?></option>									
					<?php }?>
				</select>
				
				<br /><br /><br />				
				<label style="line-height:15px;width:150px;">Tipo de Documento</label>
				<select class="input_wrapper" style="width:150px;" name="TIPO_DOCUMENTO">			
					<option value="OV" <?php echo $selecionado = @$carga[0]['TIPO_DOCUMENTO'] == "OV" ? " selected=\"selected\" " : "" ;?>>Ordem de Venda</option>
					<option value="NF" <?php echo $selecionado = @$carga[0]['TIPO_DOCUMENTO'] == "NF" ? " selected=\"selected\" " : "" ;?>>Nota Fiscal</option>							
					
				</select>
				<br /><br />
			
				<label style="line-height:15px;padding-right:20px;">Documentos</label><br/>
				<textarea name="DOCUMENTOS" value="DOCUMENTOS" rows="5" cols="36"><?php echo $var = empty($carga[0]['DOCUMENTOS']) ? "" : $carga[0]['DOCUMENTOS'] ;?></textarea>
				
				<label style="line-height:15px;padding-right:20px;width:300px; font-size: 10px; color: green">Os documentos devem ser separados por ";"<br/>
					Ex: 012556 ; 0213385 ; 654987; ...
				</label><br/>
								
				<br />
				<label style="line-height:5px;width:160px;">Data do Agendamento</label>
				<span style="width:80px;" class="input_wrapper"><input style="width:80px" class="text" name="DATA_SAIDA" id="data_saida" type="text" value="<?php echo $var = empty($carga[0]['DATA_SAIDA']) ? "" : sqlToDate($carga[0]['DATA_SAIDA']);?>" /></span><br /><br />				
				
				<label style="line-height:5px;width:160px;">Senha do Agendamento</label>
				<span style="width:150px;" class="input_wrapper"><input style="width:130px" class="text" name="SENHA_AGENDAMENTO" id="senha_agendamento" type="text" value="<?php echo $var = empty($carga[0]['SENHA_AGENDAMENTO']) ? "" : $carga[0]['SENHA_AGENDAMENTO'] ;?>" /></span><br />			
				
				<script> 
				function ativa_fun(valor_pass){ 
				
					// quando o valor for CONFERENTE 
					if(valor_pass=="CONFERENTE") { 
						document.getElementById('div_conf').style.display='block'; 						
					} 
					
					// quando o valor for MOTORISTA 
					if(valor_pass=="MOTORISTA") { 
						//alert('oi')
						document.getElementById('div_conf').style.display='none'; 
						
					} 				
				} 
				</script>			
				
							
				<label style="line-height:15px;width:100px;">Recebedor</label>
				<select class="input_wrapper" style="width:100px;" name="TIPO_RECEBEDOR" onChange="ativa_fun(this.value)">			
					
					<option value="MOTORISTA" <?php echo $selecionado = @$carga[0]['TIPO_RECEBEDOR'] == "MOTORISTA" ? " selected=\"selected\" " : "" ;?>>Motorista</option>
					<option value="CONFERENTE" <?php echo $selecionado = @$carga[0]['TIPO_RECEBEDOR'] == "CONFERENTE" ? " selected=\"selected\" " : "" ;?>>Conferente </option>										 					
				</select>
				<br /><br />
				<?php $display =  @$carga[0]['TIPO_RECEBEDOR'] == "CONFERENTE" ? "block" : "none" ?>
				<div id="div_conf" style="display:<?php echo $display; ?>"> 
					<label style="line-height:5px;width:160px;">CPF do Recebedor</label>
					<span style="width:150px;" class="input_wrapper"><input style="width:130px" class="text" name="CPF_RECEBEDOR" id="cpf_recebedor" type="text" value="<?php echo $var = empty($carga[0]['CPF_RECEBEDOR']) ? "" : $carga[0]['CPF_RECEBEDOR'] ;?>" /></span><br />
				</div>
				<br />

				<span id="span_complementar" style="display: none;">
			<label style="line-height:15px;width:150px;">Local Complemento</label>
				<select class="input_wrapper" style="width:100px;" id="local_complemento" name="LOCAL_COMPLEMENTO">
					<option value="" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "" ? " selected=\"selected\" " : "" ;?>>Escolha</option>
					<option value="APA_1" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "APA_1" ? " selected=\"selected\" " : "" ;?>>APA 1</option>
					<option value="APA_2" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "APA_2" ? " selected=\"selected\" " : "" ;?>>APA 2</option>
					<option value="APA_3_MB" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "APA_3_MB" ? " selected=\"selected\" " : "" ;?>>APA 3 - MB</option>
					<option value="APA_4_SGF" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "APA_4_SGF" ? " selected=\"selected\" " : "" ;?>>APA 4 - SGF</option>
					<option value="APA_5_SGF" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "APA_5_SGF" ? " selected=\"selected\" " : "" ;?>>APA 5 - SGF</option>
					<option value="APA_CDE" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "APA_CDE" ? " selected=\"selected\" " : "" ;?>>APA CDE</option>
					<option value="BOTIJAO" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "BOTIJAO" ? " selected=\"selected\" " : "" ;?>>BOTIJÃO</option>
					
			   </select>
	</span>
			</div>
			
			<div style="width:350px;float:left;">				
			<label style="line-height:15px;width:150px;">Local carregamento</label>
				
			<select class="input_wrapper" style="width:100px;" id="local_carregamento" name="LOCAL_CARREGAMENTO">
				<option value="" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "" ? " selected=\"selected\" " : "" ;?>>Escolha</option>
				<option value="APA_1" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "APA_1" ? " selected=\"selected\" " : "" ;?>>APA 1</option>
				<option value="APA_2" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "APA_2" ? " selected=\"selected\" " : "" ;?>>APA 2</option>
				<option value="APA_3_MB" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "APA_3_MB" ? " selected=\"selected\" " : "" ;?>>APA 3 - MB</option>
				<option value="APA_4_SGF" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "APA_4_SGF" ? " selected=\"selected\" " : "" ;?>>APA 4 - SGF</option>
				<option value="APA_5_SGF" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "APA_5_SGF" ? " selected=\"selected\" " : "" ;?>>APA 5 - SGF</option>
				<option value="APA_CDE" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "APA_CDE" ? " selected=\"selected\" " : "" ;?>>APA CDE</option>
				<option value="BOTIJAO" <?php echo $selecionado = @$carga[0]['LOCAL_CARREGAMENTO'] == "BOTIJAO" ? " selected=\"selected\" " : "" ;?>>BOTIJÃO</option>
			</select>			
	
	<br /><br />
	<label style="line-height:15px;">Doca</label>
				
	<select  class="input_wrapper" style="width:50px;" id="doca" name="DOCA">
		<option value= "" <?php echo $selecionado = @$carga[0]['DOCA'] == "" ? " selected=\"selected\" " : "" ;?>>Escolha</option>
		<option value="01" <?php echo $selecionado = @$carga[0]['DOCA'] == "01" ? " selected=\"selected\" " : "" ;?>>01</option>
		<option value="02" <?php echo $selecionado = @$carga[0]['DOCA'] == "02" ? " selected=\"selected\" " : "" ;?>>02</option>
		<option value="03" <?php echo $selecionado = @$carga[0]['DOCA'] == "03" ? " selected=\"selected\" " : "" ;?>>03</option>
		<option value="04" <?php echo $selecionado = @$carga[0]['DOCA'] == "04" ? " selected=\"selected\" " : "" ;?>>04</option>
		<option value="05" <?php echo $selecionado = @$carga[0]['DOCA'] == "05" ? " selected=\"selected\" " : "" ;?>>05</option>
		<option value="06" <?php echo $selecionado = @$carga[0]['DOCA'] == "06" ? " selected=\"selected\" " : "" ;?>>06</option>
		<option value="07" <?php echo $selecionado = @$carga[0]['DOCA'] == "07" ? " selected=\"selected\" " : "" ;?>>07</option>
		<option value="08" <?php echo $selecionado = @$carga[0]['DOCA'] == "08" ? " selected=\"selected\" " : "" ;?>>08</option>
		<option value="09" <?php echo $selecionado = @$carga[0]['DOCA'] == "09" ? " selected=\"selected\" " : "" ;?>>09</option>
		<option value="10" <?php echo $selecionado = @$carga[0]['DOCA'] == "10" ? " selected=\"selected\" " : "" ;?>>10</option>
		<option value="11" <?php echo $selecionado = @$carga[0]['DOCA'] == "11" ? " selected=\"selected\" " : "" ;?>>11</option>
		<option value="12" <?php echo $selecionado = @$carga[0]['DOCA'] == "12" ? " selected=\"selected\" " : "" ;?>>12</option>
		<option value="13" <?php echo $selecionado = @$carga[0]['DOCA'] == "13" ? " selected=\"selected\" " : "" ;?>>13</option>
		<option value="14" <?php echo $selecionado = @$carga[0]['DOCA'] == "14" ? " selected=\"selected\" " : "" ;?>>14</option>
		<option value="15" <?php echo $selecionado = @$carga[0]['DOCA'] == "15" ? " selected=\"selected\" " : "" ;?>>15</option>
		<option value="16" <?php echo $selecionado = @$carga[0]['DOCA'] == "16" ? " selected=\"selected\" " : "" ;?>>16</option>
		<option value="17" <?php echo $selecionado = @$carga[0]['DOCA'] == "17" ? " selected=\"selected\" " : "" ;?>>17</option>
		<option value="18" <?php echo $selecionado = @$carga[0]['DOCA'] == "18" ? " selected=\"selected\" " : "" ;?>>18</option>
		<option value="19" <?php echo $selecionado = @$carga[0]['DOCA'] == "19" ? " selected=\"selected\" " : "" ;?>>19</option>
		<option value="20" <?php echo $selecionado = @$carga[0]['DOCA'] == "20" ? " selected=\"selected\" " : "" ;?>>20</option>				
		<option value="APA_2" <?php echo $selecionado = @$carga[0]['DOCA'] == "APA_2" ? " selected=\"selected\" " : "" ;?>>APA_2</option>
		<option value="APA_3_MB" <?php echo $selecionado = @$carga[0]['DOCA'] == "APA_3_MB" ? " selected=\"selected\" " : "" ;?>>APA_3_MB</option>
		<option value="APA_4_SGF" <?php echo $selecionado = @$carga[0]['DOCA'] == "APA_4_SGF" ? " selected=\"selected\" " : "" ;?>>APA_4_SGF</option>
		<option value="APA_5_SGF" <?php echo $selecionado = @$carga[0]['DOCA'] == "APA_5_SGF" ? " selected=\"selected\" " : "" ;?>>APA_5_SGF</option>
		<option value="APA_CDE" <?php echo $selecionado = @$carga[0]['DOCA'] == "APA_CDE" ? " selected=\"selected\" " : "" ;?>>APA CDE</option>
		<option value="BOTIJAO" <?php echo $selecionado = @$carga[0]['DOCA'] == "BOTIJAO" ? " selected=\"selected\" " : "" ;?>>BOTIJAO</option>
	<select>
	
	<br /><br />			
	<label style="line-height:15px;">Conferente</label>
				
	<select  class="input_wrapper" style="width:80px;" id="conferente" name="CONFERENTE">
		<option value="">Selecione...</option>
		<option value="ALFREDO" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "ALFREDO" ? " selected=\"selected\" " : "" ;?>>ALFREDO</option>
		<option value="A_JOSE" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "A_JOSE" ? " selected=\"selected\" " : "" ;?>>ANTONIO JOSE</option>
		<option value="ARIBERTO" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "ARIBERTO" ? " selected=\"selected\" " : "" ;?>>ARIBERTO</option>
		<option value="ARTEVANY" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "ARTEVANY" ? " selected=\"selected\" " : "" ;?>>ARTEVANY</option>
		<option value="BRUNO" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "BRUNO" ? " selected=\"selected\" " : "" ;?>>BRUNO</option>
		<option value="DIONISIO" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "DIONISIO" ? " selected=\"selected\" " : "" ;?>>DIONISIO</option>
		<option value="EDELARDO" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "EDELARDO" ? " selected=\"selected\" " : "" ;?>>EDELARDO</option>
		<option value="ELIZAFAN" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "ELIZAFAN" ? " selected=\"selected\" " : "" ;?>>ELIZAFAN</option>		
		<option value="ERIC" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "ERIC" ? " selected=\"selected\" " : "" ;?>>ERIC</option>
		<option value="ERISON" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "ERISON" ? " selected=\"selected\" " : "" ;?>>ERISON</option>
		<option value="ERNANDES" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "ERNANDES" ? " selected=\"selected\" " : "" ;?>>ERNANDES</option>
		<option value="F_LIMA" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "F_LIMA" ? " selected=\"selected\" " : "" ;?>>FABIO LIMA</option>	
		<option value="FRANCIE" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "FRANCIE" ? " selected=\"selected\" " : "" ;?>>FRANCIE SALES</option>
		<option value="ISMAEL" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "ISMAEL" ? " selected=\"selected\" " : "" ;?>>ISMAEL</option>
		<option value="MAICON" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "MAICON" ? " selected=\"selected\" " : "" ;?>>MAICON</option>
		<option value="ROGERIO" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "ROGERIO" ? " selected=\"selected\" " : "" ;?>>ROGERIO</option>
		<option value="TADEU" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "TADEU" ? " selected=\"selected\" " : "" ;?>>TADEU</option>
		<option value="VALCIR" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "VALCIR" ? " selected=\"selected\" " : "" ;?>>VALCIR</option>
		<option value="WENDEL" <?php echo $selecionado = @$carga[0]['CONFERENTE'] == "WENDEL" ? " selected=\"selected\" " : "" ;?>>WENDEL</option>
</select>
<br /><br />
<label style="line-height:15px;">2º Conferente</label>			

	<select  class="input_wrapper" style="width:80px;" id="conferente_2" name="CONFERENTE_2">
		<option value="">Selecione...</option>
		<option value="ALFREDO" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "ALFREDO" ? " selected=\"selected\" " : "" ;?>>ALFREDO</option>
		<option value="A_JOSE" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "A_JOSE" ? " selected=\"selected\" " : "" ;?>>ANTONIO JOSE</option>
		<option value="ARIBERTO" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "ARIBERTO" ? " selected=\"selected\" " : "" ;?>>ARIBERTO</option>
		<option value="ARTEVANY" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "ARTEVANY" ? " selected=\"selected\" " : "" ;?>>ARTEVANY</option>
		<option value="BRUNO" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "BRUNO" ? " selected=\"selected\" " : "" ;?>>BRUNO</option>
		<option value="DIONISIO" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "DIONISIO" ? " selected=\"selected\" " : "" ;?>>DIONISIO</option>
		<option value="EDELARDO" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "EDELARDO" ? " selected=\"selected\" " : "" ;?>>EDELARDO</option>
		<option value="ELIZAFAN" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "ELIZAFAN" ? " selected=\"selected\" " : "" ;?>>ELIZAFAN</option>		
		<option value="ERIC" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "ERIC" ? " selected=\"selected\" " : "" ;?>>ERIC</option>
		<option value="ERISON" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "ERISON" ? " selected=\"selected\" " : "" ;?>>ERISON</option>
		<option value="ERNANDES" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "ERNANDES" ? " selected=\"selected\" " : "" ;?>>ERNANDES</option>
		<option value="F_LIMA" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "F_LIMA" ? " selected=\"selected\" " : "" ;?>>FABIO LIMA</option>	
		<option value="FRANCIE" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "FRANCIE" ? " selected=\"selected\" " : "" ;?>>FRANCIE SALES</option>
		<option value="ISMAEL" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "ISMAEL" ? " selected=\"selected\" " : "" ;?>>ISMAEL</option>
		<option value="MAICON" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "MAICON" ? " selected=\"selected\" " : "" ;?>>MAICON</option>
		<option value="ROGERIO" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "ROGERIO" ? " selected=\"selected\" " : "" ;?>>ROGERIO</option>
		<option value="TADEU" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "TADEU" ? " selected=\"selected\" " : "" ;?>>TADEU</option>
		<option value="VALCIR" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "VALCIR" ? " selected=\"selected\" " : "" ;?>>VALCIR</option>
		<option value="WENDEL" <?php echo $selecionado = @$carga[0]['CONFERENTE_2'] == "WENDEL" ? " selected=\"selected\" " : "" ;?>>WENDEL</option>
	</select>
	<br /><br />

	<label style="line-height:15px;">3º Conferente</label>	
	<select  class="input_wrapper" style="width:80px;" id="conferente_3" name="CONFERENTE_3">
		<option value="">Selecione...</option>
		<option value="ALFREDO" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "ALFREDO" ? " selected=\"selected\" " : "" ;?>>ALFREDO</option>
		<option value="A_JOSE" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "A_JOSE" ? " selected=\"selected\" " : "" ;?>>ANTONIO JOSE</option>
		<option value="ARIBERTO" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "ARIBERTO" ? " selected=\"selected\" " : "" ;?>>ARIBERTO</option>
		<option value="ARTEVANY" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "ARTEVANY" ? " selected=\"selected\" " : "" ;?>>ARTEVANY</option>
		<option value="BRUNO" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "BRUNO" ? " selected=\"selected\" " : "" ;?>>BRUNO</option>
		<option value="DIONISIO" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "DIONISIO" ? " selected=\"selected\" " : "" ;?>>DIONISIO</option>
		<option value="EDELARDO" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "EDELARDO" ? " selected=\"selected\" " : "" ;?>>EDELARDO</option>
		<option value="ELIZAFAN" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "ELIZAFAN" ? " selected=\"selected\" " : "" ;?>>ELIZAFAN</option>		
		<option value="ERIC" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "ERIC" ? " selected=\"selected\" " : "" ;?>>ERIC</option>
		<option value="ERISON" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "ERISON" ? " selected=\"selected\" " : "" ;?>>ERISON</option>
		<option value="ERNANDES" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "ERNANDES" ? " selected=\"selected\" " : "" ;?>>ERNANDES</option>
		<option value="F_LIMA" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "F_LIMA" ? " selected=\"selected\" " : "" ;?>>FABIO LIMA</option>	
		<option value="FRANCIE" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "FRANCIE" ? " selected=\"selected\" " : "" ;?>>FRANCIE SALES</option>
		<option value="ISMAEL" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "ISMAEL" ? " selected=\"selected\" " : "" ;?>>ISMAEL</option>
		<option value="MAICON" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "MAICON" ? " selected=\"selected\" " : "" ;?>>MAICON</option>
		<option value="ROGERIO" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "ROGERIO" ? " selected=\"selected\" " : "" ;?>>ROGERIO</option>
		<option value="TADEU" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "TADEU" ? " selected=\"selected\" " : "" ;?>>TADEU</option>
		<option value="VALCIR" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "VALCIR" ? " selected=\"selected\" " : "" ;?>>VALCIR</option>
		<option value="WENDEL" <?php echo $selecionado = @$carga[0]['CONFERENTE_3'] == "WENDEL" ? " selected=\"selected\" " : "" ;?>>WENDEL</option>
	</select>
	<br /><br />
											
	<label style="line-height:15px;">Capatazia</label>
	
	<select name="CAPATAZIA" id="capatazia" class="input_wrapper">
	<option name="" value="" <?php echo @$var = $carga[0]['CAPATAZIA'] == "" ? " selected=\"selected\" " : "" ;?>>Escolha</option>
	<option name="PROPRIO" value="ESMALTEC" <?php echo @$var = $carga[0]['CAPATAZIA'] == "ESMALTEC" ? " selected=\"selected\" " : "" ;?>>ESMALTEC</option>
	<option name="SGF" value="SGF" <?php echo @$var = $carga[0]['CAPATAZIA'] == "SGF" ? " selected=\"selected\" " : "" ;?>>SGF</option>
	<option name="PROPRIO" value="PROPRIO" <?php echo @$var = $carga[0]['CAPATAZIA'] == "PROPRIO" ? " selected=\"selected\" " : "" ;?>>O PRÓPRIO</option>
	</select>
				<br /><br />

				<label style="line-height:15px; width:120px">Tamanho veiculo</label><input style="width:100px;" class="text" name="TAMANHO_VEICULO" id="tamanho_veiculo" type="text" value="<?php echo $var = empty($carga[0]['TAMANHO_VEICULO']) ? "" : $carga[0]['TAMANHO_VEICULO'] ;?>" /><br /><br />		
				<label style="line-height:15px;">Observação</label><br/>
				<textarea name="OBSERVACAO" value="observacao" rows="4" cols="40"><?php echo $var = empty($carga[0]['OBSERVACAO']) ? "" : $carga[0]['OBSERVACAO'] ;?></textarea>
				<label style="line-height:15px;">Informações Painel</label><br/>
				<textarea name="INFORMACOES_FISCAIS" value="observacao" rows="2" cols="40"><?php echo $var = empty($carga[0]['INFORMACOES_FISCAIS']) ? "" : $carga[0]['INFORMACOES_FISCAIS'] ;?></textarea>
     			<label style="line-height:10px;">Ação</label>				
				<br/><br />					
				<select name="ACAO" id="acao" class="input_wrapper" onchange="complementa_carga(this.value);">
				<?php if($carga[0]['STATUS'] =='LOG') {?>
							<option name="ENC" value="ENC" <?php echo @$var = $row['ACAO'] == "ENC" ? " selected=\"selected\" " : "" ;?>>Encaminhar para expedição</option>
						<?php } ?>

						<?php if($carga[0]['STATUS'] =='EXP'){?>
							<option name="AUT" value="AUT" <?php echo @$var = $row['ACAO'] == "AUT" ? " selected=\"selected\" " : "" ;?>>Autorizar Entrada</option>
						<?php } ?>

						<?php if(($carga[0]['STATUS'] == 'LIB')){?>
							<option name="CAR" value="CAR" <?php echo @$var = $row['ACAO'] == "CAR" ? " selected=\"selected\" " : "" ;?>>Iniciar Carregamento</option>
							
						<?php } ?>
						
						<?php if($carga[0]['STATUS'] == 'CAR' && $carga[0]['TIPO_OPERACAO'] != 'DSC'){?>
							<option name="ROM" value="ROM" <?php echo @$var = $row['ACAO'] == "ROM" ? " selected=\"selected\" " : "" ;?>>Encaminhar para Romaneio</option>
						<?php } ?>
						<?php if($carga[0]['STATUS'] == 'CAR' && $carga[0]['TIPO_OPERACAO'] == 'DSC'){?>
							<option name="SAI" value="SAI" <?php echo @$var = $row['ACAO'] == "SAI" ? " selected=\"selected\" " : "" ;?>>Enviar para Portaria</option>
						<?php } ?>
												
						<?php if($carga[0]['STATUS'] == 'ROM'){?>
							<option name="FIS" value="FIS" <?php echo @$var = $row['ACAO'] == "FIS" ? " selected=\"selected\" " : "" ;?>>Liberação Fiscal</option>
							<option name="COM" value="COM" <?php echo @$var = $row['ACAO'] == "COM" ? " selected=\"selected\" " : "" ;?>>Complemento de Carga</option>
						<?php } ?>
						<?php if($carga[0]['STATUS'] == 'FIS'){?>
							<option name="SAI" value="SAI" <?php echo @$var = $row['ACAO'] == "SAI" ? " selected=\"selected\" " : "" ;?>>Enviar para Portaria</option>
							
						<?php } ?>
						<?php if($carga[0]['STATUS']!='REC'){?>						
							<option name="ATU" value="ATU" <?php echo @$var = $row['ACAO'] == "ATU" ? " selected=\"selected\" " : "" ;?>>Atualizar Informações</option>
						<?php } ?>
						<?php if($carga[0]['STATUS']=='REC'){?>
						<option name="LOG" value="LOG" <?php echo @$var = $row['ACAO'] == "LOG" ? " selected=\"selected\" " : "" ;?>>Encaminhar para Distribuição</option>						
						<option name="EXP" value="EXP" <?php echo @$var = $row['ACAO'] == "EXP" ? " selected=\"selected\" " : "" ;?>>Encaminhar para Expedição</option>
						<?php } ?>                
										
				</select>
				<br /><br />		
				<div class="table_menu">
					<ul class="left">						
						<li><input id="enc_exp" class="styled-button" style="width: 215px;" type="submit" onclick="return validar()"  value="<< Enviar >>"></li>						
					</ul>
				
		<ul class="right">
			
		</ul>
			</div>
			</div>
			<!---FIM - Informações cadastrada pelo setor de logística-->
		
		</div>
	</fieldset>
	</form>
	</div>
	</div>
</body>
</html>
