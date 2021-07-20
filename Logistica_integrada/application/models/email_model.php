<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Email_Model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->library('email');
	}
	
	function getMail(){
	
			return $this->devolucao($_POST);
		
			
	}
			
	function enviar()
	{
		//print_r($_POST);
		//die();
		//$this->db->where('COD_AGENDAMENTO',$_POST['0']);
		//$this->db->select('EMAIL_VENDAS,EMAIL_LOGISTICA');
		//$DB3 = $this->load->database('UsersMaster',true);
		//$this->db->select('EMAIL_VENDAS,EMAIL_LOGISTICA');
		//$this->db->where('usuario','31101442');
		//$usuario = $this->db->get('ESM_LOGISTICA_USUARIO_FILIAL')->result_array();
		
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from('sislogistica@intranet.esmaltec.com.br','Sistema de Logistica e Expedição');
		$this->email->to('thiagos@intranet.esmaltec.com.br');
		$this->email->Cc('swf@esmaltec.com.br'); 
		$this->email->subject('Alerta de carga devolvida');
		$mensagem = $this->getMail();		
		$this->email->message($mensagem);	
		
		$this->email->send();
		}
	
		
	function devolucao(){
		

		

     	$mensagem = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<title>Confirma&ccedil;&atilde;o de chamado</title>
						<style type="text/css">
						
						body table tr td {
							font-size: 12px;
							font-family: Verdana, Geneva, sans-serif;
							border:none;
							
						}
						
						</style>
						</head>
                        <body>
						<table width="100%" height="100%" cellpadding="0" cellspacing="none" border="none"><tr bgcolor="#EEEEEE"><td width="5%" rowspan="4"></td>
						  <td colspan="2" style="color: #69C; font-size: 30px;" >&nbsp;</td>
						  <td width="5%" rowspan="4"></td></tr>
														  <tr>
															<td height="45" colspan="2"><p style="font-size:12px;"><b> Assunto:</b>Alerta de carga devolvida<br />
														  <b>Data: </b>'.date('d/m/Y H:i:s').' </p>															  <p style="font-size:12px;"></td>
														  </tr>
														  <tr>
															<td width="46%" colspan="2" valign="top" style="font-size:12px;"><p>Uma ou mais cargas que estavam agendadas foram devolvidas.</p>
															<p>Veja aqui mais detalhes:</p>
															<p><b>Cod. Agendamento:</b>'.$_POST['COD_AGENDAMENTO'].'</p>
															<p><b>Distribuição:</b>'.$_POST['cod_distribuicao_ebs'].'</p>
															<p><b>Justificativa:</b>'.$_POST['justificativa'].'</p>
															<hr /></td>
														  </tr>
                                  <tr>
                                    <td height="84"><p><img src="http://intranet.esmaltec.com.br:8090/sistemas/logistica/images/esmaltec.fw.png"/></p>
<p style="margin:5px;">Sistema de Logistica e Expedição<br />
Esmaltec Eletrodomésticos</p></td>
 <td align="right" valign="bottom"><img src="http://intranet.esmaltec.com.br:8090/sistemas/logistica/images/grupo.fw.png" style="margin:5px;" /></td>
 </tr>
 <tr bgcolor="#EEEEEE" >
 <td colspan="4">&nbsp;</td>

 </tr>
 
</table>						</body>
</html>';
return $mensagem;
		
		
	}
	
}
?>