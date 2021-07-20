<html>
<head>
<script type="text/javascript" language="javascript" src="<?php echo base_url();?>js/datatable/media/js/jquery.js"></script>
<script>
$(function(){
	$("#enviar").click(function(){	
		$.ajax({ url: "<?php echo site_url('acompFaltas/verificaEmailsPendentes'); ?>",
				data: { PERIODO_FAL: $("#PERIODO_FAL").val()},
				dataType: "html",
				type: "POST",
				success: function(data){
					conf = confirm(' Existe para o periodo que foi selecionado '+data+' funcionarios que ainda não passaram pela entrevista. deseja enviar o email ao gestor mesmo assim?');
					if(conf){
						$("#ema").click();
						$("#corp").html("<img src='<?php echo base_url();?>images/loader.gif'/><br/>Carregando...");
					}
				}
		});
	});
	
	$("#ema").click(function(){
		$.ajax({ url: "<?php echo site_url('acompFaltas/enviarEmail'); ?>",
				data: { PERIODO_FAL: $("#PERIODO_FAL").val()},
				dataType: "html",
				type: "POST",
				success: function(data){
					$("#corp").html(data);
				}
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

	#record_length {
		font-size: 20px;
		font-weight: normal;
		/*line-height: 35px;*/
		margin: 25px 0 0 13px;
		padding: 0;
	}
	
	#record_filter label{
		width: 300px;
		margin-top: 18px;
	}
	
	.title_wrapper_right{
		margin-right: 10px;
		height: 30px;
	}
	.title_wrapper_left{
		margin-left: -5px;
		height: 30px;
	}
	
	#datatables{
		display: none;
	}
	
</style>
</head>
<body>
<div class="tudo">
   <!--inicio da div section-->
   <div class="section" style="width:98.5%; margin-left:0.75%; margin-right:0.75%;">
      <!--inicio da div title_wrapper-->
     <div class="title_wrapper">
         <h2>Enviar Notificação ao Gestor</h2>
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
          <p id="corp">
                    <!--[if !IE]>start dashboard menu<![endif]-->
                     <form id="form" method="post" action="<?php echo site_url('acompFaltas/enviarEmail'); ?>">
                     <input type="hidden" id="ema"/>
  	 <label for="PERIODO_FAL">Periodo</label>
     <?php $datacalc =  explode('/',date('d/m/y'));?>
     <select id="PERIODO_FAL" name="PERIODO_FAL">
        <option value="" selected="selected">.:: Selecione ::.</option>
        <?php for($i=13;$i>=0;$i--){
					$PERIODO_FAL =  mesAno(calculaData("01/".$datacalc[1]."/".$datacalc[2],'-',0,$i,0));
		?>
        <option value="<?php echo $PERIODO_FAL; ?>"><?php echo $PERIODO_FAL; ?></option>
       <?php }?>
     </select>
    
     <input type="button" id="enviar" name="enviar" value="Enviar" />
</form> 
                    <!--[if !IE]>end dashboard menu<![endif]-->
           </p> 
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
</body>
</html>