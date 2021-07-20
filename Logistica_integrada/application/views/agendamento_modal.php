<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="imagetoolbar" content="no" />
<title>Administration Panel</title>


<link media="screen" rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/button.css"  />
<script type="text/javascript" src="<?php echo base_url();?>js/ui/development-bundle/jquery-1.7.2.js"></script>
<script src="<?php echo base_url();?>js/jquery-validation-1.9.0/jquery.validate.js"></script>

<script>	

$(document).ready( function() {
	$("#formJustificativa").validate({
		
		rules:{
			justificativa:{				
				required: true
			}
			
		},
		
		messages:{
			justificativa:{
				required: "Justifique o motivo para devolução",
				minLength: "O seu nome deve conter, no mínimo, 2 caracteres"
			}
			
		}
	});
	
});

		function closeWin()
		{
			window.close();
		}	
		
</script>

<style type="text/css">
	label.error {width:210px;  color: red; margin: 7px 0 0 0; vertical-align: top; font-size: 13px }
</style>

</head>
<body>


  
 
  <form id="formJustificativa" action="<?php echo base_url()?>index.php/agendamento/enviarJustificativaDevolucao/<?php echo $this->uri->segment(3);?>" method="post">
  	
  	<? if(!@$row['STATUS'] == "DEV"){?>
  	<p style="padding: 10px 0 10px 0; color: #1d5987; font-weight: bold" >Justifique o motivo para a devolução</p>
    <? } else {?>
    <p style="padding: 10px 0 10px 0; color: #1d5987; font-weight: bold" >Motivo da Devolução</p>
    <? } ?>
    
    <textarea rows="10" cols="34" style="padding: 5px" id="justificativa" name="justificativa" <?php echo $value = trim((!empty ($row['JUSTIFICATIVA_DEVOLUCAO']))) ? "disabled" : "";?>><?php echo $value = trim((!empty ($row['JUSTIFICATIVA_DEVOLUCAO']))) ? $row['JUSTIFICATIVA_DEVOLUCAO'] : "";?></textarea><br />
    <input type="hidden" name="ID_CARGA" value="<?php echo $this->uri->segment(3);?>" />
    <input type="hidden" name="MAT_PROGRAMADOR" value="<?php echo $this->session->userdata('idUser');?>" />
    <input type="hidden" name="STATUS" value="DEV" />
    
    <? if(!@$row['STATUS'] == "D"){?>
  	<span style="float: right;"><input class="button blue" type="submit" value="Enviar" /></span>
  	<? } ?>
  	
  </form>




</body>
</html>
