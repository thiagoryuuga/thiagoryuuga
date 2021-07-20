<?php 
$exist = false; 
if(isset($funcionario) && $funcionario != null){ 
	$exist= true;
}
if(isset($erro)){
	echo '<script>window.alert("'.$erro.'")</script>';	
}
?>

<script charset="utf-8">
$(function(){
	
	
	$("#record_length").html("Tabela");
	
	$("#atendForm").validate();
	
	if($.browser.msie == true){
		$("#form").css("height",3900+'px');
	}
	if($.browser.mozilla)
	{
		$("#form").css("height",5000+'px');
	}
	
	
	
	$('.readonly').attr('readonly','readonly');
	
	

	
	var status = '<?php  if(isset($atend)) echo $atend[0]['STATUS_ATENDIMENTO'];?>';
	if(status != '1' && status != ""){
		$(".normal").attr('disabled','disabled');
	}
	
	$("#vis_fal").click(function(){
		$("#dialog" ).dialog();
	});
	
	$("#vis_his_fal").click(function(){
		$("#dialog-historicoFaltas" ).dialog({width:900});
	});
	
	$("#vis_his_atestado").click(function(){
		$("#dialog-historicoAtestado" ).dialog({width:900});
	});
	
	
	$("#vis_his_med").click(function(){
		$("#dialog-historicoMedidas" ).dialog({width:900});
	});
	
	
	
});


		
</script>
<script type="text/javascript" language="javascript" src="../../js/jspdf/jspdf.js"></script>
<script type="text/javascript" language="javascript" src="../../js/tableToJson/lib/jquery.tabletojson.js"></script>
<script type="text/javascript" language="javascript" src="../../js/jspdf/jspdf.plugin.standard_fonts_metrics.js"></script>
<script type="text/javascript" language="javascript" src="../../js/jspdf/libs/Deflate/deflate.js"></script>
<script type="text/javascript" language="javascript" src="../../js/jspdf/libs/Deflate/adler32cs.js"></script>
<script type="text/javascript" language="javascript" src="../../js/jspdf/jspdf.plugin.cell.js"></script>
<script type="text/javascript" language="javascript" src="../../js/jspdf/jspdf.plugin.split_text_to_size.js"></script>
<script type="text/javascript" language="javascript" src="../../js/jspdf/jspdf.plugin.from_html.js"></script>
<script type="text/javascript" language="javascript" src="../../js/jspdf/libs/FileSaver.js/FileSaver.js"></script> 

<script>

 function enviaRelatorio(matri,nome,setor) {
	 
	 	//var head = tableToJson($('#dados_func').get(0))
		var table = tableToJson($('#vis_his_atestado').get(0))
		
		var doc = new jsPDF('l', 'pt', 'a3', false);
		doc.setFontSize(12);
		doc.text(20, 20, 'NOME: '+nome);
		doc.text(20, 35, 'MATRICULA: '+matri);
		doc.text(20, 50, 'SETOR: '+setor);
		
		/*doc.cellInitialize();
		$.each(head, function (i, row){
		  $.each(row, function (j, cell){
			  
			doc.cell(10, 100, 130, 20, cell, i);//parametros 1° margem esquerda / 2° margem topo / 3° tamanho da tabela / 4° altura da celula
		  })
		})*/
		doc.setFontSize(8);
		doc.cellInitialize();
		$.each(table, function (i, row){
		  $.each(row, function (j, cell){
			  
			doc.cell(10, 80, 130, 20, cell, i);//parametros 1° margem esquerda / 2° margem topo / 3° tamanho da tabela / 4° altura da celula
		  })
		})
		doc.save()
		
		
			   
    }
   
</script>

<script>

function tableToJson(table)
{
	 var table = $('#lista_atestados').tableToJSON(); // Convert the table into a javascript object
 return table;
  
}

/*function table2ToJson(table)
{
	 var head = $('#dados_func').tableToJSON(); // Convert the table into a javascript object
 return head;
  
}*/


</script>



<style>
body {
	margin: 0;
	padding: 0;
}
p {
	margin: 5px;
	height: 25px;
}
.readonly {
	background: #f5f5f5;
}
#record_length {
	font-size: 20px;
	font-weight: normal;
	line-height: 35px;
	margin: 0 0 0 13px;
	;
	padding: 0;
}
#antlink, #link {
	background: url('<?php echo base_url();?>images/entrar-btn.png') no-repeat;
	line-height: 20px;
	padding: 3px 13.5px 3px 13.5px;
	color: #FFF;
	font-family: Arial;
	font-size: 12px;
	font-weight: bold;
	width: 94px;
}
.questoes {
	width: 40%;
	padding-left: 5%;
	padding-right: 5%;
	float: left;
}
</style>
<?php $url = base_url(); ?>

<div class="tudo">
	<!--inicio da div section-->
	<div class="section" style="width:98.5%; margin-left:0.75%; margin-right:0.75%;">
		<!--inicio da div title_wrapper-->
		<div class="title_wrapper">
  		<h2>Formulario</h2>
  		<span class="title_wrapper_left"></span> <span class="title_wrapper_right"></span> 
  	<!--fim da div title_wrapper--> 
		</div>
		<!--inicio da div section_content-->
		<div class="section_content">
			<!--[if !IE]>start section content top<![endif]-->
			<div class="sct">
				<div class="sct_left">
					<div class="sct_right">
						<div class="sct_left">
							<div class="sct_right" align="center">
<!--[if !IE]>start dashboard menu<![endif]-->
<div style="float:none; position:relative; height:350px !important;" id="form">
<p style="text-align:left;"></p>
<?php  if(isset($faltas)) { $f = $faltas->num_rows(); if($f > 0) {$fal = $faltas->result_array();}}?>
<form action="<?php echo base_url() . 'index.php/atestado/funcionario'; ?>" method="post" id="atendForm" accept-charset="UTF-8">
  <div id="colum_left" style="float:left; width:48%;" align="right">
    <div style="float:left; width:30%;" align="right">
      <p>
        <label for="NUM_MATRICULA">Matricula: </label>
      </p>
      <p>
        <label for="NOME">Nome: </label>
      </p>
      <p>
        <label for="SETOR">Setor: </label>
      </p>
      <p>
        <label for="CARGO_INI">Cargo Inicial: </label>
      </p>
      <p>
        <label for="CARGO_ATU">Cargo Atual: </label>
      </p>
      <p>
        <label for="DATA_ADMISSAO">Data Admiss&atilde;o: </label>
      </p>
      <p>
        <label for="TEMPO_FUNCAO">Tempo de fun&ccedil;&atilde;o: </label>
      </p>
      <p>
        <label for="CONTATO">Contato: </label>
      </p>
    </div>
    <div style="float:right; width:69.5%;" align="left">
      <p>
        <input class="required" type="text" name="NUM_MATRICULA" id="NUM_MATRICULA" size="10" value="<?php if($exist) echo $funcionario[0]['NUM_MATRICULA']; ?>"/>
        <input type="submit" value="Pesquisar" />
        <!--a  id="link" href="#" rel="superbox[iframe][750x550]"> PESQUISAR </a> <a  id="antlink"  href="#"> PESQUISAR </a--> </p>
      <p>
        <input type="text" class="readonly" name="NOME" id="NOME" size="50" value="<?php if($exist) echo $funcionario[0]['NOM_COMPLETO']; ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="SETOR" id="SETOR" size="50" value="<?php if($exist) echo $funcionario[0]['DEN_UNI_FUNCIO']; ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="CARGO_INI" id="CARGO_INI" size="50" value="<?php if($exist) echo $funcionario[0]['CARGO_INICIAL']; ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="CARGO_ATU" id="CARGO_ATU" size="50" value="<?php if($exist) echo $funcionario[0]['CARGO_ATUAL']; ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="DATA_ADMISSAO" id="DATA_ADMISSAO" size="10" value="<?php if($exist) echo $funcionario[0]['DAT_ADMIS']; ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="DATA_ADMISSAO" id="CARGO_ATUAL" size="25" value="<?php if($exist) echo $funcionario[0]['TEMPO_FUNCAO']; ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="CONTATO" id="CONTATO" size="20"  value="<?php if($exist) echo $funcionario[0]['NUM_TELEF_RES']; ?>"/>
      </p>
    </div>
  </div>
  <div id="colum_right" style="float:right; width:51%;" align="left">
    <div style="float:left; width:28%;" align="right">
      <p>
        <label for="STATUS">Status: </label>
      </p>
      <p>
        <label for="GERENCIA">Gerência: </label>
      </p>
      <p>
        <label for="CIDADE">Cidade: </label>
      </p>
      <p>
        <label for="IDADE">Idade: </label>
      </p>
      <p>
        <label for="ESCOLARIDADE">Escolaridade: </label>
      </p>
      <p>
        <label for="TEMPO_EMPRESA">Tempo de Empresa: </label>
      </p>
      <p>
        <label for="PNE">PCD: </label>
      </p>
      <p>
        <label for="EST_CIVIL">Estado Civil: </label>
      </p>
    </div>
    <div style="float:right; width:71.5%;" align="left">
      <p>
        <input type="text" class="readonly" name="STATUS" id="STATUS" size="20"  value="<?php if($exist) echo $funcionario[0]['STATUS']; ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="GERENCIA" id="GERENCIA" size="50" value="<?php if($exist) echo $funcionario[0]['DEN_ESTR_LINPROD']; ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="CIDADE" id="CIDADE" size="50" value="<?php if($exist) echo $funcionario[0]['DEN_CIDADE']; ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="IDADE" id="IDADE" size="20" value="<?php if($exist) echo $funcionario[0]['IDADE']; ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="IDADE" id="IDADE" size="20" value="<?php if($exist) echo $funcionario[0]['DES_GRAU_INSTR']; ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="TEMPO_EMPRESA" id="TEMPO_EMPRESA" size="20" value="<?php if($exist) echo $funcionario[0]['TEMPO_EMPRESA']; ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="PNE" id="PNE" size="20" value="<?php if($exist) echo utf8_encode($funcionario[0]['PNE']); ?>"/>
      </p>
      <p>
        <input type="text" class="readonly" name="EST_CIVIL" id="EST_CIVIL" size="20" value="<?php if($exist) echo $funcionario[0]['IES_EST_CIVIL']; ?>"/>
      </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <input class="normal" type="hidden" name="COD_ACOMP_FALTAS" id="COD_ACOMP_FALTAS" value="<?php if(isset($acomp)) echo $acomp[0]['COD_ACOMP_FALTAS'];?>">
    </div>
  </div>

</form>
<div style="text-align:left;">
 <p>
 	<span id="vis_his_fal" style="text-decoration:underline !important; cursor:pointer;">Visualizar Historico de Faltas</span><br />
  <span id="vis_his_med" style="text-decoration:underline !important; cursor:pointer;">Visualizar Historico de Medidas Disiplinares</span><br />
  <span id="vis_his_atestado" style="text-decoration:underline !important; cursor:pointer;">Visualizar Histórico de Atestado</span>
 </p>
</div>

</div>

</div></div></div></div></div></div>
<span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
</div></div>
<div id="dialog" style="display:none;" title="Datas das Faltas">
  <?php if(isset($fal)){
	   $color = "#E1F0FF";
		foreach($fal as $row){
			if($color == "#E1F0FF")
				$color = "#FFFFFF";
			else
				$color = "#E1F0FF";
			echo "<p style='background:".$color."; magin:0; padding:5px;'>".$row['DAT_FALTA']."</p>";
		}
	}?>
</div>
<div id="dialog-historicoFaltas" style="display:none; width:500px;" title="Historico de Faltas">
  <?php if(isset($historicoQtdFaltas)){
	   $color = "#E1F0FF";
		
		$tabela = "<table border='1' bordercolor='#000000' style='border:1px solid;'>
					<tr bgcolor='".$color."'>
						<td width='80px' align='center'>Janeiro</td>
						<td width='80px' align='center'>Fevereiro</td>
						<td width='80px' align='center'>Março</td>
						<td width='80px' align='center'>Abril</td>
						<td width='80px' align='center'>Maio</td>
						<td width='80px' align='center'>Junho</td>
						<td width='80px' align='center'>Julho</td>
						<td width='80px' align='center'>Agosto</td>
						<td width='80px' align='center'>Setembro</td>
						<td width='80px' align='center'>Outubro</td>
						<td width='80px' align='center'>Novembro</td>
						<td width='80px' align='center'>Dezembro</td>
					</tr>";
		
		foreach($historicoQtdFaltas as $row){
		$tabela.=  "<tr>
						<td align='center'>".$row['JANEIRO']."</td>
						<td align='center'>".$row['FEVEREIRO']."</td>
						<td align='center'>".$row['MARCO']."</td>
						<td align='center'>".$row['ABRIL']."</td>
						<td align='center'>".$row['MAIO']."</td>
						<td align='center'>".$row['JUNHO']."</td>
						<td align='center'>".$row['JULHO']."</td>
						<td align='center'>".$row['AGOSTO']."</td>
						<td align='center'>".$row['SETEMBRO']."</td>
						<td align='center'>".$row['OUTUBRO']."</td>
						<td align='center'>".$row['NOVEMBRO']."</td>
						<td align='center'>".$row['DEZEMBRO']."</td>
					</tr>";
		}
		
		$tabela .= " </table>";
		
		if(isset($historicoDatasFaltas)){
			$datas = "";
			foreach($historicoDatasFaltas as $dat){
				$datas .= $dat['DAT_FALTA'].' , ';
			}
			$datas = substr($datas,0,(strlen($datas)-3));
			$tabelaDatas = "<p><table border='1' bordercolor='#000000' style='border:1px solid;'>
							<tr>
							  <td bgcolor='".$color."' width='150px' align='center'>Datas Das Faltas</td>
							  <td> ".$datas." </td>
							</tr>
						</table></p>
						";
		}
		
		echo $tabela;
		echo $tabelaDatas;
	}?>
</div>
<div id="dialog-historicoMedidas" style="display:none; width:500px;" title="Historico de Medidas Disiplinares">
  <?php if(isset($historicoMedidas)){
	   $color = "#E1F0FF";
		
		$tabela = "<table border='1' bordercolor='#000000' style='border:1px solid;'>
					<tr bgcolor='".$color."'>
						<td width='80px' align='center'>Medida</td>
						<td width='80px' align='center'>Janeiro</td>
						<td width='80px' align='center'>Fevereiro</td>
						<td width='80px' align='center'>Março</td>
						<td width='80px' align='center'>Abril</td>
						<td width='80px' align='center'>Maio</td>
						<td width='80px' align='center'>Junho</td>
						<td width='80px' align='center'>Julho</td>
						<td width='80px' align='center'>Agosto</td>
						<td width='80px' align='center'>Setembro</td>
						<td width='80px' align='center'>Outubro</td>
						<td width='80px' align='center'>Novembro</td>
						<td width='80px' align='center'>Dezembro</td>
					</tr>";
		$medida = "";
		foreach($historicoMedidas as $row){
			if($row['OQUE'] == 'S'){$medida = "SUSPENSÃO";} else if($row['OQUE'] == 'A'){$medida = "ADVERTÊNCIA";}
		$tabela.=  "<tr>
						<td align='center'>".$medida."</td>
						<td align='center'>".$row['JANEIRO']."</td>
						<td align='center'>".$row['FEVEREIRO']."</td>
						<td align='center'>".$row['MARCO']."</td>
						<td align='center'>".$row['ABRIL']."</td>
						<td align='center'>".$row['MAIO']."</td>
						<td align='center'>".$row['JUNHO']."</td>
						<td align='center'>".$row['JULHO']."</td>
						<td align='center'>".$row['AGOSTO']."</td>
						<td align='center'>".$row['SETEMBRO']."</td>
						<td align='center'>".$row['OUTUBRO']."</td>
						<td align='center'>".$row['NOVEMBRO']."</td>
						<td align='center'>".$row['DEZEMBRO']."</td>
					</tr>";
		}
		
		$tabela .= " </table>";
		
		$tabelaDatas = "<p>";
		$tipo = "";
		if(isset($historicoMedidas[0]['SUSPENSAO'])){
			$tabelaDatas .= "<table border='1' bordercolor='#000000' style='border:1px solid;'>
							<tr>
							  <td bgcolor='".$color."' width='180px' align='center'>Datas Das Suspensões</td>
							  <td> ".$historicoMedidas[0]['SUSPENSAO']." </td>
							</tr>
						</table>
						";		
		}
		if(isset($historicoMedidas[0]['ADVERTENCIA'])){
			$tabelaDatas .= "<table border='1' bordercolor='#000000' style='border:1px solid;'>
							<tr>
							  <td bgcolor='".$color."' width='180px' align='center'>Datas Das Advertências</td>
							  <td> ".$historicoMedidas[0]['ADVERTENCIA']." </td>
							</tr>
						</table>
						";		
		}
		$tabelaDatas .= "</p>";
		echo $tabela;
		echo $tabelaDatas;
	}?>
</div>
<div id="dialog-historicoAtestado" style="display:none; width:500px;" title="Histórico de Atestado"> 
  <?php
	if(isset($historicoAtestado) && isset($funcionario)){
		if(is_array($historicoAtestado) && (count($historicoAtestado))){
			$i = 1;
			?>
            <table id="dados_func" border="1" bordercolor="#000000" style="border:1px solid;" width="870px">
    <tbody>
   
      <tr bgcolor="#E1F0FF" align="center">
        <td></td>
        <td ></td>
        <td></td>
       </tr>
      <tr bgcolor="#E1F0FF" align="center">
        <td>Matricula</td>
        <td >Nome</td>
        <td>Setor</td>
       </tr>
       
       <tr align="center"><td><?php echo($funcionario[0]['NUM_MATRICULA'])?></td>
        <td ><?php echo($funcionario[0]['NOM_COMPLETO'])?></td>
        <td><?php echo($funcionario[0]['DEN_UNI_FUNCIO'])?></td></tr>
        <tr>
        <td>&nbsp;</td>
        <td >&nbsp;</td>
        <td>&nbsp;</td>
       </tr>
        </tbody>
        </table>
        
        
            
            
  <table id="lista_atestados" border="1" bordercolor="#000000" style="border:1px solid;">
    <tbody>
   
      <tr bgcolor="#E1F0FF" align="center">
        <td width="40px" align="center">#</td>
        <td width="80px" align="center">Período</td>
        <td width="80px" align="center">Saída</td>
        <td width="80px" align="center">Retorno</td>
        <td width="80px" align="center">Qtd. Dias</td>
        <td width="80px" align="center">CID</td>
        <td width="240px" align="center">Descrição</td>
        <td width="80px" align="center">CRM</td>
        <td width="160px" align="center">Médico</td>
        <td width="160px" align="center">Posto de Trabalho</td>
      </tr>
     
      <?php
			foreach($historicoAtestado as $atestado){
				?>
      <tr style="font-size:11px;">
      
        <td align="center"><?php echo $i;?></td>
        <td align="center"><?php echo $atestado['periodo'];?></td>
        <td align="center"><?php echo $atestado['saida'];?></td>
        <td align="center"><?php echo $atestado['retorno'];?></td>
        <td align="center"><?php echo $atestado['qtd_dia'];?></td>
        <td align="center"><?php echo $atestado['cid'];?></td>
        <td align="center"><?php echo utf8_encode($atestado['cid_descricao']);?></td>
        <td align="center"><?php echo $atestado['crm'];?></td>
        <td align="center"><?php echo $atestado['medico'];?></td>
        <td align="center"><?php echo $atestado['posto'];?></td>
      </tr>
      <?php
				$i++;
			}
			?>
    </tbody>
  </table>
  <?php
		}else{
			echo $historicoAtestado;		
		}
	}else{
		echo 'Sem resultados';
	}
	?>
    <button id="relat_pdf" name="relat_pdf" style="float:right; margin-top:10px;" onclick="enviaRelatorio('<?php echo $funcionario[0]['NUM_MATRICULA']?>','<?php echo $funcionario[0]['NOM_COMPLETO']?>','<?php echo $funcionario[0]['DEN_UNI_FUNCIO']?>')" value="Extrair_em_PDF">Extrair em PDF</button>
</div>
