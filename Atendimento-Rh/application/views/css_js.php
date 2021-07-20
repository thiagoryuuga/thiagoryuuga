<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/css/admin.css"  />

<script type="text/javascript" language="javascript" src="<?php echo base_url();?>js/datatable/media/js/jquery.js"></script>
<script src="<?php echo base_url();?>js/ajax.js"></script>
<script src="<?php echo base_url();?>js/jqueryUI/js/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/index.css">
<link rel="stylesheet" href="<?php echo base_url();?>js/datatable/media/css/demo_table_jui.css">
<link rel="stylesheet" href="<?php echo base_url();?>js/datatable/media/css/datatables.css">
<link rel="stylesheet" href="<?php echo base_url();?>js/jqueryUI/css/ui-lightness/jquery-ui-1.8.16.custom.css">
<link rel="stylesheet" href="<?php echo base_url();?>js/jqueryUI/development-bundle/themes/base/jquery.ui.all.css">


<link rel="stylesheet" href="<?php echo base_url();?>js/jqueryUI/development-bundle/demos/demos.css"> 
<script type="text/javascript" language="javascript" src="<?php echo base_url();?>js/validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>/js/modal/jquery.superbox.css" type="text/css" media="all" />

<script type="text/javascript" src="<?php echo base_url();?>/js/modal/jquery.superbox-min.js"></script>

<script type="text/javascript" language="javascript" src="<?php echo base_url();?>js/datatable/media/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>js/jqueryUI/development-bundle/ui/jquery.ui.core.js"></script> 
<script src="<?php echo base_url();?>js/jqueryUI/development-bundle/ui/jquery.ui.widget.js"></script> 
<script src="<?php echo base_url();?>js/jqueryUI/development-bundle/ui/jquery.ui.datepicker.js"></script> 
<script src="<?php echo base_url();?>js/jqueryUI/development-bundle/ui/jquery.ui.tabs.js"></script> 


<script src="<?php echo base_url();?>js/jquery.maskMoney.js"></script> 
<script src="<?php echo base_url();?>js/jquery.maskedinput-1.2.2.js"></script> 

<script src="<?php echo base_url();?>js/jquery-barcode-2.0.2.min.js"></script>	
<script src="<?php echo base_url();?>js/rotate.js"></script>	

	<script src="<?php echo base_url();?>js/jquery/jquery.treeview/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>js/jquery/jquery.treeview/jquery.treeview.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery/jquery.treeview/demo/demo.js"></script>

<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				
				$.superbox.settings = {
						closeTxt: "Fechar",
						loadTxt: "Carregando...",
						nextTxt: "Next",
						prevTxt: "Previous"
						};
				$.superbox();
				
				
				oTable = $('#record4').dataTable({
					"bJQueryUI": true,
					"bSort": false,
					"sPaginationType": "full_numbers",
					"aLengthMenu": [[50], [50]],
					"iDisplayLength": 50
				});
		
				oTable = $('#record').dataTable({
					"bJQueryUI": true,
					"bSort": false,
					"ordering": false,
					"sPaginationType": "full_numbers",
					"aLengthMenu": [[10], [10]],
					"iDisplayLength": 10
				});
			
				oTable = $('#record1').dataTable({
					"bJQueryUI": true,
					"bSort": true,
					"sPaginationType": "full_numbers",
					"aLengthMenu": [[10], [10]],
					"iDisplayLength": 10
				});
			
				
			} );
</script>

