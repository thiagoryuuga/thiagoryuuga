<script language="javascript">


	
	$(document).ready( function() {
		
	
	
	
	$("#gerar").click(function(){
	
		$("#formPesquisa").validate({
			// Define as regras
			rules:{			
				cod_distribuicao_ebs:{
					// campoNome será obrigatório (required) e terá tamanho mínimo (minLength)
					required: true
				}
				
			},
			// Define as mensagens de erro para cada regra
			messages:{
				
				cod_distribuicao_ebs:{
					required: "Campo Obrigatório",
					minLength: "O seu nome deve conter, no mínimo, 2 caracteres"
				}
				
			}
		});
	});
	
	});
	
	function valida_autorizacao(id) {
		if (confirm("Deseja autorizar entrada ?")) {
			window.location.href="<?php echo base_url()?>index.php/inicio/liberar/"+id;
		}
	}
	
	
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 250,
			width: 330,
			modal: true,
			buttons: {	
				"Enviar": function()
				{
					$("#justificativa").val($("#JUSTIFICATIVA_DEVOLUCAO").val());
					$("#formPesquisa").submit();
					$( this ).dialog( "close" );
					
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#devolve" ).click(function() {
			//alert ('cheguei aki');
				$("#formPesquisa").attr('action','<?php echo base_url()?>index.php/agendamento/devolverAgendamento');
				$( "#dialog-form" ).dialog( "open" );
			})
	});
	
	
	
	
	
	function setLeadTime(id){
		$.ajax({ url: "<?php echo site_url('agendamento/setLeadTime'); ?>",
			data: { cod_agendamento: id, time: $("#ddl"+id).val()},
			dataType: "json",
			type: "POST",
			success: function(data){
				alert("Alterações efetuadas!");
				
			}
			
		});
}
	
</script>
<script>
	function setDist(id){
		
		
		$.ajax({ url: "<?php echo site_url('agendamento/gerarDistribuicao'); ?>",
			data: { cod_agendamento: id, distribuicoes: $("#distrib_"+id).val()},
			dataType: "json",
			type: "POST",
			success: function(data){
			
				
			}

			
		});
		alert('Distribuição Gerada!');
			window.location.reload();
}
</script>


<h3 style="padding: 10px;">Agendamento - Gerar Distibuição</h3>	
<form id="formPesquisa" action="<?php echo base_url()?>index.php/agendamento/gerarDistribuicao" method="post" style="min-height: 340px">
	
	<?php if($this->session->flashdata('flashErro')):?>
		<ul class="system_messages">
			
			<li class="red"><span class="ico"></span><strong class="system_title"><?=$this->session->flashdata('flashErro')?></strong></li>
			
		</ul>	
	<?endif?>
	
	<?php if($this->session->flashdata('flashSucesso')):?>
		<ul class="system_messages">															
			<li class="green"><span class="ico"></span><strong class="system_title"><?=$this->session->flashdata('flashSucesso')?></strong></li>															
		</ul>	
	<?endif?>
	
	<?php if($this->session->flashdata('flashDevolvido')){?>															
		<ul class="system_messages">														
			<li class="yellow"><span class="ico"></span><strong class="system_title"></strong><?php echo $this->session->flashdata('flashDevolvido');?></li>
		</ul>													
	<?php } ?>	
	
	<br />
	
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="autorizado">
<span id="table_ini"></span>
		<thead>
				<tr>
                    <!-- <th><a href="#">Codigo Agendamento</a></th>-->
                    <th style="padding:2px; margin:0px;"><a href="#">Carreta</a></th>
                    <th  style="padding:2px; margin:0px;"><a href="#">Data de Cadastro</a></th>													
					<th style="padding:2px; margin:0px;"><a href="#">Linha</a></th>
					<th style="padding:2px; margin:0px;"><a href="#">Ordem EBS</a></th>
					<th style="padding:2px; margin:0px;"><a href="#">N&deg; OC Cliente</a></th>
					<th style="padding:2px; margin:0px;"><a href="#">Cliente</a></th>
					<th style="padding:2px; margin:0px;"><a href="#">Endere&ccedil;o</a></th>
					<th style="padding:2px; margin:0px;"><a href="#">Cod. Item</a></th>
					<th style="padding:2px; margin:0px;"><a href="#">Desci&ccedil;&atilde;o Item</a></th>
                    <th style="padding:2px; margin:0px;"><a href="#">QTD</a></th>
                    <th style="padding:2px; margin:0px;"><a href="#">Data Agendamento</a></th>
                    <th style="padding:2px; margin:0px;"><a href="#">Filial</a></th>
                    <!--<th style="padding:2px; margin:0px;"><a href="#">Localiza&ccedil;&atilde;o</a></th>-->
                    <th style="padding:2px; margin:0px;"><a href="#">Distribuições</a></th>													 
                    <th  style="padding:2px; margin:0px;"><a href="#">Lead Time</a></th>
                    <th  style="padding:2px; margin:0px;"><a href="#">Gravar</a></th>																																		
				</tr>
			</thead>
			<tbody>
                   <?php foreach ($cargasAgendamentoDistribuicao as $row) {?>	
					<tr class="first">
                    	<?php //$cidade = explode(", ", $row['ENDERECO']); ?>
                        <!-- <td id="cidade"><?php echo $row['COD_AGENDAMENTO'];?>-->   									  
						<td id="<?php echo ("carreta_".$row['COD_AGENDAMENTO'])?>" style="padding:2px; margin:0px;"><?php echo $row['COD_CARRETA']?></td>
                        <td style="padding:2px; margin:0px;"><?php echo $row['DATA_CADASTRO']?></td>
                        <td style="padding:2px; margin:0px;"><?php echo $row['LINHA']?></td>																		
						<td style="padding:2px; margin:0px;"><?php echo $row['ORDEM_VENDA'];?></td>
						<td style="padding:2px; margin:0px;"><?php echo $row['OC_CLIENTE'];?></td>
						<td style="padding:2px; margin:0px;"><?php echo $row['CLIENTE_ENTREGA'];?></td>
						<td style="padding:2px; margin:0px;"><?php echo $row['REGIONAL']; ?></td>
						<td style="padding:2px; margin:0px;"><?php echo $row['COD_ITEM'];?></td>
						<td style="padding:2px; margin:0px;"><?php echo $row['DEN_ITEM'];?></td>
						<td style="padding:2px; margin:0px;"><?php echo $row['QTD'];?></td>
						<td style="padding:2px; margin:0px;"><?php echo $row['DATA_AGENDAMENTO'];?></td>
						<td style="padding:2px; margin:0px;"><?php echo $row['DEN_FILIAL'];?></td>
						<!--<td style="padding:2px; margin:0px;"><?php echo $row['LOCALIZACAO'];?></td>-->
                        <td style="padding:2px; margin:0px;"><input type="text" id="<?php echo("distrib_".$row['COD_AGENDAMENTO'])?>" name="<?php echo("distrib_".$row['COD_AGENDAMENTO'])?>" 
                        value="<?php echo $row['COD_DISTRIBUICAO_EBS']?>" ></td> 
																						
				<!--<td  style="padding:2px; margin:0px;"><input type="checkbox" name="COD_AGENDAMENTO[]" value="<?php echo $row['COD_AGENDAMENTO']?>"></td>-->
                 <?php if (isset($row['DATA_LIMITE'])){
						if (isset ($row['LEADTIME']) && ($row['RESTANTE']> $row['LEADTIME']))
						{
							$cor = 'green';
						}
						if ( isset ($row['LEADTIME']) && ($row['RESTANTE'] == $row['LEADTIME']))
						{
							$cor = 'orange';
						}
						if (isset ($row['LEADTIME']) && ($row['RESTANTE']< $row['LEADTIME']))
						{
							$cor = 'red';
						}
						?>
                                
                <td style="padding:2px; margin:0px;"><input type="button" style="font-size: 10px; float: left; margin:0px; padding:0px; height:30px;" class="button<?php echo(' '.$cor)?>" value="<?php echo substr($row['DATA_LIMITE'], 0, 10); ?>" /></td>
                <?php } else {/*$estado= explode (', ',$row['ENDERECO'])*/?>
                <td>&nbsp;</td>
                <!--<td><select id="<?php echo('ddl'.$row['COD_AGENDAMENTO'])?>" class="teste">
                <option value="<?php echo $estado[1]?>">Escolha</option>
              <?php foreach ($tempo as $localidade){
				  if($estado[1] == $localidade['SIGLA'])
				  {
					 
					 $tyle = "display:block;";
					
					 
				  }
				  else
				  {
					    $tyle = "display:none;";
				  }
				  
				  ?>
              
             <option style="<?php echo ($tyle);?>" value="<?php echo ($localidade['LEADTIME'])?>"><?php echo $localidade['SIGLA']. " - " 
			  .$localidade['LOCALIDADE']?></option>
              <?php } ?>
               </select>
               </td>-->
               <?php } ?>
             
             <td><input type="button" class="button oblue" style="height:30px;" name="gravar" id="<?php echo $row['COD_AGENDAMENTO'];?>" value="Gravar" onclick="setDist(this.id)"/></td>	</tr>
			<?php } ?>	
								
		</tbody>
	</table>
     

	
		<div style="float:; height: 30px;padding-top:5px; ">
			
            		<!--<a id="create-user" style=" height:15px;" class="thickbox button orange" href="<?php echo base_url();?>index.php/agendamento/formDevolucao/<?php echo $row['COD_AGENDAMENTO']?>?height=260&width=300" title="Devolver">Devolver</a>--> <div style="float:right; width: 130px; padding-right: 10px; height:10px; ">
<!--             <input type="button" class="button red" style="width: 120px;" value="Devolver" name="devolve" id="devolve">	-->																	
					
			</div>
                 
               
            <div style="float:right; width: 130px; padding-right: 10px; ">
            		<!--<input type="submit" class="button green" style="width: 120px;" value="Gerar Distribuição" name="gerar" id="gerar">-->
                    <!--<input type="button" class="button green" style="width: 120px;" value="Atualizar" name="gerar" id="gerar" onclick="atualizar()">-->										    
			</div>
           
           
		</div>
	   
<div id="dialog-form" title="Devolução" style="height:190px; display:none;">
	
   
                    <label for="JUSTIFICATIVA_DEVOLUCAO"><span style="font-weight:bold;">Justificativa</span></label><br/>           
                     <textarea  name="JUSTIFICATIVA_DEVOLUCAO" style="height:125px; width:290px;" id="JUSTIFICATIVA_DEVOLUCAO"></textarea>
               
</div>
</form>																	
