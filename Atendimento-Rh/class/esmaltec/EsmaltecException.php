<?php



/**
* Classe  MySQLException<br>
* Lança a exceção personalizada do Exception para erro de MySQL
* @package esmaltec\class\esmaltec
* @version 1.0.0
* @author 588104776 <leandror@intranet.esmaltec.com.br>
* @copyright 2013-2013 Esmaltec S/A
**/
class EsmaltecException extends Exception{
  
  protected $titulo;
  
  protected $tipo;
  
  protected $codigo;
  
  
  
  /* Properties */
  /* Redefine a exceção para que a mensagem não seja opcional */
  public function __construct($message = "", $titulo = "", $tipo = "warning",  $codigo = 0, $code=0) {
      /* Garante que tudo é atribuído corretamente */
      parent::__construct($message, $code);
      
      switch ($tipo){
        case "warning": $this->tipo = $tipo; $this->titulo = ($titulo != "") ? $titulo : "Alerta!"; break;
        case "error": $this->tipo = $tipo; $this->titulo = ($titulo != "") ? $titulo : "Erro!"; break;
        case "danger": $this->tipo = $tipo; $this->titulo = ($titulo != "") ? $titulo : "Erro!"; break; 
        default: $this->tipo = "danger"; $this->titulo = ($titulo != "") ? $titulo : "Erro!";  break;
      }
      
      $this->tipo = $tipo;
      $this->codigo = $codigo;
      
      $e = array();
      $e["message"] = parent::getMessage();
      $e["code"] = $this->codigo;
      $e["file"] = parent::getFile();
      $e["tipo"] = $this->tipo;
      $e["line"] = parent::getLine();
      $e["trace"] = parent::getTrace();
      $e["trace_string"] = parent::getTraceAsString();
      
      if($this->tipo == "danger"){
        $retorno = EnviaEmail::enviaEmailSuporteLog($_SESSION["system"][SISTEMA_PATH]['sistema']['Name'], BASE_URL, $e);
        //var_dump($retorno);
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
  
  public function getCodigo() {
      return $this->codigo;
  }
  
  public function getInfo(){
    $info["tipo"] = $this->getTipo();
    $info["titulo"] = $this->getTitulo();
    $info["msg"] = $this->getMessage();
    $info["id"] = $this->getCodigo();
    $info["bool"] = false;
    return $info;
  }
}

?>
