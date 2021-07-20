<?php

	$session_idUsuario = $this->session->userdata('idUser');
	$session_name_user = $this->session->userdata('Name');
	$session_permissao = $this->session->userdata('idLevel');
	//checkLogin($session_name_user);	


?>
<!doctype html><head>
 <meta http-equiv="X-UA-Compatible" content="IE=Edge">
 <meta http-equiv="refresh" content="600; url=http://intranet.esmaltec.com.br/sistemas/logistica/index.php/logout" />
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">-->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="imagetoolbar" content="no" />

<title>Painel Administrativo</title>

<link media="screen" rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/admin.css"  />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/button.css"  />
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

<link rel="stylesheet" href="<?php echo base_url();?>js/modal/jquery.superbox.css" type="text/css" media="all" />

<script type="text/javascript" src="<?php echo base_url();?>js/modal/jquery.superbox-min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/collaptable/jquery.aCollapTable.min.js"></script>

<script type="text/javascript">
function sizer(tipo) {
	var tipo = tipo;
	var table;
	
	if($('#logistica').length > 0)
	{
		table = 'logistica';	
	}
	if($('#formPortaria').length > 0)
	{
		table = 'formPortaria';		
	}
	if($('#clientes_wrapper').length > 0)
	{
		table = 'clientes_wrapper';		
	}
	if($('#logistica_produtos').length > 0)
	{
		table = 'logistica_produtos';		
	}
	if($('#expedicao').length > 0)
	{
		table = 'expedicao';		
	}
	if($('#logistica_import').length > 0)
	{
		table = 'logistica_import';
	}
	
	
		var largura = parseFloat($('#'+table).width());
		if (tipo =='big')
		{
			var pctg = "0.08";
		}
		if(tipo =='small')
		{
			var pctg = '1';
		}
		var total = Math.ceil(largura+(largura * pctg));	
		
		$('#body').css('width',(total) +'px');
		
		$('.sct').css('width', (total) +'px');
		$('.sct').css('padding-right',-15+'px');
		$('.table_section').css('width', (total)+'px');
		$('#content').css('width',(total)+'px');   
}




</script>





<script type="text/javascript" src="<?php echo base_url();?>js/datatables/js/jquery.dataTables.min.js"></script>

<!--- JQuery UI-->
	<script type="text/javascript">
	
	$(function() {
		$('#data_saida').mask('99/99/9999');
		$('#data_atual').mask('99/99/9999');
		$('#data_pesquisa1').mask('99/99/9999');
		$('#data_pesquisa2').mask('99/99/9999');
		$('#hora_chegada').mask('99:99');
		$('#hora_entrada').mask('99:99');
		$('#placa_veiculo').mask('AAA 9999');
		$('#placa_carreta').mask('AAA 9999');
		$('#placa_cavalo').mask('AAA 9999');
		$('.data_lead_time').mask('99/99/9999')
		// outras opções de máscara
		$('.date_time').mask('99/99/9999 99:99:99');
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
  
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
		$( "#dialog-message" ).dialog({
			modal: true,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
	$(function() {
		$("#tabs").tabs();
	});
	
	
	
	$(function() {
		$( "#tabs_ajax_home" ).tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Erro ao carregar guias, por favor entrar em contato com o desenvolvedor. " );
				}
			}
		});
	});
	
	$(function() {
		$( "#tabs_ajax_portaria" ).tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Erro ao carregar guias, por favor entrar em contato com o desenvolvedor. " );
				}
			}
		});
	});

	$(function() {
		$( "#tabs_ajax_carretas" ).tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Erro ao carregar guias, por favor entrar em contato com o desenvolvedor. " );
				}
			}
		});
	});

		
	$(function() {
		$( "#tabs_ajax_logistica" ).tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Erro ao carregar guias, por favor entrar em contato com o desenvolvedor. "
						 );
				}
			}
		});
	});
	
	



<!-- DataTables -->

$(document).ready(function() {

	oTable = $('#autorizado').dataTable({
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});
});

$(document).ready(function() {

	oTable = $('#logistica_produtos').dataTable({
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});
});

$(document).ready(function() {

	oTable = $('#carregando_expedicao').dataTable({
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});
});

$(document).ready(function() {

	oTable = $('#libfis').dataTable({
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});
});

$(document).ready(function() {
	oTable = $('#recusadas').dataTable({
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});
});

$(document).ready(function() {
	oTable = $('#distribuicoes_geradas').dataTable({
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});
});

$(document).ready(function() {

	oTable = $('#pendentes').dataTable({
		"sDom": 'T<"clear">lfrtip',		
		"oTableTools": {
			"sSwfPath": "<?php echo base_url();?>js/datatables/TableTools-2.1.2/media/swf/copy_cvs_xls_pdf.swf"
		},		
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers"
	});
});

$(document).ready(function() {
	oTable = $('#distribuidas').dataTable({
		"sDom": 'T<"clear">lfrtip',		
		"oTableTools": {
			"sSwfPath": "<?php echo base_url();?>js/datatables/TableTools-2.1.2/media/swf/copy_cvs_xls_pdf.swf"
		},		
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers"
	});
});


$(document).ready(function() {
	oTable = $('#logistica').dataTable({		
		"aaSorting":[],
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});

});

$(document).ready(function() {
	oTable = $('#cadastro_carretas').dataTable({
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100		
	});
});

$(document).ready(function() {
	oTable = $('#expedicao').dataTable({
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100		
	});
});

$(document).ready(function() {

	oTable = $('#liberadas').dataTable({		
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});
});


$(document).ready(function() {
	oTable = $('#romaneio').dataTable({		
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});
});

$(document).ready(function() {

	oTable = $('#auditoriaExp').dataTable({		
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});
});

$(document).ready(function() {

	oTable = $('#veiculos_cadastrados').dataTable({		
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});
});


$(document).ready(function() {

	oTable = $('#clientes').dataTable({		
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers"
	});
});

$(document).ready(function() {
	oTable = $('#placas_chegada').dataTable({		
		"aaSorting":[],
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});

});

$(document).ready(function() {
	oTable = $('#placas_entrada').dataTable({		
		"aaSorting":[],
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});

});

$(document).ready(function() {
	oTable = $('#placas_docas').dataTable({		
		"aaSorting":[],
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});

});

$(document).ready(function() {
	oTable = $('#placas_saida').dataTable({		
		"aaSorting":[],
		"bPaginate": true,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",		
		"iDisplayLength": 100
	});

});

$(document).ready(function() {
 	 $("#table_wrapper_inner").load();
   
   $.ajaxSetup({ cache: false });
});



	
$(function() {
        $( "#dialog-message" ).dialog({
            modal: true,
            buttons: {
                Ok: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    });	

</script>

<script>
	function resetFlashData()
	{
		setTimeout(function() {
			<?php unset($this->session->flashdata);?>
			$('.system_messages').css('display','none');

		}, 2000);
	}

	window.onload = resetFlashData;
</script>



<style type="text/css">  
	  
	.ui-widget-header{background: #B4D8EA; border: 1px solid #B4D8EA; color:#2E6E9E}	
	.ui-widget-content{border: 0}
	.dashboard_menu{ border:0}
	.section {margin:0 15px 30px 15px}

	#veiculos_cadastrados_filter{
		width: 10%;
	} 
</style>


</head>

<body id="body" style="overflow-x:hidden;">





	<!--[if !IE]>start wrapper<![endif]-->
	<div id="wrapper">
    
    	<!--[if !IE]>start head<![endif]-->
		<div id="head">
        
			<!--[if !IE]>start logo and user details<![endif]-->
			<div id="logo_user_details" > 
            
            	<h1 id="logo"><a href="#">Administração</a></h1>
                
                <!--[if !IE]>start user details<![endif]-->
				<div id="user_details">  
                
                    <ul id="user_details_menu">
                        <li>Bem-vindo <strong><?php echo $session_name_user;?></strong></li>
                        
						
						<li>
                            <ul id="user_access">
                                <!--<li class="first"><a href="#">My account</a></li>--->
                                <li class="last"><a href="<?php echo base_url()?>index.php/logout">Sair [x]</a></li>
                            </ul>
                        </li>
                        <!--<li><a class="new_messages" href="#">4 new messages</a></li>-->						
                    </ul>     
                                            
					<div id="server_details">
						<dl>
							<dt>Hora :</dt>
							<dd><?php echo $data_sistema = date('H:i');?></dd>
						</dl>
						<dl>
							<dt>Data :</dt>
							<dd><?php echo $hora_sistema = date('d/m/Y');?></dd>
						</dl>
					</div>             
                </div>   
                <!--[if !IE]>end user details<![endif]-->
                
            </div> 
            <!--[if !IE]>end logo and user details<![endif]-->
            
			<!--[if !IE]>start menus_wrapper<![endif]-->
			
            <!--[if !IE]>end menus_wrapper<![endif]-->
            
        </div>
        <!--[if !IE]>end head<![endif]-->
        
        
		<!--[if !IE]>start content<![endif]-->
		<div id="content" style="left:15px">        
        
	<div class="sct" style="border:0; padding-top:-10px;">
		<!--[if !IE]>start dashboard menu<![endif]-->
		<ul class="dashboard_menu" style="left:15px">
		<?php if($session_permissao == "P"||$session_permissao == "G" || $session_permissao == "L" || $session_permissao == "E"){?>
          	<li><a href="<?php echo base_url()?>index.php/inicio#page" class="d1 animate rand"><span>Portaria</span></a></li>
			<li><a href="<?php echo base_url()?>index.php/cadastrar_carreta#page" class="d14 animate rand"><span>Cadastro de Veiculos</span></a></li>
        <?php } ?>
		<?php if($session_permissao == "G" || $session_permissao == "L" || $session_permissao == "E" ){?>
			<li><a href="<?php echo base_url()?>index.php/logistica#page" class="d2 animate rand"><span>Distribuição</span></a></li>
			<li><a href="<?php echo base_url()?>index.php/expedicao#page" class="d3 animate rand"><span>Expedição</span></a></li>
			<li><a href="<?php echo base_url()?>index.php/cliente#page" class="d4 animate rand"><span>Clientes</span></a></li>
			<!--<li><a href="<?php echo base_url()?>index.php/painel#page" class="d15 animate rand"><span>Painel</span></a></li>-->
			<!--<li><a href="<?php echo base_url()?>index.php/agendamento#page" class="d10 animate rand"><span>Agendamento</span></a></li>-->
        <?php }?>
        </ul>
		<!--[if !IE]>end dashboard menu<![endif]-->
											
		
				