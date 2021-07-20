<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="imagetoolbar" content="no" />
<title>Administration Panel</title>


<link rel="stylesheet" href="<?php echo base_url();?>js/multiselect/css/common.css" type="text/css" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/button.css"  />

<link rel="stylesheet" href="<?php echo base_url();?>js/jquery-ui-1.8.20.custom.css">

<link type="text/css" href="<?php echo base_url();?>js/multiselect/css/ui.multiselect.css" rel="stylesheet" />

<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/js/jquery_1.5.1_jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/js/jqueryui_1.8.10_jquery-ui.min.js"></script>

<script type="text/javascript" language="javascript" src="<?php echo base_url();?>js/datatable/media/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>js/jquery-validation-1.9.0/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/js/plugins/localisation/jquery.localisation-min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/js/plugins/scrollTo/jquery.scrollTo-min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/js/ui.multiselect.js"></script>



<script type="text/javascript">
				
		function closeWin(){
			//window.opener.location.reload(true)
			window.close();
	
		}
				
		function exibe(id) {
			if(document.getElementById(id).style.display=="none") {
				document.getElementById(id).style.display = "block";
			} else {
				document.getElementById(id).style.display = "none";
			}
		}
		
		function liberarCarga(id) {
			if (confirm("Deseja autorizar a saída do veículo ?")) {
				window.location.href="<?php echo base_url()?>index.php/inicio/saida/"+id;		
				
				window.alert('Veículo liberado!')
				window.opener.location.reload(true)
				window.close();
			} else {				
				window.alert('Operação cancelada!')
				window.opener.location.reload(true)
				window.close();
			}
		}
		
		function fnFormatDetails ( oTable, nTr )
		{
				//retorna a tabela de detalhes
				
				var aData = oTable.fnGetData( nTr );
				retorno = getInformation(aData[1]);
				
				return retorno; 
			
				
		}
			
			
			
		function getInformation(codDis){
			
				var retorno = '';
				$.ajax({ url: "<?php echo site_url('agendamento/getItensDistrib'); ?>",
					data: { codDistribuicao: codDis, idCarga : '<?php echo $this->uri->segment(3);?>'},
					dataType: "html",
					type: "POST",
					async: false, 
					success: function(data){
						 alert(codDis)
;						 // retorno = data;
					}
				});
			
				return retorno;
		}
		
		
		
		
		
		$(document).ready(function() {
			 var status ='<?php echo $status_carga; ?>';
			
			 if(status == "EXP" || status == "LIB"){
				$("#devolver").css('display','none');
				$(".devol").css('display','none');
			}
			
			$("#devolver").click(function(){
				$(".checkDist").addClass('required');
				$("#formDevolver").validate();
				$("#formDevolver").submit();
			});
				$(".justificativa").css('display','none');
				$(".dataTables_length").css('display','none');
				$(".checkDist").click(function(){
					if($(".checkDist").is(':checked')){	
						$(".justificativa").css('display','block');
						$("#justificativa").addClass('required');						
					}else{
						$(".justificativa").css('display','none');
					}
				});
			
			$("#inserir").click(function(){
				$("#countries").addClass('required');
				var mult = $("#countries").val();
				if(mult != null){
					$("#erroInsert").css('display','none');
					$("#distrib").validate();
					$("#distrib").submit();
					
				}else{
					$("#erroInsert").css('display','block');
				}
			});
		});
					
			
	
	
</script>
<style>
.justificativa{
	font-family:Verdana, Geneva, sans-serif;
	font-weight:bold;
	padding:5px;
	background:#DFEFFC;
}
.error{
	color:#F00;
}
</style>


</head>
<body>
<?php $this->load->view('js.php');?>	
<div id="wrapper" style="width:700px;"> 
	
	<div style="font-size: 11px; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif">
		<label style="font-weight: bold">Nome do Motorista:</label> <?php echo @$carga[0]['NOME_MOTORISTA'];?><br />
		<label style="font-weight: bold">Nome da Transportadora:</label> <?php echo @$carga[0]['NOME_TRANSPORTADORA'] ;?><br />
		<label style="font-weight: bold">Placa do carro:</label> <?php echo @$carga[0]['PLACA_VEICULO'] ;?><br /><br />
		
		<?php if (@$msg and @$verCargaDistribuicoes and !$this->session->flashdata('flashSucessoLiberado')){ ?>
			
			<div style="<?php echo $color;?>">
				<?php echo $msg;?>
			</div><br />	
			
			<label style="font-weight: bold">Distribuições associadas</label>
		
		<?php } else if(@$verCargaDistribuicoes == "") {?>
			
			<label style="font-weight: bold">Escolhas as Distribuições</label>			
		
		<?php } else if($this->session->flashdata('flashSucessoLiberado')) { ?>
		
			<div style="border: 1px solid green; background: #65b516; color: #ffffff; font-weight:bold; margin-top: 5px; padding: 10px ">				
				<?=$this->session->flashdata('flashSucessoLiberado');?>			
			</div>	
			
		<?php }else if($this->session->flashdata('flashDevolvido')) { ?>
			<div style="border: 1px solid green; background: #DD0; color: #000000; font-weight:bold; margin-top: 5px; padding: 10px ">				
				<?=$this->session->flashdata('flashDevolvido');?>			
			</div>
		<?php  } ?>
				
	</div>	
	
	<?php if (@$msg and @$verCargaDistribuicoes){?>
		 <?php if($this->session->flashdata('flashDevolvido')) { ?>
			<div style="border: 1px solid green; background: #FFFFC1; color: #000000; font-weight:bold;">				
				<?=$this->session->flashdata('flashDevolvido');?>			
			</div>
		<?php  } ?>
        
        <form id="formDevolver" action="<?php echo base_url() . 'index.php/agendamento/distribuicaoDevolver';?>" method="post">		
			<input type="hidden" name="COD_DISTRIBUICAO_EBS" value="<?php echo $verCargaDistribuicoes[0]['COD_DISTRIBUICAO_EBS'];?>">
            <input type="hidden" name="ID_CARGA" value="<?php echo $this->uri->segment(3);?>" />		
		<div style="font-size: 11px; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; padding-top: 10px;">
			
			<table cellpadding="0" cellspacing="" width="100%" border="1"  class="display" id="example" >
															
				<thead>
					<tr>											
						<th style="text-align: left;font-weight: bold"><a href="#">Distribuição EBS</a></th>										            											
						<th class="devol" style="text-align: center;font-weight: bold"><a href="#">Devolver</a></th>																	
					</tr>
				</thead>
				<tbody>
					<?php foreach (@$verCargaDistribuicoes as $row) {?>	
					<tr class="first">		
						<td style="text-align: left; font-weight:bold;"><?php echo $row['COD_DISTRIBUICAO_EBS']; ?></td>						                        								
						<td class="devol" style="text-align: center;"><input class="checkDist" type="checkbox" name="DISTRIBUICAO[]" value="<?php echo $row['COD_DISTRIBUICAO_EBS'];?>" ></td>						                        						
					</tr>
					<?php } ?>
				</tbody>
				
			</table>
            <br />
                <div class="justificativa">
                    <fieldset style="border:1px solid; padding:10px;">
                    	<legend>Justificativa</legend>
                    	<textarea id="justificativa" name="JUSTIFICATIVA" cols="125" rows="5"></textarea>
                    </fieldset>
                </div>
			<br />
           
             <input type="button" class="button orange" id="devolver" style="font-size: 12px; float: left; margin-right:15px;" value=" Devolver " >

             <input type="button" class="button red" style="font-size: 12px;" value=" Fechar " onclick="closeWin()">
			<?php if(@$dev and !$this->session->flashdata('flashSucessoLiberado')){?>				
				<!--<a href="#"  class="button green" style="font-size: 12px;" name="liberar" onclick="liberarCarga(<?php echo $row['ID_CARGA']?>)"> Liberar </a>-->
				
				<input type="submit" class="button green" style="font-size: 12px;" name="liberar" value=" Liberar  ">
								
				<input type="button" class="button orange" style="font-size: 12px; float: right" value=" Devolver " onclick="exibe('conteudo')">
				
				<?if($this->session->flashdata('flashErroDev')):?>
					<div style="border: 1px solid red; background: #f37373; color: #ffffff; font-weight:bold; margin-top: 5px; padding: 10px ">				
						<?=$this->session->flashdata('flashErroDev');?>			
					</div>	
				<?endif?>
				
				<div id="conteudo" style="border:1px solid #b4d8ea; margin-top: 5px; padding: 5px ;display: none;">
				   	<p style="padding: 10px 0 10px 0; color: #1d5987; font-weight: bold" >Justifique o motivo para a devolução</p>
				   				   	
				   	<textarea rows="5" cols="84" style="padding: 5px" id="justificativa" name="justificativa" <?php echo $value = trim((!empty ($row['JUSTIFICATIVA_DEVOLUCAO']))) ? "disabled" : "";?>><?php echo $value = trim((!empty ($row['JUSTIFICATIVA_DEVOLUCAO']))) ? $row['JUSTIFICATIVA_DEVOLUCAO'] : "";?></textarea><br /><br />
				   	
				   	<input type="hidden" name="COD_DISTRIBUICAO_EBS" value="<?php echo $row['COD_DISTRIBUICAO_EBS']; ?>">
				    <input type="hidden" name="ID_CARGA" value="<?php echo $this->uri->segment(3);?>" />
				    <input type="hidden" name="MAT_PROGRAMADOR" value="<?php echo $this->session->userdata('idUser');?>" />
				    <input type="hidden" name="STATUS" value="DEV" />
    				<input type="submit" class="button blue" style="font-size: 12px;" name="devolver" value=" Enviar  ">
    				
				</div>
							
			<?php }?>
		</div>			
		</form>
					
	<?php } else { ?>

	<div id="content">
	    
		<?php if (@$distribuicoes){?><br/>
		<script>
			$(function(){
				$.localise('ui-multiselect', {/*language: 'en',*/ path: 'js/locale/'});
				$(".multiselect").multiselect();
				$('#switcher').themeswitcher();
				
				
				$("#distrib").validate();
			});
			
		</script>
        
        <?php if($this->session->flashdata('flashDevolvido')) { ?>
			<div style="border: 1px solid green; background: #FFFFC1; color: #000000; font-weight:bold;">				
				<?=$this->session->flashdata('flashDevolvido');?>			
			</div>
		<?php  } ?>
        
		    <form id="distrib" action="<?php echo base_url()?>index.php/agendamento/distribuicaoCarga/" method="post">
		      <input type="hidden" name="id_carga" name="id_carga" value="<?php echo $id_carga;?>"/>
              <div id="erroInsert" style="border: 1px solid green; background:#F00; color:#FFF; font-weight:bold; display:none;">				
				Escolha a Distribução.			
			</div><br/>
              
              <select id="countries" class="multiselect" multiple="multiple" name="distribuicoes[]">
		        
		        <?php foreach ($distribuicoes as $rowDistribuicao) {?>
		        <option value="<?php echo $rowDistribuicao['COD_DISTRIBUICAO_EBS'].'-'.$rowDistribuicao['COD_AGENDAMENTO']; ?>"><?php echo $rowDistribuicao['COD_CARRETA']?></option>		        
		        <?php } ?>
		      
		      </select>
		      <br/>
		     
		      <input class="button blue" type="button" id="inserir" style="font-size: 12px;float: right" value="Inserir" />
		    </form>
		    
            
		    <div id="switcher"></div>
		<?php } else { ?>   
			<br/><div style="background: #e7f3f8; padding: 10px;color: #45545f; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif">
				Nenhum distribuição disponível!
			</div>
			<br /><input type="button" class="button red" style="font-size: 12px;" value=" Fechar " onclick="closeWin()">
		<?php } ?> 
		  	 
	</div> 	

<?php }?>

</div>
</body> 
</html>
