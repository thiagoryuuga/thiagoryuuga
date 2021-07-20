
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
	
	$("#tipoForm").validate();
	
	if($("#COD_TIPO_ATENDIMENTO").val() != ""){
		$("#Cadastrar").val("Alterar");
		$("#Cadastrar").html("Alterar");
	}
	
	if($.browser.msie == true){
		$("#form").css("height",50+'px');
	}
	
	var msn = '<?php  if(isset($msn)) echo $msn;?>';
	if(msn == 'msn1'){
		alert('Cadastro realizado com sucesso.');
	}else if(msn == 'msn2'){
		alert('Alteração realizado com sucesso.');
	}
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
                      <form action="<?php echo base_url().'index.php/tipo/manter';?>" method="post" id="tipoForm">
                      <div style="float:none; position:relative; height:100px;" id="form">
                      <div id="colum_left" style="float:left; width:15%;" align="right">
                      	<p><label for="DEN_TIPO_ATENDIMENTO">Tipo de Atendimento: </label></p>
                        <p><label for="STATUS_TIPO_ATENDIMENTO">Status: </label></p>
                      </div>
                      <div id="colum_right" style="float:right; width:84.5%;" align="left">
                        <input type="hidden" name="COD_TIPO_ATENDIMENTO" id="COD_TIPO_ATENDIMENTO" value="<?php if(isset($tip)) echo $tip[0]['COD_TIPO_ATENDIMENTO'];?>"/>
                        <p><input type="text" name="DEN_TIPO_ATENDIMENTO" id="DEN_TIPO_ATENDIMENTO" size="50" class="required" value="<?php if(isset($tip)) echo utf8_encode($tip[0]['DEN_TIPO_ATENDIMENTO']);?>"></p>
                       	<p><select name="STATUS_TIPO_ATENDIMENTO" id="STATUS_TIPO_ATENDIMENTO">
                        	<option value="A" <?php if(isset($tip)) if($tip[0]['STATUS_TIPO_ATENDIMENTO'] == "ATIVO") echo "selected='selected'";?>>ATIVO</option>
                            <option value="I" <?php if(isset($tip)) if($tip[0]['STATUS_TIPO_ATENDIMENTO'] == "INATIVO") echo "selected='selected'";?>>INATIVO</option>
                        </select></p>
						<button type="submit" id="Cadastrar"  name="Cadastrar" value="Cadastrar" />Cadastrar</button>
                        <button type="button" id="Limpar"  name="Limpar" value="Limpar" />Limpar</button>                      
                        </div>
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
     <!--fim da div section--> 
   </div>
   <div id="datatables" style="width:98.5%; margin-left:0.75%;">
      <table cellpadding="0" cellspacing="0" border="0" class="display" id="record">
        <thead>
          <tr>
             <th>Tipo de Atendimento</th>
             <th>Status</th>
             <th>A&ccedil;&otilde;es</th>
          </tr>
        </thead>
        <tbody>
         <?php foreach($tipos as $row):?>
          <tr>
            <td><?php echo utf8_encode($row['DEN_TIPO_ATENDIMENTO']);?></td>
            <td align="center"><?php echo $row['STATUS_TIPO_ATENDIMENTO'];?></td>
            <td align="left" width="150px"><a href="<?php echo base_url().'index.php/tipo/editar/'.$row['COD_TIPO_ATENDIMENTO'];?>"><img style="border:0px;" src="<?php echo base_url();?>images/tables/action2.gif"></a></td>
          </tr>
         <?php endforeach;?>
        </tbody>
      </table>  
   </div> 
</div>
