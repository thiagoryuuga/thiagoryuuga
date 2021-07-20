<?php if($PortariaOk) {?>
<div id="dialog-message" title="Sucesso!">    
    <p>
       <b>Cadastro realizado com sucesso!</b>.
    </p>
</div>
<?php } ?>
<script type="text/javascript">

$(document).ready( function() {
	$("#formPortaria").validate({
		// Define as regras
		rules:{			
			ID_TRANSPORTADORA:{
				// campoNome será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true
			},
			CPF_MOTORISTA1: {
				required: true
			},
			estado:{
				// campoNome será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true
			},
			cidade:{
				// campoNome será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true
			},
			VEICULO:{
				// campoNome será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true
			},
			TELEFONE_CONTATO:{
				required: true
			},
			TIPO_OPERACAO:{
				// TIPO_OPERACAO será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true
			},
			PLACA_VEICULO:{
				// campoNome será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true
			}
			
		},
		// Define as mensagens de erro para cada regra
		messages:{			
			ID_TRANSPORTADORA:{
				required: "Campo Obrigatório",				
			},
			CPF_MOTORISTA1: {
				required: "Campo Obrigatório",
			},
			estado:{
				required: "Campo Obrigatório",				
			},
			cidade:{
				required: "Campo Obrigatório",				
			},
			VEICULO:{
				required: "Campo Obrigatório",				
			},
			TIPO_OPERACAO:{
				required: "Campo Obrigatório",				
			},
			TELEFONE_CONTATO:{
				required: "Campo Obrigatório",				
			},
			PLACA_VEICULO:{
				required: "Campo Obrigatório",				
			}			
		}
	});
	
});


$(document).ready(function() {
	$(function() {
		$("#id_transportadora").autocomplete({
			source: function(request, response) {
				$.ajax({ url: "<?php echo site_url('inicio/transportadoras'); ?>",
				data: { term: $("#id_transportadora").val()},
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

$(document).ready(function() {
	$(function() {
		$("#placa_veiculo").autocomplete({
			source: function(request, response) {
				$.ajax({ url: "<?php echo site_url('cadastrar_carreta/busca_carreta'); ?>",
				data: { term: $("#placa_veiculo").val()},
				dataType: "html",
				type: "POST",
				success: function(data){
					tamanho = $('#placa_veiculo').val().length;
					console.log($('#placa_veiculo').val().length);
					
					
					if(data.length < 1 && tamanho == 8)
					{
						$("#sender").css('display','none');
						$('#carreta_nao_encontrada').css('display','');
					}
					else{
						var informacoes = (data.split(';'));
						$("#placa_veiculo").val(informacoes[0]);
						$("#placa_cavalo").val(informacoes[1]);
						$('select[name="VEICULO"]').find('option[value="'+informacoes[2]+'"]').attr("selected",true);
						$("#sender").css('display','');
						$('#carreta_nao_encontrada').css('display','none');
					}
				}
			});
		},
		minLength: 7
		});
	});
});
$(document).ready(function() {
	$(function() {
		$("#placa_cavalo").autocomplete({
			source: function(request, response) {
				$.ajax({ url: "<?php echo site_url('cadastrar_carreta/busca_cavalo'); ?>",
				data: { cavalo: $("#placa_cavalo").val(),carreta: $("#placa_veiculo").val()},
				dataType: "json",
				type: "POST",
				success: function(data){
					tamanho = $('#placa_cavalo').val().length;
					console.log($('#placa_cavalo').val().length);
					response(data);
					if(data.length < 1 && tamanho == 8 )
					{
						$("#sender").css('display','none');
						$('#cavalo_nao_encontrado').css('display','');
					}
					else{
						$("#sender").css('display','');
						$('#cavalo_nao_encontrado').css('display','none');
					}
				}
			});
		},
		minLength: 3
		});
	});
});
	
	
$(document).ready(function() {
	$(function() {
		$("#cpf_motorista1").autocomplete({
			source: function(request, response) {
				$.ajax({ url: "<?php echo site_url('inicio/motorista'); ?>",
				data: { term: $("#cpf_motorista1").val()},
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




	// Cadastrar Novo Motorista!
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var nome_motorista = $( "#nome_motorista" ),
			cpf_motorista = $( "#cpf_motorista" ),
			data_cadastro = $("#data_cadastro"),
			//password = $( "#password" ),
			allFields = $( [] ).add( nome_motorista ).add( cpf_motorista ).add( data_cadastro ),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}
		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			buttons: {
				"Cadastrar": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkLength( nome_motorista, "nome_motorista", 3, 99 );
					bValid = bValid && checkLength( cpf_motorista, "cpf_motorista", 6, 80 );
					//bValid = bValid && checkLength( password, "password", 5, 16 );

					bValid = bValid && checkRegexp( nome_motorista, /^[a-zA-Z][a-zA-Z][a-zA-Z]*[a-zA-Z ]*$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
					bValid = bValid && checkRegexp( cpf_motorista, /^([0-9]){3}([0-9]){3}([0-9]){3}([0-9]){2}$/i, "eg. ui@jquery.com" );
					//bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
					
					//alert("CPF "+cpf_motorista.val()+" - Nome "+nome_motorista.val()+" - Data "+data_cadastro.val());
					
					<!-- Persisti informações no banco --->
					
					if ( bValid ) {
						$.ajax({ 
							url: "<?php echo site_url('inicio/inserir_motorista'); ?>",    
							data: { cpf: $("#cpf_motorista").val(), nome: $("#nome_motorista").val(), d: $("#data_cadastro").val()},
							dataType: "html",
							type: "POST",
							success: function(login){ 
								$("#cod_recebedor").val(login);
								alert('Cadastro realizado com sucesso!');
							}
						});				
					}		
					<!-- -->
					
					if ( bValid ) {
					    $('#cpf_motorista1').val(cpf_motorista.val()+" - "+nome_motorista.val());
						$('#cpf_motorista1').attr("readonly","readonly");
					
						$( this ).dialog( "close" );
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#create-user" )
			.button()
			.click(function() {
				$( "#dialog-form" ).dialog( "open" );
			});
	});	
</script>

<script>
	function getDuplicidade(val){
			$.ajax({ url: "<?php echo site_url('inicio/duplicidades'); ?>",
			data: { placa_veiculo: val},
			dataType: "html",
			type: "POST",
			success: function(data){
				if(data == 'blocked')
				{
					$("#error_message").css('display','');
					$("#sender").css('display','none');
				}
				else
				{
					$("#error_message").css('display','none');
					$("#sender").css('display','');
				}
			}
			
		});
	}
</script>
<script>
function getTipoVeiculo()
{
	$.ajax({ url: "<?php echo site_url('cadastrar_carreta/busca_tipo'); ?>",
			data: { placa_cavalo: $("#placa_cavalo").val()},
			dataType: "html",
			type: "POST",
			success: function(data){
				$('select[name="VEICULO"]').find('option[value="'+data+'"]').attr("selected",true);
			}
			
		});
}
</script>

<style type="text/css">
            
	label { display: block; margin-top: 10px; }
	label.error { width:100px;  color: red; margin: 7px 10px; vertical-align: middle; font-size: 10px }
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

	.ui-widget-header{background: #E7F3F8; border: 1px solid #E7F3F8; color:#2E6E9E}
	.ui-widget-content{border: 0}
	
	<!-- Modal para cadastro --->
	body { font-size: 62.5%; }
	label, input { display:block; }
	
	fieldset { padding:0; border:0; margin-top:25px; }
	h1 { font-size: 1.2em; margin: .6em 0; }
	div#users-contain { width: 350px; margin: 20px 0; }
	div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
	div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
	.ui-dialog .ui-state-error { padding: .3em; }
	.validateTips { border: 1px solid red; padding: 0.3em; }
	
</style>


<div id="dialog-form" title="Cadastrar motorista">
	<!--<p class="validateTips">All form fields are required.</p>-->

	<!--<form action="<?php echo base_url();?>index.php/inicio/inserir_motorista" method="post">-->
	<form>
	<fieldset>
		<label for="nome_motorista">Nome</label>
		<input type="text" name="nome_motorista" id="nome_motorista" class="text ui-widget-content ui-corner-all" /><br /><br />
		<label for="cpf_motorista">CPF</label>
		<input type="text" name="cpf_motorista" id="cpf_motorista" value="" class="text ui-widget-content ui-corner-all" />
		<input type="hidden" name="novo" id="novo" value="true" class="text ui-widget-content ui-corner-all" />		
		<input type="hidden" name="data_cadastro" id="data_cadastro" value="<?php echo $horario = date("d/m/Y H:i:s");?>" class="text ui-widget-content ui-corner-all" />
		
	</fieldset>
	</form>
</div>
	<form id="formPortaria" style="color:#6B98A8" class="search_form general_form" action="<?php echo base_url();?>index.php/inicio/entrada" method="post">
	<?php if($this->session->flashdata('flashSucesso')){ ?>
		<ul class="system_messages">
			<li class="green"><span class="ico"></span><strong class="system_title"><?php echo $this->session->flashdata('flashSucesso');?></strong></li>
		</ul>
	<?php } ?>
	
	<?php if($this->session->flashdata('flashCancelado')){?>															
		<ul class="system_messages">														
			<li class="yellow"><span class="ico"></span><strong class="system_title"></strong><?php echo $this->session->flashdata('flashCancelado');?></li>
		</ul>													
	<?php } ?>	
			<label style="width:180px;">Transportadora</label>
			<input class="text" type="text" name="ID_TRANSPORTADORA"  id="id_transportadora" style="width:450px" value="<?php echo $var = empty($row['ID_TRANSPORTADORA']) ? "" : $row['ID_TRANSPORTADORA'] ;?>"/>	
			<br /><br />
	
	<label style="width:180px;">CPF do motorista</label>
	<input class="text" type="text" name="CPF_MOTORISTA1"  id="cpf_motorista1" style="width:350px" value=""/>
			<?php if(form_error('CPF_MOTORISTA1')){?>															
				<?php echo $msgErro = form_error('CPF_MOTORISTA1', '<p class="red" style="padding:5px;width:530px;color:red;" >', ' [<a href="#" id="create-user">Clique aqui para cadastrar</a>]</p>')?>
			<?php } else {?>
				[Não cadastrado, <a href="#" id="create-user">Clique aqui!</a>]<br /><br />
			<?php } ?>
	<input type="hidden" name="cod_recebedor" id="cod_recebedor" value="" class="text ui-widget-content ui-corner-all" />
			<label style="width:180px;">Telefone para contato</label>
			<input class="text" type="text" name="TELEFONE_CONTATO"  id="telefone_contato" style="width:200px" value="<?php echo $var = empty($row['TELEFONE_CONTATO']) ? "" : $row['TELEFONE_CONTATO'] ;?>"/>																												
			<br /><br />
	<label style="width:180px;">Tipo de Operação</label>
		
	<select name="TIPO_OPERACAO" id="TIPO_OPERACAO" class="input_wrapper">
		<option name="" value="" <?php echo @$var = $row['TIPO_OPERACAO'] == "" ? " selected=\"selected\" " : "" ;?>>Escolha</option>
		<option name="BOT" value="FOB" <?php echo @$var = $row['TIPO_OPERACAO'] == "BOT" ? " selected=\"selected\" " : "" ;?>>Botijão</option>
		<option name="C_EXP" value="C_EXP" <?php echo @$var = $row['TIPO_OPERACAO'] == "C_EXP" ? " selected=\"selected\" " : "" ;?>>Carga Expressa</option>
		<option name="CIF" value="CIF" <?php echo @$var = $row['TIPO_OPERACAO'] == "CIF" ? " selected=\"selected\" " : "" ;?>>CIF Cliente</option>
		<option name="DEV" value="DEV" <?php echo @$var = $row['TIPO_OPERACAO'] == "DEV" ? " selected=\"selected\" " : "" ;?>>Devolução</option>
		<option name="FOB" value="FOB" <?php echo @$var = $row['TIPO_OPERACAO'] == "FOB" ? " selected=\"selected\" " : "" ;?>>FOB Cliente</option>
		<option name="FRA" value="FRA" <?php echo @$var = $row['TIPO_OPERACAO'] == "FRA" ? " selected=\"selected\" " : "" ;?>>Fracionado</option>
		<option name="TRA" value="TRA" <?php echo @$var = $row['TIPO_OPERACAO'] == "TRA" ? " selected=\"selected\" " : "" ;?>>Transferência</option>
	</select>
		<br /><br />
		<label>Estado (UF)</label>
		<?php
			$options = array ('' => 'Escolha');
			foreach($estados as $estado)
				$options[$estado->ID] = $estado->NOME;
					echo utf8_encode(form_dropdown('estado', $options, '', 'style="margin-left:80px;" class="input_wrapper"'));
		?>
		<br /><br />
		<label style="width:180px;">Cidade (Destino)</label>
			<?php echo form_dropdown('cidade', array('' => 'Escolha um Estado'),'', 'style="margin-left:8px; font-color: #000000;" class="input_wrapper"', 'id="cidade"' ) ?>
				<script type="text/javascript">
					var path = '<?php echo site_url(); ?>';
				</script>
		<br /><br />
		<label style="width:190px;">Tipo do veículo</label>
			<select name="VEICULO" class="input_wrapper">
				<option name="C_BAU" value="C_BAU" <?php echo @$var = $row['VEICULO'] == "C_BAU" ? " selected=\"selected\" " : "" ;?>>Carreta baú</option>
				<option name="C_BAU_REB" value="C_BAU_REB" <?php echo @$var = $row['VEICULO'] == "C_BAU_REB" ? " selected=\"selected\" " : "" ;?>>Carreta baú rebaixada</option>
				<option name="GRANELEIRA" value="GRANELEIRA" <?php echo @$var = $row['VEICULO'] == "GRANELEIRA" ? " selected=\"selected\" " : "" ;?>>Carreta graneleira</option>
				<option name="SIDER" value="SIDER" <?php echo @$var = $row['VEICULO'] == "SIDER" ? " selected=\"selected\" " : "" ;?>>Carreta sider</option>																
				<option name="C_TRUCK" value="C_TRUCK" <?php echo @$var = $row['VEICULO'] == "C_TRUCK" ? " selected=\"selected\" " : "" ;?>>Truck baú</option>
				<option name="CONTAINER_20" value="CONTAINER_20" <?php echo @$var = $row['VEICULO'] == "CONTAINER_20" ? " selected=\"selected\" " : "" ;?>>Container 20”</option>
				<option name="CONTAINER_40" value="CONTAINER_40" <?php echo @$var = $row['VEICULO'] == "CONTAINER_40" ? " selected=\"selected\" " : "" ;?>>Container 40”</option>
				<option name="IVECO" value="IVECO" <?php echo @$var = $row['VEICULO'] == "IVECO" ? " selected=\"selected\" " : "" ;?>>Iveco</option>
				<option name="FURGOVAN" value="FURGOVAN" <?php echo @$var = $row['VEICULO'] == "FURGOVAN" ? " selected=\"selected\" " : "" ;?>>Furgovan</option>
				<option name="LEVE" value="LEVE" <?php echo @$var = $row['VEICULO'] == "LEVE" ? " selected=\"selected\" " : "" ;?>>Veiculo leve</option>																
			</select>														
		<br /><br />
		<ul class="system_messages" id="error_message" style="display:none";>														
			<li class="red"><span class="ico"></span><strong class="system_title"></strong>Existem operações em aberto para este veículo. Para cadastra-lo novamente, favor encerrar todas as pendências</li>
		</ul>
		<ul class="system_messages" id="carreta_nao_encontrada" style="display:none";>														
			<li class="red"><span class="ico"></span><strong class="system_title"></strong>Carreta não cadastrada! Favor adicionar os dados na Aba "Cadastro de Carretas"</li>
		</ul>
		<ul class="system_messages" id="cavalo_nao_encontrado" style="display:none";>														
			<li class="red"><span class="ico"></span><strong class="system_title"></strong>Veículo não cadastrado! Favor adicionar os dados na Aba "Cadastro de Carretas"</li>
		</ul>															
		<label>Placa carreta</label><input style="margin-left:80px;width:100px" class="text" name="PLACA_VEICULO" id="placa_veiculo" type="text" onblur="getDuplicidade(this.value);" value="<?php echo $var = empty($row['PLACA_VEICULO']) ? "" : $row['PLACA_VEICULO'] ;?>" /><br /><br />
		
		<label>Placa do cavalo</label><input style="margin-left:80px;width:100px" class="text" name="PLACA_CAVALO" id="placa_cavalo" onblur="getTipoVeiculo();" type="text" value="<?php echo $var = empty($row['PLACA_CAVALO']) ? "" : $row['PLACA_CAVALO'] ;?>" /><br /><br />
		
		<label>Num. container</label><input style="margin-left:80px;" class="text" name="NUM_CONTAINER" id="num_container" type="text" value="<?php echo $var = empty($row['NUM_CONTAINER']) ? "" : $row['NUM_CONTAINER'] ;?>"/><br /><br />
		<input type="submit" class="styled-button" id="sender" style="width: 150px;" value="Cadastar Caminhão" />
</form>