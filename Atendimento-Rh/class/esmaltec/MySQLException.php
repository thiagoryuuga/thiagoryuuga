<?php



/**
* Classe  MySQLException<br>
* Lança a exceção personalizada do Exception para erro de MySQL
* @package esmaltec\class\esmaltec
* @version 1.0.0
* @author 588104776 <leandror@intranet.esmaltec.com.br>
* @copyright 2013-2013 Esmaltec S/A
**/
class MySQLException extends Exception{
  
  protected $titulo;
  
  protected $tipo;
  
  protected $erro;
  
  /* Properties */

  public function __construct($message = "", $titulo = "", $tipo = "warning", $erro = "",  $code = 0, $filename = __FILE__, $lineno = __LINE__ , $previous = null) {
      /* Garante que tudo é atribuído corretamente */
      parent::__construct($message, $code);
      $this->message = $message;
      $this->code = $code;
      switch ($tipo){
        case "warning": $this->tipo = $tipo; $this->titulo = ($titulo != "") ? $titulo : "Alerta!"; break;
        case "danger": $this->tipo = $tipo; $this->titulo = ($titulo != "") ? $titulo : "Erro!"; break; 
        default: $this->tipo = "danger"; $this->titulo = ($titulo != "") ? $titulo : "Erro!";  break;
      }
      $this->erro = $erro;
      $this->tipo = $tipo;
      $this->file = $filename;
      $this->line = $lineno;
      $e = array();
      $e["message"] = parent::getMessage();
      $e["code"] = parent::getCode();
      $e["file"] = parent::getFile();
      $e["tipo"] = $this->tipo;
      $e["line"] = parent::getLine();
      $e["trace"] = parent::getTrace();
      $e["trace_string"] = parent::getTraceAsString();
      $mysql = (is_array($erro)) ? $erro : NULL;
      if($this->tipo == "danger"){
        $retorno = EnviaEmail::enviaEmailSuporteLog($_SESSION['sistema']['Name'], BASE_URL, $e, $mysql);
      }
  }
  
  /* Representação do objeto personalizada no formato string */
  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }

  public function getTitulo() {
      return $this->titulo;
  }
  public function getTipo() {
      return $this->tipo;
  }
  
  public function getInfo(){
    $info["tipo"] = $this->getTipo();
    $info["titulo"] = $this->getTitulo();
    $info["msg"] = $this->getMessage();
    $info["id"] = 0;
    $info["bool"] = false;
    return $info;
  }
}

?>
