<script charset="utf-8">
$(function() {
		var dates = $( "#DAT_INI, #DAT_FIM" ).datepicker({
			defaultDate: "+0w",
			changeMonth: true,
			numberOfMonths: 1,
			onSelect: function( selectedDate ) {
				var option = this.id == "DAT_INI" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
		});
	});
	
$(function() {
		var dates = $( "#dini, #dfim" ).datepicker({
			defaultDate: "+0w",
			changeMonth: true,
			numberOfMonths: 1,
			onSelect: function( selectedDate ) {
				var option = this.id == "dini" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
		});
	});

$(function(){
	
	$("#Limpar").click(function(){
		$("#DAT_INI").val("");
		$("#DAT_FIM").val("");
		$("#dini").val("");
		$("#dfim").val("");
		$("#num_matricula").val("");
	});
	
	$("#record_length").html("Tabela");
	
	$("#relat_atend").validate();
	$("#relat_absent").validate();
	$("#relat_faltas").validate();
	
	if($("#COD_TIPO_ATENDIMENTO").val() != ""){
		$("#Cadastrar").val("Alterar");
		$("#Cadastrar").html("Alterar");
	}
	
	if($.browser.msie == true){
		$(".form").css("height",90+'px');
	}
	
	var msn = '<?php  if(isset($msn)) echo $msn;?>';
	if(msn == 'msn1'){
		alert('Cadastro realizado com sucesso.');
	}else if(msn == 'msn2'){
		alert('Alteração realizado com sucesso.');
	}
	
	$("#section_atendimento").css('display','none');
	$("#section_absenteismo").css('display','none');
	$("#section_falta").css('display','none');
	$("#section_grafico").css('display','none');
	
	$("#atendimento").click(function(){
		$("#section_atendimento").css('display','inline');
		$("#section_atendimento").show();
		$("#section_absenteismo").css('display','none');
		$("#section_absenteismo").hide();
		$("#section_falta").css('display','none');
		$("#section_falta").hide();
		$("#section_grafico").css('display','none');
		$("#section_grafico").hide();
	});
	
	$("#absent").click(function(){
		$("#section_atendimento").css('display','none');
		$("#section_atendimento").hide();	
		$("#section_absenteismo").css('display','inline');
		$("#section_absenteismo").show();
		$("#section_falta").css('display','none');
		$("#section_falta").hide();
		$("#section_grafico").css('display','none');
		$("#section_grafico").hide();
		
	});
	
	$("#falta").click(function(){
		$("#section_atendimento").css('display','none');
		$("#section_atendimento").hide();	
		$("#section_absenteismo").css('display','none');
		$("#section_absenteismo").hide();
		$("#section_falta").css('display','inline');
		$("#section_falta").show();
		$("#section_grafico").css('display','none');
		$("#section_grafico").hide();
		
	});
	
	
	$("#grafico").click(function(){
		$("#section_atendimento").css('display','none');
		$("#section_atendimento").hide();	
		$("#section_absenteismo").css('display','none');
		$("#section_absenteismo").hide();
		$("#section_falta").css('display','none');
		$("#section_falta").hide();
		$("#section_grafico").css('display','inline');
		$("#section_grafico").show();
		
	});

});
</script>
<style>
body
{
	margin:0;
	padding:0;
}
p{
	margin:5px;
}
#record_length
{
	font-size: 20px;
	font-weight: normal;
	line-height: 35px;
	margin: 0 0 0 13px;;
	padding: 0;
	
}
</style>
<?php $url = base_url();?>

<div class="tudo">
   <!--inicio da div section-->
   <div class="section" style="width:98.5; margin-left:0.75%; margin-right:0.75%;">
      <!--inicio da div title_wrapper-->
     <div class="title_wrapper">
         <h2>Relatórios</h2>
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
                    <ul id="menu" class="dashboard_menu" style="margin-left:50px;">
                        <?php if($this->session->userdata('idLevel') == 0 || $this->session->userdata('idLevel') == 1 ){?>
                        <li id="atendimento" style="cursor:pointer;"><img src="<?php echo base_url();?>images/icons-menu/atend_relat.png"/></li>
                        <?php } ?>
                        <?php if($this->session->userdata('idLevel') == 0 || $this->session->userdata('idLevel') == 4 || $this->session->userdata('idLevel') == 2){?>
                        <li id="absent" style="cursor:pointer;"><img src="<?php echo base_url();?>images/icons-menu/absent_relat.png"/></li>
                        <li id="falta" style="cursor:pointer;"><img src="<?php echo base_url();?>images/icons-menu/faltas_relat.png"/></li>
                        <?php } ?>
                        <?php if($this->session->userdata('idLevel') == 0 || $this->session->userdata('idLevel') == 3){?>
                        <li id="grafico" style="cursor:pointer;"><img src="<?php echo base_url();?>images/icons-menu/grafico.png"/></li>
                        <?php } ?>
                    	</li>
                    </ul>           
                    </div>
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
        <br/>
      <!--inicio da div title_wrapper-->
     <div id="section_atendimento">
     <div class="title_wrapper">
         <h2>Filtros</h2>
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
                    <div id="relat" class="sct_right" align="center" style="height:120px;">
                    <!--[if !IE]>start dashboard menu<![endif]-->
                     <div style="float:none; margin-left:50px;" class="form" align="left">                                 
                         <form action="<?php echo base_url().'index.php/relatorios/atendimento';?>" method="post" id="relat_atend">  
                               <label for="DAT_INI">Data Inicio: </label>
                               <input type="text" name="DAT_INI" id="DAT_INI" size="10" class="required" value=""/>
                               <label for="DAT_FIM">Data Fim: </label>                               
                               <input type="text" name="DAT_FIM" id="DAT_FIM" size="10" class="required" value="" /><br/><br/>
                               <button type="submit" id="Gerar_Excel"  name="Gerar_Excel" value="Gerar Excel"> Gerar&nbsp;Excel </button>
    				           <button type="button" id="Limpar"  name="Limpar" value="Limpar"> Limpar </button>
                         </form>
                     </div>
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
     </div>
     <!--fim da div section--> 
     
     
     <div id="section_falta">
     <div class="title_wrapper">
         <h2>Filtros</h2>
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
                    <div id="relat" class="sct_right" align="center" style="height:180px;">
                    <!--[if !IE]>start dashboard menu<![endif]-->
                     <div style="float:none; margin-left:50px;" id="form" align="left">                                 
                         <div style="float:none; position:relative; height:170px;" class="form">
                            <div id="colum_left" style="float:left; width:48%;" align="right">
                                     
                                 <form action="<?php echo base_url().'index.php/relatorios/faltas';?>" method="post" id="relat_faltas">   
                                 <div style="float:left; width:30%;" align="right">
                                    <p>
                                       <label>Tipo:</label>
                                    </p>
                              		<br/>
                                    <p>
                                       <label>Competência:</label>
                                    </p>
                                    <br/>
                                    <p>
                                       <label>Gerência:</label>
                                    </p>
                                     <br/>
                                 </div>
                                 <div style="float:right; width:69.5%;" align="left">
                                   <p>
                                      <label>
                                          <input class="required" type="radio" name="tipo" value="A" id="tipo_0" />
                                          Anual</label>
                                        <label>
                                          <input class="required" type="radio" name="tipo" value="P" id="tipo_1" />
                                          Competência</label>
                                       
                                    </p>
                                    <br/>
                                    <p>
                                    <?php $datacalc =  explode('/',date('d/m/y'));?>
                                        <select id="PERIODO" name="PERIODO" class="required">
                                          <?php for($i=13;$i>=0;$i--){?>
                                          <option><?php echo mesAno(calculaData("01/".$datacalc[1]."/".$datacalc[2],'-',0,$i,0));?></option>
                                          <?php }?>
                                       </select>
                                    </p>
                                    <br/>
                                    <p>
                                    	<select id="gerencia" name="gerencia">
                                        	<?php if(isset($gerencia)){
												foreach($gerencia as $ger){	
											?>
                                            <option value="<?php echo $ger['DEN_ESTR_LINPROD'];?>"><?php if($ger['DEN_ESTR_LINPROD'] == ""){echo "TODOS";} else {echo $ger['DEN_ESTR_LINPROD'];}?></option>
                                            <?php }}?>
                                        </select>
                                    </p>
                                    <p>
                                      <button type="submit" id="enviar"  name="enviar" value="enviar"> Enviar </button>
    				           		  <button type="button" id="Limpar"  name="Limpar" value="Limpar"> Limpar </button>
                                    </p>
                                  </div>
                                  </form>
                            </div>
                        </div>
                     </div>
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
     </div>
     <!--fim da div section--> 
     
     
     
      <div id="section_absenteismo">
     <div class="title_wrapper">
         <h2>Filtros</h2>
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
                    <div id="relat" class="sct_right" align="center" style="height:150px;">
                    <!--[if !IE]>start dashboard menu<![endif]-->
                     <div style="float:none; margin-left:50px;" id="form" align="left">                                 
                         <div style="float:none; position:relative; height:120px;" class="form">
                            <div id="colum_left" style="float:left; width:48%;" align="right">
                                 <form action="<?php echo base_url().'index.php/relatorios/absenteismo';?>" method="post" id="relat_absent">   
                                 <div style="float:left; width:30%;" align="right">
                                    <p>
                                       <label>Matricula:</label>
                                    </p>
                                    <br/>
                                    <p>
                                       <label>Competência:</label>
                                    </p>
                                 </div>
                                 <div style="float:right; width:69.5%;" align="left">
                                    <p>
                                        <input type="text" name="num_matricula" id="num_matricula" />
                                    </p>
                                    <br/>
                                    <p>
                                        <?php $datacalc =  explode('/',date('d/m/y'));?>
                                        <select id="PERIODO" name="PERIODO" class="required">
                                          <?php for($i=13;$i>=0;$i--){?>
                                          <option><?php echo mesAno(calculaData("01/".$datacalc[1]."/".$datacalc[2],'-',0,$i,0));?></option>
                                          <?php }?>
                                       </select>
                                    </p>
                                    <br/>
                                    <p>
                                      <button type="submit" id="enviar"  name="enviar" value="enviar"> Enviar </button>
    				           		  <button type="button" id="Limpar"  name="Limpar" value="Limpar"> Limpar </button>
                                    </p>
                                  </div>
                                  </form>
                            </div>
                        </div>
                     </div>
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
     </div>
     <!--fim da div section--> 
     
     
     
       
      <div id="section_grafico">
     <div class="title_wrapper">
         <h2>Filtros</h2>
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
                    <div id="relat" class="sct_right" align="center" style="height:150px;">
                    <!--[if !IE]>start dashboard menu<![endif]-->
                     <div style="float:none; margin-left:50px;" id="form" align="left">                                 
                         <div style="float:none; position:relative; height:120px;" class="form">
                            <div id="colum_left" style="float:left; width:48%;" align="right">
                                 <form action="<?php echo base_url().'index.php/relatorios/gerarGrafico';?>" method="post" id="relat_grafico">   
                                 <div style="float:left; width:30%;" align="right">
                                    <p>
                                       <label>Competência:</label>
                                    </p>
                                 </div>
                                 <div style="float:right; width:69.5%;" align="left">
                                    <p>
                                       <?php $datacalc =  explode('/',date('d/m/y'));?>
                                        <select id="PERIODO" name="PERIODO" class="required">
                                          <?php for($i=13;$i>=0;$i--){?>
                                          <option><?php echo mesAno(calculaData("01/".$datacalc[1]."/".$datacalc[2],'-',0,$i,0));?></option>
                                          <?php }?>
                                       </select>
                                    </p>
                                    <br/>
                                    <p>
                                      <button type="submit" id="enviar"  name="enviar" value="enviar"> Enviar </button>
    				           		  <button type="button" id="Limpar"  name="Limpar" value="Limpar"> Limpar </button>
                                    </p>
                                  </div>
                                  </form>
                            </div>
                        </div>
                     </div>
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
     </div>
     <!--fim da div section--> 
     
     
   </div>
  </div>
</div>


