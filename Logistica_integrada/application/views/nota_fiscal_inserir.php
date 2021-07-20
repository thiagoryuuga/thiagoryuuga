<script type="text/javascript">
$(function () {
  function removeCampo() {
	$(".removerCampo").unbind("click");
	$(".removerCampo").bind("click", function () {
	   if($("tr.linhas").length > 1){
		$(this).parent().parent().remove();
	   }
	});
  }
 
  $(".adicionarCampo").click(function () {
	novoCampo = $("tr.linhas:first").clone();
	novoCampo.find("input").val("");
	novoCampo.insertAfter("tr.linhas:last");
	removeCampo();
  });
});
</script>


<table border="0" cellpadding="2" cellspacing="4"> 
	
  	<?php if (!empty($notas)){?>
  	<tr>    
   	 	<label style="line-height:15px;padding-right:20px;">Nota Fiscal</label>    	 	
  	</tr>
											
	<?php foreach ($notas as $nf){?>
  	
  	<tr class="linhas">       	 	 
   	 	<td><input type="text" class="text" name="NOTA_FISCAL[]" value="<?php echo $nf['NOTA_FISCAL']?>"/></td>    
    	<td><a href="#" class="removerCampo" title="Remover linha"><img src="<?php echo base_url(); ?>images/delete.png" border="0" /></a></td>
  	</tr>
  	
  	<?php }
	} else {?>	
		
	<tr class="linhas">    
   	 	<label style="line-height:15px;padding-right:20px;">Nota Fiscal</label> 
   	 	<td><input type="text" class="text" name="NOTA_FISCAL[]" /></td>    
    	<td><a href="#" class="removerCampo" title="Remover linha"><img src="<?php echo base_url(); ?>images/delete.png" border="0" /></a></td>
  	</tr>
		
	<?php } ?>	
  	
  	<tr>
  		<td colspan="4">
        	<a href="#" class="adicionarCampo" title="Adicionar item"><img src="<?php echo base_url(); ?>images/add.png" border="0" /></a>
		</td>
	</tr>
 
</table>