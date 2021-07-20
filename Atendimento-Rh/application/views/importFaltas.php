
<script charset="utf-8">
	$(function(){
	$("#Limpar").click(function(){
		window.location.href = '<?php echo base_url();?>index.php/importFaltas';
	});
	
	$("#record_length").html("Tabela");
	
	$("#importForm").validate();
	
	if($.browser.msie == true){
		$("#form").css("height",120+'px');
	}
	
	var msn = '<?php if (isset($msn)) echo $msn;?>';
		if(msn == 'msn5'){
			alert('Importação realizada com sucesso.');
			window.location.href = '<?php echo base_url();?>'+'/index.php/importFaltas';
		}else if(msn == 'msn6'){
			alert('Ocorreu um erro na importação, tente novamente.');
			window.location.href = '<?php echo base_url();?>'+'/index.php/importFaltas';
		}	
	$('.readonly').attr('readonly','readonly');
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
	
	.readonly{
		background: #f5f5f5;
	}

	#record_length {
		font-size: 20px;
		font-weight: normal;
		line-height: 35px;
		margin: 0 0 0 13px;;
		padding: 0;
	}
	
	#antlink, #link{
		background: url('<?php echo base_url();?>images/entrar-btn.png') no-repeat;
		line-height:20px;
		padding: 3px 13.5px 3px 13.5px;
		color: #FFF;
		font-family:Arial;
		font-size: 12px;
		font-weight: bold;
		width: 94px;		
	}
</style>
<?php $url = base_url(); ?>

<div class="tudo">
   <!--inicio da div section-->
   <div class="section" style="width:98.5%; margin-left:0.75%; margin-right:0.75%;">
      <!--inicio da div title_wrapper-->
     <div class="title_wrapper">
         <h2>Formulario -  Importar Faltas</h2>
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
                    <!--[if !IE]>start dashboard menu<![endif]-->
                      <form  enctype="multipart/form-data" action="<?php echo base_url() . 'index.php/importFaltas/importar'; ?>" method="post" id="importForm" accept-charset="UTF-8">
                      <?php $exist = false; if(isset($funcionario) && $funcionario != null){ $exist= true;}?>
                      <div style="float:none; position:relative; height:120px;" id="form">
                      <div id="colum_left" style="float:left; width:48%;" align="right">
                      	<div style="float:left; width:30%;" align="right">
							<p>
                            	<label>Periodo:</label>
                        	</p>
                            <p>
                            	<label>Arquivo:</label>
                        	</p>
                        </div>
                        <div style="float:right; width:69.5%;" align="left">
							 <p>
                               <select id="PERIODO" name="PERIODO">
                               		<?php for($i=13;$i>=0;$i--){?>
                                 	<option><?php echo mesAno(calculaData(date('d/m/y'),'-',0,$i,0));?></option>
                                    <?php }?>
                               </select>
                             </p>
                             <p>
                        		<input type="file" name="arquivo" id="arquivo" class="required"/>
                       		 </p>
                             <p>
                                <br/>
                                <button class="normal" type="submit" id="Cadastrar"  name="Cadastrar" value="Cadastrar" />Cadastrar</button>
                                <button type="button" id="Limpar"  name="Limpar" value="Limpar" />Limpar</button>
                        	</p>
                        </div>
                      </div>
                      </div>
					</form>
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
</div>
