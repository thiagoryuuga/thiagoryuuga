<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<link href="<?php echo base_url();?>css/inicio.css" rel="stylesheet" type="text/css" />
<script charset="utf-8">
	$(function(){
	$("#Limpar").click(function(){
		$("#COD_TIPO_ATENDIMENTO").val("");
		$("#DEN_TIPO_ATENDIMENTO").val("");
		$("#STATUS_TIPO_ATENDIMENTO").val("A");
		$("#Cadastrar").val("Cadastrar");
		$("#Cadastrar").html("Cadastrar");
	});
	
	$("#record_length").html("Tabela");
	
	$("#funcForm").validate();
	
	if($("#COD_TIPO_ATENDIMENTO").val() != ""){
		$("#Cadastrar").val("Alterar");
		$("#Cadastrar").html("Alterar");
	}
	
	if($.browser.msie == true){
		$("#form").css("height",100+'px');
		$('.title_wrapper_left').css('margin-right', 0+'px');
		$('.title_wrapper_left').css('height', 20+'px');
	}else
	{
		$('.title_wrapper_left').css('margin-left', 0+'px');
		$('.title_wrapper_left').css('height', 35+'px');
		$('.title_wrapper_right').css('margin-right', '-15px');
	}
	
	var msn = '<?php if (isset($msn)) echo $msn;?>
		';
		if(msn == 'msn1'){
		alert('Cadastro realizado com sucesso.');
		}else if(msn == 'msn2'){
		alert('Alteração realizado com sucesso.');
		}
		
	var func = '<?php if(isset($funcionario)) echo true;?>';
	if(func == true){
		$("#datatables").css('display','block');
	}
	
	
});
		
function editar(matricula)
{
	
	parent.location="<?php echo base_url().'index.php/'.$tela.'/funcionario/';?>"+matricula+"/"+$("#periodo").val();
}		
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
	.title_wrapper_left{
		margin-left: -5px;
		height: 30px;
	}
	
	.title_wrapper_right{
		margin-right: 10px;
		height: 30px;
	}
	
	#datatables{
		display: none;
	}
	
</style>
<?php $url = base_url(); ?>
</head>

<body>

<div class="tudo">
   <!--inicio da div section-->
   <div class="section" style="width:98.5%; margin-left:0.75%; margin-right:0.75%;">
      <!--inicio da div title_wrapper-->
     <div class="title_wrapper">
         <h2>Formulario</h2>
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
                      <form action="<?php echo base_url() . 'index.php/funcionario/pesquisar'; ?>" method="post" id="funcForm" accept-charset="UTF-8">
                      <input type="hidden" id="tela" name="tela" value="<?php if(isset($tela)){ echo $tela;} else{$get = $this->uri->uri_to_assoc(); if(isset($get['tela'])) echo $get['tela'];}?>">
                      <input type="hidden" id="periodo" name="periodo" value="<?php if(isset($periodo)){ echo $periodo;} else{$get = $this->uri->uri_to_assoc(); if(isset($get['periodo'])) echo $get['periodo'];}?>">
                      <div style="float:none; position:relative; height:180px;" id="form">
                      <div id="colum_left" style="width:100%;" align="right">
                      	<div style="float:left; width:30%;" align="right">
							<p><label for="NUM_MATRICULA">Matricula: </label></p>
							<p><label for="NOME">Nome: </label></p>
		                </div>
                        <div style="float:right; width:69.5%;" align="left">
							<p><input type="text" name="NUM_MATRICULA" id="NUM_MATRICULA" size="10"/></p>
							<p><input type="text" name="NOME" id="NOME" size="50"/></p>
							<button type="submit" id="Pesquisar"  name="Pesquisar" value="Pesquisar" />Pesquisar</button>
                           
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
   <div id="datatables" style="width:98.5%; margin-left:0.75%;">
      <table cellpadding="0" cellspacing="0" border="0" class="display" id="record">
        <thead>
          <tr>
             <th>Matricula</th>
             <th>Nome</th>
             <th>Setor</th>
             <th>Cargo</th>
             <th>A&ccedil;&otilde;es</th>
          </tr>
        </thead>
        <tbody>
         <?php 
         		if(isset($funcionario)):
         		foreach($funcionario as $row):?>
          <tr>
            <td><?php echo $row['NUM_MATRICULA']; ?></td>
            <td align="center"><?php echo $row['NOM_COMPLETO']; ?></td>
            <td><?php echo utf8_encode($row['DEN_UNI_FUNCIO']); ?></td>
            <td align="center"><?php echo $row['DEN_CARGO']; ?></td>
            <td align="left" width="50px"><a href="#" onclick="editar(<?php echo utf8_decode($row['NUM_MATRICULA']); ?>);"><img style="border:0px;" src="<?php echo base_url(); ?>images/tables/action2.gif"></a></td>
          </tr>
         <?php 
         	endforeach;
			endif; ?>
        </tbody>
      </table>  
   </div> 
</div>
</body>
</html>