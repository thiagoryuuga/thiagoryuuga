<script>
	$(".readonly").attr('readonly','readonly');
	$("#DATA_AGENDAMENTO").datepicker();
	$("#DATA_AGENDAMENTO").datepicker("option", "dateFormat", 'dd/mm/yy');
</script>
<style>
	.label{

		width:25%;
		float:left;
		text-align:left;
		font-family:Verdana, Geneva, sans-serif;
		font-size:14px;
		font-weight:500;
	}
	.campo{

		width:73%;
		float:right;
		text-align:left;
	}
	.campo input{
		height:20px;
	}
	p{
		height:25px;
	}
	.justi{
		height:120px;
		font-family:Verdana, Geneva, sans-serif;
		font-size:14px;
		font-weight:bold;
		text-align:left;
	}
	.readonly{
		color:#666;
	}
	.button{
		float:right;
		margin-right:40px;
	}
</style>
<div style="background-color:#E7F3F8; text-align:center; margin-left:40px;">            
    <h3 style="padding: 10px;">Agendamento - Atualizar Agenda</h3><br/>	
    <form  enctype="multipart/form-data" action="<?php echo base_url() . 'index.php/agendamento/alterar'; ?>" method="post">												
       <div>
       	   <div class="justi">
        	<label>Justificativa:</label>
            <p><textarea class="readonly" style="height:75px; width:580px;" id="JUSTIFICATIVA_DEVOLUCAO" name="JUSTIFICATIVA_DEVOLUCAO"><?php echo $justificativa;?></textarea></p>
           </div>
           <div class="label">
            <p><label>Data Agendamento:</label></p>
            <p><label>Carreta:</label></p>
            <p><label>Linha:</label></p>
            <p><label>Ordem:</label></p>
            <p><label>N&deg; OC Cliente:</label></p>
            <p><label>Cliente:</label></p>
            <p><label>Endereço:</label></p>
            <p><label>Código do Item:</label></p>
            <p><label>Descrição do Item:</label></p>
            <p><label>Quatidade:</label></p>
            
           </div>
           <div class="campo">
            <input type="hidden" id="COD_AGENDAMENTO" name="COD_AGENDAMENTO"  size="50" value="<?php echo $agendamento[0]['COD_AGENDAMENTO'];?>">
            <p><input type="text" id="DATA_AGENDAMENTO" name="DATA_AGENDAMENTO" size="8" value="<?php echo $agendamento[0]['DATA_AGENDAMENTO'];?>"/></p>
           	<p><input type="text" id="CARRETA" name="CARRETA" class="readonly" size="50" value="<?php echo $agendamento[0]['COD_CARRETA'];?>"/></p>
            <p><input type="text" id="LINHA" name="LINHA" class="readonly" size="50" value="<?php echo $agendamento[0]['LINHA'];?>"/></p>
            <p><input type="text" id="ORDEM" name="ORDEM" class="readonly" size="50" value="<?php echo $agendamento[0]['ORDEM_VENDA'];?>"/></p>
            <p><input type="text" id="OC_CLIENTE"  name="OC_CLIENTE" class="readonly" size="50" value="<?php echo $agendamento[0]['OC_CLIENTE'];?>"/></p>
            <p><input type="text" id="CLIENTE" name="CLIENTE" class="readonly" size="50" value="<?php echo $agendamento[0]['CLIENTE_ENTREGA'];?>"/></p>
            <p><input type="text" id="ENDERECO" name="ENDERECO" class="readonly" size="50" value="<?php echo $agendamento[0]['REGIONAL'];?>"/></p>
            <p><input type="text" id="COD_ITEM" name="COD_ITEM" class="readonly" size="50" value="<?php echo $agendamento[0]['COD_ITEM'];?>"/></p>
            <p><input type="text" id="DEN_ITEM" name="DEN_ITEM" class="readonly" size="50" value="<?php echo $agendamento[0]['DEN_ITEM'];?>"/></p>
            <p><input type="text" id="QTD" name="QTD" class="readonly" size="8" value="<?php echo $agendamento[0]['QTD'];?>"/></p>
           
           </div>
      </div>

       <button class="button blue normal" type="submit" id="Cadastrar"  name="Enviar" value="Enviar" >Enviar</button>
											
  </form>
</div>