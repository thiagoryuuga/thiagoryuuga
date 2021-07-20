<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="imagetoolbar" content="no" />
<title>Administration Panel</title>
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/admin.css"  />
<!--[if lte IE 6]><link media="screen" rel="stylesheet" type="text/css" href="css/admin-ie.css" /><![endif]-->
<link rel="stylesheet" href="<?php echo base_url();?>js/ui/development-bundle/themes/redmond/jquery.ui.all.css">	
<link rel="stylesheet" href="<?php echo base_url();?>js/ui/development-bundle/demos/demos.css">

<link rel="stylesheet" href="<?php echo base_url();?>js/datatables/estilo/table_jui.css" />

<script type="text/javascript" src="<?php echo base_url();?>js/ui/development-bundle/jquery-1.7.2.js"></script>
<!---<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>-->
<script src="<?php echo base_url();?>js/ui/development-bundle/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url();?>js/ui/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url();?>js/ui/development-bundle/ui/jquery.ui.datepicker.js"></script>
<script src="<?php echo base_url();?>js/ui/development-bundle/ui/jquery.ui.tabs.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/cep.js"></script>

<!--- DATATABLES --->

<script type="text/javascript" src="<?php echo base_url();?>js/datatables/js/jquery.dataTables.min.js"></script>

<!--- JQuery UI-->

	
	<script>
	
	$(function() {
		$("#data_inicial").datepicker();
	});
	
	$(function() {
		$( "#data_pesquisa" ).datepicker();
	});
	
	// CONFIGURAÇÃO DO DATEPICKER DO JQUERYUI PARA PT-BR
	$.datepicker.setDefaults({dateFormat: 'dd/mm/yy',
	  dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
	  dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
	  dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
	  monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro', 'Outubro','Novembro','Dezembro'],
	  monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set', 'Out','Nov','Dez'],
	  nextText: 'Próximo',
	  prevText: 'Anterior'
	 });
	</script>


<!------>


<!-- TAB --->
	
	
	<script>
	$(function() {
		$("#tabs").tabs();
	});
	
	$(function() {
		$("#tabs_home").tabs();
	});
	</script>

	
	
<!--- / --->





<script type="text/javascript">

$(document).ready(function() {

	oTable = $('#example').dataTable({

		"bPaginate": true,

		"bJQueryUI": true,

		"sPaginationType": "full_numbers"

	});

});

</script>

</head>

<body>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">

	<thead>

		<tr>

			<th>Rendering engine</th>



			<th>Browser</th>

			<th>Platform(s)</th>

			<th>Engine version</th>

			<th>CSS grade</th>

		</tr>

	</thead>

	<tbody>



		<tr class="odd gradeX">

			<td>Trident</td>

			<td>Internet

				 Explorer 4.0</td>

			<td>Win 95+</td>

			<td class="center"> 4</td>

			<td class="center">X</td>



		</tr>

		<tr class="even gradeC">

			<td>Trident</td>

			<td>Internet

				 Explorer 5.0</td>

			<td>Win 95+</td>

			<td class="center">5</td>

			<td class="center">C</td>



		</tr>

		<tr class="odd gradeA">

			<td>Trident</td>

			<td>Internet

				 Explorer 5.5</td>

			<td>Win 95+</td>

			<td class="center">5.5</td>

			<td class="center">A</td>



		</tr>

		<tr class="even gradeA">

			<td>Trident</td>

			<td>Internet

				 Explorer 6</td>

			<td>Win 98+</td>

			<td class="center">6</td>

			<td class="center">A</td>



		</tr>

		<tr class="gradeA">

			<td>Presto</td>

			<td>Opera 9.5</td>

			<td>Win 88+ / OSX.3+</td>

			<td class="center">-</td>

			<td class="center">A</td>



		</tr>

		<tr class="gradeA">

			<td>Presto</td>

			<td>Opera for Wii</td>

			<td>Wii</td>

			<td class="center">-</td>

			<td class="center">A</td>



		</tr>

		<tr class="gradeA">

			<td>Presto</td>

			<td>Nokia N800</td>

			<td>N800</td>

			<td class="center">-</td>

			<td class="center">A</td>



		</tr>

		<tr class="gradeA">

			<td>Presto</td>

			<td>Nintendo DS browser</td>

			<td>Nintendo DS</td>

			<td class="center">8.5</td>

			<td class="center">C/A<sup>1</sup></td>



		</tr>

		<tr class="gradeC">

			<td>KHTML</td>

			<td>Konqureror 3.1</td>

			<td>KDE 3.1</td>

			<td class="center">3.1</td>

			<td class="center">C</td>



		</tr>

		<tr class="gradeA">

			<td>KHTML</td>

			<td>Konqureror 3.3</td>

			<td>KDE 3.3</td>

			<td class="center">3.3</td>

			<td class="center">A</td>



		</tr>

		<tr class="gradeA">

			<td>KHTML</td>

			<td>Konqureror 3.5</td>

			<td>KDE 3.5</td>

			<td class="center">3.5</td>

			<td class="center">A</td>



		</tr>

		<tr class="gradeX">

			<td>Tasman</td>

			<td>Internet Explorer 4.5</td>

			<td>Mac OS 8-9</td>

			<td class="center">-</td>

			<td class="center">X</td>



		</tr>

		<tr class="gradeC">

			<td>Tasman</td>

			<td>Internet Explorer 5.1</td>

			<td>Mac OS 7.6-9</td>

			<td class="center">1</td>

			<td class="center">C</td>



		</tr>

		<tr class="gradeX">

			<td>Misc</td>

			<td>Links</td>

			<td>Text only</td>

			<td class="center">-</td>

			<td class="center">X</td>



		</tr>

		<tr class="gradeX">

			<td>Misc</td>

			<td>Lynx</td>

			<td>Text only</td>

			<td class="center">-</td>

			<td class="center">X</td>



		</tr>

		<tr class="gradeC">

			<td>Misc</td>

			<td>IE Mobile</td>

			<td>Windows Mobile 6</td>

			<td class="center">-</td>

			<td class="center">C</td>



		</tr>

		<tr class="gradeC">

			<td>Misc</td>

			<td>PSP browser</td>

			<td>PSP</td>

			<td class="center">-</td>

			<td class="center">C</td>



		</tr>

		<tr class="gradeU">

			<td>Other browsers</td>

			<td>All others</td>

			<td>-</td>

			<td class="center">-</td>

			<td class="center">U</td>



		</tr>

	</tbody>

	<tfoot>

		<tr>

			<th>Rendering engine</th>

			<th>Browser</th>

			<th>Platform(s)</th>



			<th>Engine version</th>

			<th>CSS grade</th>

		</tr>

	</tfoot>

</table>

</body>

</html>

