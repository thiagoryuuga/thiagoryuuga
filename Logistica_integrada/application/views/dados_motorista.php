<?php 
	@$data = explode('-', $carga[0]['DATA_ATUAL']);


if (!@$carga[0]['DATA_ATUAL']){
	
	echo "Nenhuma Informação cadastrada";
	
}  else { ?>


<html>

<body>

<table cellpadding="5" cellspacing="5">

<tr>
	<td><strong>Data/hora Chegada: </strong></td>
	<td><?php echo $data[2]."/".$data[1]."/".$data[0] ;?> as <?php echo $carga[0]['HORA_CHEGADA'] ?></td>
</tr>

<tr>
	<td><strong>Nome do Motorista: </strong></td>
	<td><?php echo $carga[0]['NOME_MOTORISTA'] ?></td>
</tr>

<tr>
	<td><strong>Nome do Motorista: </strong></td>
	<td><?php echo $carga[0]['CPF_MOTORISTA'] ?></td>
</tr>

<tr>
	<td><strong>Telefone: </strong></td>
	<td><?php echo $carga[0]['TELEFONE_CONTATO'] ?></td>
</tr>

<tr>
	<td><strong>Transportadora: </strong></td>
	<td><?php echo $carga[0]['NOME_TRANSPORTADORA'] ?></td>
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
	<td><strong>Cidade: </strong></td>
	<td><?php echo utf8_encode($carga[0]['NOME_CIDADE']) ?></td>
</tr>

<tr>
	<td><strong>Estado: </strong></td>
	<td><?php echo utf8_encode($carga[0]['NOME_ESTADO']) ?></td>
</tr>



</table>

</body>

</html>
<? } ?>