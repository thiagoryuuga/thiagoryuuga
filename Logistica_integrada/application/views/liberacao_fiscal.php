<script>
function listaLibFiscal()
	{

		$.ajax({ url: "<?php echo site_url('expedicao/listaLiberacaoFiscal'); ?>",
			data: { dummyPost: 'foo'},
			dataType: "html",
			type: "POST",
			success: function(data){
				
				 $("#libfis").fadeOut('fast');
				 $("#libfis").find("tbody").empty();
				 $("#libfis").find("tbody").append(data);
				 tb_init('a.thickbox');
				 $("#libfis").fadeIn('slow');

				
			}
		});
	}
</script>

<script language="javascript">
	function valida(id) {
		alert(id);
		if (confirm("Deseja autorizar a Saída ?")) {
			window.location.href="<?php echo base_url()?>index.php/inicio/saida/"+id;
		}
		
	}
</script> 
<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="libfis">
	<thead>
		<tr style="color:#6B98A8">
		<th style="text-align: center;">Início Lib. Fiscal</th>
		<th>Transportadora</th>
		<th>Operação</th>
		<th>Veículo</th>
		<th style="text-align: center;">Placa</th>
		<th>UF</th>																
		<th>Cidade</th>
		<th>Status</th>
		<th>Loc. Carga</th>
		<th>Doca</th>
		<th>Conferente</th>
		<th>Capatazia</th>
		<th>Motorista</th>
		<th style="text-align: center;">Visualizar</th>
		</tr>
	</thead>
	<tbody style="font-size:11px;">
		<?php $tyle=""; ?>
		<?php foreach ($veiculosLibFiscal as $rowPendentes) {
			if($rowPendentes['TEMPO'] < 30)
			{
				$tyle="background-color:#FFF;";
			}
			if($rowPendentes['TEMPO'] > 30 && $rowPendentes['TEMPO'] < 60)
			{
				$tyle="background-color:#BD0";
			}
			if($rowPendentes['TEMPO'] >60 )
			{
				$tyle="background-color:#A00;color:#FFF;";
			}?>
			<tr>
				<td  style="background-color:#fff" class="center"><?php echo ($rowPendentes['HORA_LIB_FISCAL_INI'])?></td>
				<td><div id="<?php echo ('d_'.$rowPendentes['ID_CARGA'])?>"><?php echo substr($rowPendentes['NOME_TRANSPORTADORA'],0,15)?></div></td>
				<td><?php echo $rowPendentes['DES_OPERACAO']?></td>
				<td><?php echo $rowPendentes['VEICULO']?></td>
				<td class="center"><?php echo $rowPendentes['PLACA_VEICULO']?></td>
				<td><?php echo utf8_encode($rowPendentes['NOME_ESTADO'])?></td>
				<td><?php echo utf8_encode($rowPendentes['NOME_CIDADE'])?></td>
				<td><?php echo $rowPendentes['STATUS']?></td>
				<td><?php echo($rowPendentes['LOCAL_CARREGAMENTO'])?></td>
				<td><?php echo($rowPendentes['DOCA'])?></td>
				<td><?php echo($rowPendentes['CONFERENTE'])?></td>
				<td><?php echo($rowPendentes['CAPATAZIA'])?></td>
				<?php
                     $link = base_url()."index.php/inicio/dados_motorista/".$rowPendentes['ID_CARGA']."?height=300&width=400";
					 $link_recusa = base_url()."index.php/expedicao/abreRecusaCarga/".$rowPendentes['ID_CARGA']."?height=200&width=570";
            	?>
				<td class="center"><a class="thickbox" href="<?echo $link?>">[Informações]</a></td>
				<td >
					<div class="actions" style="width: 40px;">
					<ul>
						<li><a class="action1 thickbox" href="<?php echo base_url()?>index.php/expedicao/ver/<?php echo $rowPendentes['ID_CARGA']?>/<?php echo $par = ""?>?height=580&width=1100" title="visualizar"></a></li>
						<li><a class="action4 thickbox" href="<?php echo $link_recusa ?>" title="Recusar Veiculo"></a></li>
					</ul>
					</div>																	
				</td>
            </tr>
			<?php } ?>
            </tbody>
			</table>