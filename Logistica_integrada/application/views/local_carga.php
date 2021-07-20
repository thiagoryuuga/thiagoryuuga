<div id="local_descarregamento" name="local_descarregamento" style="margin-left:35%;">
<Label>Informe o local para descarregmento</label>
<form id="grava_descarregamento" name="grava_descarregamento" method="post" action="<?php echo base_url();?>index.php/inicio/gera_descarregamento">
<input type="hidden" name="carga_editada" id="carga_editada" value="<?php echo($id_carga)?>">
<select name="opcao_descarregamento" id="opcao_descarregamento">
                <option value=""> Escolha</option>
				<option value="APA_1">APA 1</option>
				<option value="APA_2">APA 2</option>
				<option value="APA_3_LOG">APA 3 - LOG</option>
				<option value="APA_4_SGF">APA 4 - SGF</option>
				<option value="APA_5_SGF">APA 5 - SGF</option>
				<option value="APA_CDE">APA_CDE</option>
				<option value="BOTIJAO">BOTIJÃO</option>
				
</select><br /><br />
<div>
<input type="submit" name="sender_descarrega" id="sender_descarrega" class="button green" value="Gravar e Liberar saida"></div>
</form>
</div>