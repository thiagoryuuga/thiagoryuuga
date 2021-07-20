<script>
function validaCampo()
{
	var size = document.getElementById('motivo_recusa').value;
	
	if (size.length < 5)
	{
		document.getElementById('motivo_recusa').focus();
		alert('Informação Obrigatória');
		return false;
	}
}
	
</script>
<form id="form_recusa_carga" action="<?php echo base_url()?>index.php/expedicao/recusar_entrada" method="post">
	<LABEL class="ui-state-default">Motivo da recusa:</LABEL><br/>
	<textarea id="motivo_recusa" name="motivo_recusa" cols="54" rows="8" value=""></textarea>
	<input type="hidden" name="carga_recusa" id="carga_recusa" value="<?php echo($carga)?>" />
	<input type="hidden" name="status_carga" id="status_carga" value="REC" />
	<br/>
	<input class="button green" type="submit" name="grava_recusa" id="grava_recusa" value="Registrar" onclick="validaCampo();">
	

</form>
