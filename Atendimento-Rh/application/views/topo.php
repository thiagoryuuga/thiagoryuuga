<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<style>
body
{
	margin:0;
	padding:0;
}
</style>
<?php $url = base_url();?>
<script type="text/javascript" language="javascript" src="<?php echo base_url();?>js/datatable/media/js/jquery.js"></script>


<script>
$(function(){
	$("#enviar_email").click(function(){
		$("#email" ).dialog();
	});
	/*
	$.superbox.settings = {
			closeTxt: "Fechar",
			loadTxt: "Carregando...",
			nextTxt: "Next",
			prevTxt: "Previous"
			};
	$.superbox();
	*/
	/*$("#notification").dialog({
		resizable: false,
		height:200,
		modal: true,
		buttons: {
			"OK": function() {
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	}); */
});

function enviar(PERIODO_FAL){
	$.ajax({ url: "<?php echo site_url('acompFaltas/enviarEmail'); ?>",
		data: { PERIODO: PERIODO_FAL},
		dataType: "html",
		type: "POST",
		success: function(data){
			alert(data);
			$("#email").html(data);
		}
	});
}
</script>

<title>atendimento</title>

<link href="<?php echo $url ?>css/inicio.css" rel="stylesheet" type="text/css" />

</head>

<body>
	<!--inicio da div conteudo-->
    <div class="conteudo">
        <!--inicio da div head-->
        <div id="head">
           <!--inicio da div logo-->
           <div class="logo">
           <!--fin da div logo-->
           </div>
    	   
           <!--inicio da div user_details-->
     	   <div style="margin-top:-115px;" id="user_details">  
                <ul id="user_details_menu">
                    <li>Ol&aacute;! <strong><?php $name =  explode(' ',$this->session->userdata('Name')); echo $name[0].' '.$name[1]; ?>.</strong></li>
                </ul>     
                <!--inicio da div server_details-->                            
				<div id="server_details" style="font-size:12px; color:#E8E8E8;">
						<dl >
							<dt>Server time :</dt>
							<dd><?php echo date("H:i");?></dd>
						</dl>
						<dl>
							<dt>Login ip :</dt>
							<dd><?php echo $_SERVER['REMOTE_ADDR'];?></dd>
						</dl>
                        <dl>
							<dt>Sessão:</dt>
							<dd><span id="counter"></span></dd>
						</dl>
				 <!--fim da div server_details-->
                </div>                                             
                    
				<!--inicio da div search_wrapper-->
				             
            <!--fim da div user_details-->
            </div> 
            <!--fim da div head-->                  
         </div> 
         
		<!--inicio da div menus_wrapper-->
	
            
        </div>
        <!--[if !IE]>end head<![endif]-->
      <!--inicio da div content-->
       <div id="content"> 
        <!--inicio da div page--> 
        <div id="page">   
          <!--inicio da div inner-->     
          <div class="inner">
           <!--inicio da div section-->
            <div class="section">
                        
                             <!--inicio da div title_wrapper-->
                            <div class="title_wrapper">
                                <h2>Dashboard</h2>
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
                                                <div class="sct_right">
                                                    <!--[if !IE]>start dashboard menu<![endif]-->
                                                    <ul id="menu" class="dashboard_menu">
                                                       <?php if($this->session->userdata('idLevel') == 0 || $this->session->userdata('idLevel') == 1 || $this->session->userdata('idLevel') == 2 || $this->session->userdata('idLevel') == 5){?>
                                                       <li>
                                                       <a href="<?php echo site_url('tipo');?>"><img src="<?php echo base_url();?>images/icons-menu/tipo_atend.png"/></a></li>
                                                       <li><a href="<?php echo site_url('atendimento');?>"><img src="<?php echo base_url();?>images/icons-menu/atendimento.png"/></a></li>	
                                                       <?php } ?>
                                                       </li>
                                                       <?php if($this->session->userdata('idLevel') == 0 || $this->session->userdata('idLevel') == 2){?>
                                                      
                                                        <li><a href="<?php echo site_url('visita');?>"><img src="<?php echo base_url();?>images/icons-menu/visita.png"/></a></li>	
                                                        <li><a href="<?php echo site_url('acidente');?>"><img src="<?php echo base_url();?>images/icons-menu/acidente.png"/></a></li>
                                                       <?php } ?>
                                                       
                                                       <?php if($this->session->userdata('idLevel') == 0 || $this->session->userdata('idLevel') == 3 || $this->session->userdata('idLevel') == 4){?>
                                                       <li><a href="<?php echo site_url('importFaltas');?>"><img src="<?php echo base_url();?>images/icons-menu/import_faltas.png"/></a></li>
                                                       <?php } ?>
                                                        <?php if($this->session->userdata('idLevel') == 0 || $this->session->userdata('idLevel') == 3 || $this->session->userdata('idLevel') == 4 || $this->session->userdata('idLevel') == 5){?>
                                                        <li><a href="<?php echo site_url('acompFaltas');?>"><img src="<?php echo base_url();?>images/icons-menu/faltas.png"/></a></li>
                                                        <?php } ?>
                                                        <?php if($this->session->userdata('idLevel') == 0 || $this->session->userdata('idLevel') == 4){?>
                                                        <li><a href="<?php echo site_url('acompFaltas/email');?>" rel="superbox[iframe][350x150]"><img src="<?php echo base_url();?>images/icons-menu/email.png"/></a></li>
                                                        <?php } ?>
                                                        <li><a href="<?php echo site_url('atestado');?>"><img src="<?php echo base_url();?>images/icons-menu/atestado_medico.png"/></a></li>
                                                        <li><a href="<?php echo site_url('relatorios');?>"><img src="<?php echo base_url();?>images/icons-menu/relatorios.png"/></a></li>
                                                       <li><a href="<?php echo site_url('login/destroy');?>"><img src="<?php echo base_url();?>images/icons-menu/sair.png"/></a></li>	
                                                    </ul>
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
                     <!--fim da div inner-->    
       			</div>
               <!--fim da div page-->
            </div>
            <!--fim da div content-->
        </div>
	<!--fim da div conteudo-->
    </div>
    <script>

	var countdownfrom=3000; 
	var currentsecond=document.getElementById("counter").innerHTML=countdownfrom+1;


	function countMyTime()
	{ 
		if (currentsecond!=1)
		{ 
			currentsecond-=1; 
			document.getElementById("counter").innerHTML=currentsecond + "s ";
		} 
		else{ 
			currentsecond=3000;
			
			window.location.href='<?php echo site_url('login/destroy');?>';
		} 
	setTimeout("countMyTime()",1000) 
	} 

	countMyTime();

</script>