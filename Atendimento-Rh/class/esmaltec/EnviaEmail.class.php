<?php

include_once ESM_CLASS_PATH . 'lib/phpmailer/5.1/phpmailer.class.php';
include_once ESM_CLASS_PATH . 'lib/phpmailer/5.1/class.smtp.php';

/**
 * Classe EnviaEmail
 * Configura��es para enviar email
 * @package lib
 * @author Leandro Pedrosa Rodrigues
 * @copyright Leandro Pedrosa Rodrigues
 * @version 1.0 EnviaEmail.class.php 19/08/2011
 */
class EnviaEmail {

  /**
   * Vari�vel armazenando o host do email
   * @name $mail_host
   * @var String
   * @access private
   * @static
   */
  private static $mail_host = "intranet.esmaltec.com.br";

  /**
   * Vari�vel armazenando o email do suporte
   * @name $mail_username
   * @var String
   * @access private
   * @static
   */
  private static $mail_username = "leandror@intranet.esmaltec.com.br";

  /**
   * Vari�vel armazenando a senha do email
   * @name $mail_password
   * @var String
   * @access private
   * @static
   */
  private static $mail_password = "lpr03kga07";

  /**
   * Vari�vel armazenando o caminho dos templates de email
   * @name $mail_templates_path
   * @var String
   * @access private
   * @static
   */
  private static $mail_templates_path = "email/";

  /**
   * Vari�vel armazenando o nome do email
   * @name $mail_nome
   * @var String
   * @access private
   * @static
   */
  private static $mail_nome = "Suporte";

  /**
   * Vari�vel armazenando a base url
   * @name $base_url
   * @var String
   * @access private
   * @static
   */
  private static $base_url = BASE_URL;

  /**
   * Vari�vel armazenando o email do suporte
   * @name $email_suporte
   * @var String
   * @access private
   * @static
   */
  private static $email_suporte = "leandror@intranet.esmaltec.com.br";

  /**
   * Vari�vel armazenando o nome do suporte
   * @name $nome_suporte
   * @var String
   * @access private
   * @static
   */
  private static $nome_suporte = "Leandro Pedrosa Rodrigues";

  public static function enviaEmailSuporteLog($titulo_sistema, $dominio_sistema, $e = NULL, $mysql = NULL) {

    $mensagem = $exception = '';
    if (!is_null($mysql)) {
      $mensagem = 'Foi encontra o seguinte erro de MySQL:<br>';
      $mensagem = 'Número do erro: ' . $mysql[0] . '<br>';
      $mensagem = 'Erro: ' . $mysql[1] . '<br>';
    }

    if (!is_null($e)) {
      $exception = '<ul>
      <li><span class="negrito">Exception</span></li>
      <li><span class="negrito">Mensagem</span>: ' . $e["message"] . '</li>
      <li><span class="negrito">Código</span>: ' . $e["code"] . '</li>
      <li><span class="negrito">Arquivo</span>: ' . $e["file"] . '</li>
      <li><span class="negrito">Tipo</span>: ' . $e["tipo"] . '</li>
      <li><span class="negrito">Linha</span>: ' . $e["line"] . '</li>
      <li><span class="negrito">Trace String</span>: ' . $e["trace_string"] . '</li>
      </ul>';
    }

    $tituloEmail = "[Suporte - Erro] - " . $titulo_sistema;
    $emailBody = file_get_contents(ESM_TEMPLATE_PATH . self::$mail_templates_path . 'email_suporte_log.html');
    $emailBody = str_replace('[ESM_TEMPLATE_PATH]', ESM_TEMPLATE_PATH, $emailBody);
    $emailBody = str_replace('[DATA_HORA]', date('d/m/Y H:i:s'), $emailBody);
    $emailBody = str_replace('[TITULO_SISTEMA]', $titulo_sistema, $emailBody);
    $emailBody = str_replace('[DOMINIO_SISTEMA]', $dominio_sistema, $emailBody);
    $emailBody = str_replace('[EXCEPTION]', utf8_decode($exception), $emailBody);
    $emailBody = str_replace('[MENSAGEM]', utf8_decode($mensagem), $emailBody);
    $emailBody = str_replace('[EMAIL_SITE]', self::$email_suporte, $emailBody);


    $mail = new PHPMailer();
    $mail->SetLanguage("br");
    //$mail->IsSMTP();
    $mail->IsHTML(true);
    $mail->SMTPAuth = true;
    $mail->Host = self::$mail_host;
    $mail->Username = self::$mail_username;
    $mail->Password = self::$mail_password;
    $mail->AddAddress(self::$email_suporte, self::$nome_suporte);
    $mail->SetFrom(self::$mail_username, self::$mail_nome);
    //$mail->AddReplyTo($_SESSION['remetente_email'], $_SESSION['remetente_nome']);
    // Anexa o arquivo
    //$mail->AddAttachment($fileImg, $snapshot);
    $mail->Subject = $tituloEmail;
    $mail->Body = $emailBody;
    if ($mail->Send()) {
      return array(true, "Email enviado com sucesso para o suporte!");
    } else {
      return array(false, "Erro: " . $mail->ErrorInfo);
    }
  }

}

?>
