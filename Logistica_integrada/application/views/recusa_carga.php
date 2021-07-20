<?php 
	
if (!@$id_carga){
	
	echo "Nenhuma Informação cadastrada";
	
}  else { ?>


<html>
 <body>
   <form action="<?php echo ( base_url()."index.php/expedicao/processaRecusaCarga/")?>" method="post" >
    <input type="hidden" id="id_carga" name="id_carga" value="<?php echo $id_carga; ?>">
     <textarea id="motivo_recusa" name="motivo_recusa" cols="80" rows="10"></textarea>
      <br />
     <input type="submit" class="button green" value="Gravar Recusa" name="gravar_recusa" id="gravar_recusa">
</form>
   </body>
</html>
<? } ?>