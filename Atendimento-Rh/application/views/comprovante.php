<style media="all">
#comprovante{
    width: 100%; 
    border:1px solid black; border-collapse: collapse; margin:3px;
}
.central
{
    text-align:center;
}

.lower{
    vertical-align: bottom;

}
.table tr
{
    padding:1px;
}
</style>



<script>
function file_ready(cod_atendimento,atendente)
{
     var button = document.getElementById('send_print');
     var ok = window.confirm('Deseja imprimir este comprovante?');
     if(ok)
     {
         var xhttp = new XMLHttpRequest();
         xhttp.open("POST", "<?php echo site_url('atendimento/grava_impressao/"+cod_atendimento+"/"+atendente+"'); ?>", true);
         xhttp.send();
         button.style.display = 'none';
         window.print();         
     }

     
    
}
</script>
<html>
<body>
<br />
 <br />

<?php for($i=0; $i <= 1; $i++){?>

    <table border='1' id="comprovante">
    <?php if($i == 0){?>
    <tr><th colspan='4' class="central">RECIBO DE ATENDIMENTO – RH - VIA RH</th></tr>
    <?php } ?>
    <?php if($i > 0){?>
    <tr><th colspan='4' class="central">RECIBO DE ATENDIMENTO – RH - VIA COLABORADOR</th></tr>
    <?php } ?>
    
    <tr><th>MATRÍCULA:</th><td><?php echo($dados_consulta[0]['NUM_MATRICULA'])?></td><th>NOME:</th><td><?php echo($dados_consulta[0]['NOM_COMPLETO'])?></td></tr>
    <tr><th>SETOR:</th><td><?php echo($dados_consulta[0]['DEN_UNI_FUNCIO'])?></td><th>CARGO:</th><td><?php echo($dados_consulta[0]['DEN_CARGO'])?></td></tr>
    <tr><th>TIPO DE ATENDIMENTO:</th><td colspan='3'><?php echo(utf8_encode($dados_consulta[0]['DEN_TIPO_ATENDIMENTO']))?></td></tr>
    <tr><th>ASSUNTO:</th><td colspan='3'><?php echo(utf8_encode($dados_consulta[0]['DEN_ATENDIMENTO']))?></td></tr>
    <tr><th>OBSERVAÇÕES:</th><td colspan='3'><?php echo(utf8_encode($dados_consulta[0]['OBSERVACAO_ATENDIMENTO']))?></td></tr>
    <tr><th>DATA DE ATENDIMENTO:</th><td colspan='3'> <?php echo($dados_consulta[0]['DATA_INI_ATENDIMENTO'])?></td></tr>
    <tr><th>ATENDENTE:</th><td colspan='3'><?php echo($dados_consulta[0]['NOM_ATENDENTE'])?></td></tr>
    <tr height="100" class="central lower" ><td colspan='2'><br /><br />Assinatura:</td><td colspan='2'><br /><br />Assinatura:</td></tr>
    <tr class="central"><td colspan='2' ><?php echo($dados_consulta[0]['NOM_COMPLETO'])?></td><td colspan='2'><?php echo($dados_consulta[0]['NOM_ATENDENTE'])?></td></tr>
    </table>

    <br />
    <br />
    <?php if($i == 0){?>
    <p>Corte Aqui <br />
    .................................................................................................................................................................................................
    </p>
    <?php } ?>
    <br />
    <br />
<?php } ?>




<input type="button" name="send_print" id="send_print" onclick="file_ready('<?php echo $dados_consulta[0]['COD_ATENDIMENTO']?>','<?php echo $dados_consulta[0]['ATENDENTE']?>')" value="Imprimir Recibo" />
</body>
</html>