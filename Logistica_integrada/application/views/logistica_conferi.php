<?php




?>





<html>

<body>

<table cellpadding="5" cellspacing="5">

<tr>
	<td><strong>Data saída: </strong></td>
	<td><?php echo $carga[0]['HORA_SAIDA'] ?></td>
</tr>

<tr>
	<td><strong>Transportadora: </strong></td>
	<td><?php echo $carga[0]['NOME_TRANSPORTADORA'] ?></td>
</tr>

<tr>
	<td><strong>CNPJ Transportadora: </strong></td>
	<td><?php echo $carga[0]['CNPJ_TRANSPORTADORA'] ?></td>
</tr>

<tr>
	<td><strong>Tipo de veículo: </strong></td>
	<td><?php echo $carga[0]['VEICULO'] ?></td>
</tr>

<tr>
	<td><strong>Placa do veículo: </strong></td>
	<td><?php echo $carga[0]['PLACA_VEICULO'] ?></td>
</tr>

<tr>
	<td><strong>Placa do cavalo: </strong></td>
	<td><?php echo $carga[0]['PLACA_CAVALO'] ?></td>
</tr>

<tr>
	<td><strong>Estado: </strong></td>
	<td><?php echo utf8_encode($carga[0]['NOME_ESTADO']) ?></td>
</tr>

<tr>
	<td><strong>Cidade: </strong></td>
	<td><?php echo utf8_encode($carga[0]['NOME_CIDADE']) ?></td>
</tr>

<tr>
	<td><strong>Cliente: </strong></td>
	<td><?php echo utf8_encode($carga[0]['NOME_CLIENTE']) ?></td>
</tr>

<tr>
	<td><strong>Distribuição: </strong></td>
	<td><?php echo utf8_encode($carga[0]['DISTRIBUICAO']) ?></td>
</tr>

<tr>
	<td><strong>Tipo de Doc.: </strong></td>
	<td><?php echo utf8_encode($carga[0]['TIPO_DOCUMENTO']) ?></td>
</tr>

<tr>
	<td><strong>Documento: </strong></td>
	<td><?php echo utf8_encode($carga[0]['DOCUMENTOS']) ?></td>
</tr>

<tr>
	<td><strong>Local carregamento: </strong></td>
	<td><?php echo $carga[0]['LOCAL_CARREGAMENTO'] ?></td>
</tr>

<tr>
	<td><strong>Doca: </strong></td>
	<td><?php echo $carga[0]['DOCA'] ?></td>
</tr>


<tr>
	<td><strong>Conferente: </strong></td>
	<td><?php echo $carga[0]['CONFERENTE'] ?></td>
</tr>

<tr>
	<td><strong>Capatazia: </strong></td>
	<td><?php echo $carga[0]['CAPATAZIA'] ?></td>
</tr>

</table>

</body>

</html>