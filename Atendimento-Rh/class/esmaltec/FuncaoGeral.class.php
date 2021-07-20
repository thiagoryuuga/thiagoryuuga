<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Classe FuncaoGeral
 * Funções em geral
 * @package lib
 * @author Leandro Pedrosa Rodrigues
 * @copyright Leandro Pedrosa Rodrigues
 * @version 1.0 FuncaoGeral.class.php 19/08/2011
 */
class FuncaoGeral {

  /**
   * Retira pontuacao da string 
   * @param string $st_data
   * @return string
   */
  public static function alphaNum($st_data) {
    $st_data = preg_replace("([[:punct:]]| )", '', $st_data);
    return $st_data;
  }

  /**
   * Retira caracteres nao numericos da string
   * @param string $st_data
   * @return string
   */
  public static function numeric($st_data) {
    $st_data = preg_replace("([[:punct:]]|[[:alpha:]]| )", '', $st_data);
    return $st_data;
  }

  /**
   * 
   * Retira tags HTML / XML e adiciona "\" antes
   * de aspas simples e aspas duplas
   * @param unknown_type $st_string
   */
  public static function cleanString($st_string) {
    return addslashes(strip_tags($st_string));
  }

  /**
   * Proteção contra injeção de SQLs
   * @param string $sql
   * @return string
   */
  public static function antiInjection($sql) {
    $seg = preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/i", '', $sql); //remove palavras que          contenham a sintaxe sql
    $seg = trim($seg); //limpa espaços vazios
    $seg = strip_tags($seg); // tira tags html e php
    $seg = addslashes($seg); //adiciona barras invertidas a uma string
    return $seg;
  }

  /**
   * Proteção contra invasão
   * Para cada valor de um array é verificado injeção de SQLs
   * E por último pesquisa por palavras mais comuns
   * Retorna 0 se tiver ok, caso contrário, retorna um número maior que 0
   * @param array $array
   * @return int
   */
  public static function protecaoContraInvasao(Array $array) {
    $array_mod = array();
    $pos = 0;
    foreach ($array as $variavel) {
      $array_mod[] = self::antiInjection($variavel);
      if (strlen($variavel) < 1) {
        $pos+=1;
      }
    }
    $procura[0] = "#";
    $procura[1] = "&";
    $procura[2] = "'";
    $procura[3] = ";";
    $procura[4] = "%";
    $procura[5] = "$";
    $procura[6] = "|";
    $procura[7] = "www";
    $procura[8] = "!";
    $procura[9] = "?";
    $procura[10] = "+";
    $procura[11] = ";";
    $procura[12] = ":";
    $procura[13] = "{";
    $procura[14] = "}";
    $procura[15] = "]";
    $procura[16] = "[";
    $procura[17] = "´";
    $procura[17] = "*";
    $procura[18] = "`";
    $procura[19] = "^";
    $procura[20] = "~";
    $procura[21] = "|";
    $procura[22] = "=";
    $procura[23] = "(";
    $procura[24] = ")";
    $procura[25] = "where";
    $procura[26] = "having";
    foreach ($array_mod as $variavel) {
      for ($i = 0; $i < 27; $i++) {
        $pos += strpos("x" . $variavel, $procura[$i]);
      }
    }
    return $pos;
  }

  /**
   * Função para gerar senhas aleatórias
   *
   * @author    Leandro Pedrosa <pedrosalpr@gmail.com>
   *
   * @param integer $tamanho Tamanho da senha a ser gerada
   * @param boolean $maiusculas Se terá letras maiúsculas
   * @param boolean $numeros Se terá números
   * @param boolean $simbolos Se terá símbolos
   *
   * @return string A senha gerada
   */
  function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {
    $lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '1234567890';
    $simb = '_.$#@%';
    $retorno = '';
    $caracteres = '';

    $caracteres .= $lmin;
    if ($maiusculas)
      $caracteres .= $lmai;
    if ($numeros)
      $caracteres .= $num;
    if ($simbolos)
      $caracteres .= $simb;

    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
      $rand = mt_rand(1, $len);
      $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
  }

  /**
   * Função para gerar Identificador aleatórias
   *
   * @author    Leandro Pedrosa <pedrosalpr@gmail.com>
   *
   * @param integer $tamanho Tamanho da senha a ser gerada
   * @param boolean $maiusculas Se terá letras maiúsculas
   * @param boolean $numeros Se terá números
   *
   * @return string O identificador gerado
   */
  public static function geraIdentificador($tamanho = 8, $maiusculas = true, $numeros = true) {
    $lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '1234567890';
    $retorno = '';
    $caracteres = '';

    $caracteres .= $lmin;
    if ($maiusculas)
      $caracteres .= $lmai;
    if ($numeros)
      $caracteres .= $num;

    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
      $rand = mt_rand(1, $len);
      $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
  }

  /**
   * Método que converte todos os caracteres com acentos para maiusculos
   * @param String $texto O texto a ser convertido
   * @return String Retorna 
   */
  public static function converteMaiusculaAcentos($texto) {

    //ãÃáÁàÀêÊéÉèÈíÍìÌôÔõÕóÓòÒúÚùÙûÛçñÑ
    $texto = @str_replace('ã', 'Ã', $texto);
    $texto = @str_replace('á', 'Á', $texto);
    $texto = @str_replace('à', 'À', $texto);
    $texto = @str_replace('ê', 'Ê', $texto);
    $texto = @str_replace('é', 'É', $texto);
    $texto = @str_replace('è', 'È', $texto);
    $texto = @str_replace('í', 'Í', $texto);
    $texto = @str_replace('ì', 'Ì', $texto);
    $texto = @str_replace('ó', 'Ó', $texto);
    $texto = @str_replace('ô', 'Ô', $texto);
    $texto = @str_replace('ò', 'Ò', $texto);
    $texto = @str_replace('õ', 'Õ', $texto);
    $texto = @str_replace('ú', 'Ú', $texto);
    $texto = @str_replace('ù', 'Ù', $texto);
    $texto = @str_replace('û', 'Û', $texto);
    $texto = @str_replace('ç', 'Ç', $texto);
    $texto = @str_replace('ñ', 'Ñ', $texto);
    return ($texto);
  }

  public static function redirecionar($url) {
    echo '<script>window.location.href = "' . $url . '"</script>';
  }

  public static function anti_sql_injection($str) {
    if (!is_numeric($str)) {
      $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
      $str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str) : mysql_escape_string($str);
    }
    return $str;
  }

  /**
   * Método para calcular a quantidade de Meses entre Duas Datas
   * @access public
   * @copyright (c) 2013, Esmaltec S/A
   * @author 588104776 <leandror@intranet.esmaltec.com.br>
   * @param string $inicio Data de Inicio (Y-m-d)
   * @param string $fim Data Final (Y-m-d)
   * @return int Quantidade de meses
   * @since 1.0.0
   * @version 1.0.0 10/01/2014 Criação do método
   */
  public static function calculaQtdMesEntreDatas($inicio, $fim) {
    $d1 = new DateTime($inicio);
    $d2 = new DateTime($fim);
    $d_inicio = $d1->format("d");
    $d_fim = $d2->format("d");
    $diff = $d2->diff($d1);

    $qtd_mes = 0;
    $qtd_mes += $diff->y * 12;
    $qtd_mes += $diff->m;
    if ($d_fim <= $d_inicio) {
      $qtd_mes += 1;
    }
    $qtd_mes += ($diff->d > 0) ? 1 : 0;
    return $qtd_mes;
  }

  /**
   * Método para calcular os ultimos dias entre as data
   * @access public
   * @copyright (c) 2013, Esmaltec S/A
   * @author 588104776 <leandror@intranet.esmaltec.com.br>
   * @param string $inicio Data de Inicio (Y-m-d)
   * @param string $fim Data Final (Y-m-d)
   * @param int $qtd_mes Quantidade de meses
   * @return array Retorna array contendo todas as datas tendo ultimo dia entre as datas
   * @since 1.0.0
   * @version 1.0.0 10/01/2014 Criação do método
   */
  public static function calculaUltimoDiaEntreDatas($inicio, $fim, $qtd_mes = 0) {
    if ($qtd_mes == 0) {
      $qtd_mes = self::calculaQtdMesEntreDatas($inicio, $fim);
    }
    $data_aux = $inicio;
    $data_array = array();
    for ($i = 0; $i < $qtd_mes; $i++) {
      $data_fim_mes = date("Y-m-t", strtotime($data_aux));
      $data_array[] = $data_fim_mes;
      $data_objeto = new DateTime($data_aux);
      $data_nova = $data_objeto->add(new DateInterval('P1M'));
      $data_aux = $data_nova->format('Y-m-d');
    }
    return $data_array;
  }

  public static function calculaUltimoDiaMes($data) {

    return date("Y-m-t", strtotime($data));
  }
	
	public static function calculaPrimeiroDiaMes($data) {

    return date("Y-m-1", strtotime($data));
  }

  public static function getNomeMesAbreaviado($mes) {
    $mes_nome = "";
    $mes = (int) $mes;
    switch ($mes) {
      case 1: $mes_nome = "Jan";
        break;
      case 2: $mes_nome = "Fev";
        break;
      case 3: $mes_nome = "Mar";
        break;
      case 4: $mes_nome = "Abr";
        break;
      case 5: $mes_nome = "Mai";
        break;
      case 6: $mes_nome = "Jun";
        break;
      case 7: $mes_nome = "Jul";
        break;
      case 8: $mes_nome = "Ago";
        break;
      case 9: $mes_nome = "Set";
        break;
      case 10: $mes_nome = "Out";
        break;
      case 11: $mes_nome = "Nov";
        break;
      case 12: $mes_nome = "Dez";
        break;
    }
    return $mes_nome;
  }

  public static function getNomeMes($mes) {
    $mes_nome = "";

    switch ($mes) {
      case 1: $mes_nome = "Janeiro";
        break;
      case 2: $mes_nome = "Fevereiro";
        break;
      case 3: $mes_nome = "Março";
        break;
      case 4: $mes_nome = "Abril";
        break;
      case 5: $mes_nome = "Maio";
        break;
      case 6: $mes_nome = "Junho";
        break;
      case 7: $mes_nome = "Julho";
        break;
      case 8: $mes_nome = "Agosto";
        break;
      case 9: $mes_nome = "Setembro";
        break;
      case 10: $mes_nome = "Outubro";
        break;
      case 11: $mes_nome = "Novembro";
        break;
      case 12: $mes_nome = "Dezembro";
        break;
    }
    return $mes_nome;
  }

  public static function formatarMoedaReal($valor) {
    return number_format($valor, 0, ",", ".");
  }

  public static function verificaPermissao($nivel, Projeto $projeto = null, $idUser) {
    if (is_null($projeto)) {
      if (in_array($nivel, array(1, 2))) {
        return true;
      } else {
        return false;
      }
    } else {
      if (in_array($nivel, array(1, 2)) || ($nivel == 4 && $projeto->projeto_fk_lider == $idUser)) {
        return true;
      } else {
        return false;
      }
    }
  }

  public static function get_EAN13($number) {
    $sum = 0;

    for ($i = 0; $i < 12; $i++) {
      $sum += ( $number[$i] * ( ( $i & 1 ) ? 3 : 1 ) );
    }

    return sprintf('%s%u', $number, ( ( 1 - ( ( $sum / 10 ) - (int) ( $sum / 10 ) ) ) * 10));
  }

  public static function computeChecksumEAN($code, $type = "ean13") {
    $len = $type == 'ean13' ? 12 : 7;
    $code = substr($code, 0, $len);
    if (!preg_match('`[0-9]{' . $len . '}`', $code))
      return('');
    $sum = 0;
    $odd = true;
    for ($i = $len - 1; $i > -1; $i--) {
      $sum += ($odd ? 3 : 1) * intval($code[$i]);
      $odd = !$odd;
    }
    return($code . ( (string) ((10 - $sum % 10) % 10)));
  }

  public static function calculaDataPrevista($qtd_dia, $feriado) {
    $data_prevista = date('Y-m-d', strtotime($qtd_dia . ' weekdays'));
    while (in_array($data_prevista, $feriado)) {
      $data_prevista = date('Y-m-d', strtotime('1 weekdays', strtotime($data_prevista)));
    }
    return $data_prevista;
  }

  public static function arredondaMoeda($valor) {
    $valor = number_format($valor, 0, ',', '.');
    $explode = explode('.', $valor);
    $val = "";
    if (count($explode) > 1) {
      $val = $explode[0] . '.' . $explode[1];

      //$val = $explode[0]; 
      //$val = number_format($val, 0, ',', '.');
    } else {
      $val = $explode[0];
    }
    return $val;
  }

  public static function converteMoeda($valor, $casa = "milhares") {
    if(is_null($valor)){
      return null;
    }else
    if (is_array($valor)) {
      foreach ($valor as $key => $valor_row) {
        $valor[$key] = self::converteMoeda($valor_row, $casa);
      }
      return $valor;
    } else {
      $valor2 = number_format($valor, 0, ',', '.');
      $explode = explode('.', $valor2);
      $qtd_casa = count($explode);
      switch ($casa) {
        case "centenas":
          if ($qtd_casa == 1) {
            $val = $explode[0];
            return number_format($val, 0, ',', '.');
          } else {

            $val = implode("", $explode);
            return number_format($val, 0, ',', '.');
          }
          break;
        case "milhares":
          if ($qtd_casa == 1) {
            $val = "0." . $explode[0];
            return number_format($val, 3, ',', '.');
          } else {
            array_pop($explode);
            $val = implode("", $explode);
            return number_format($val, 0, ',', '.');
          }
          break;
        case "milhoes":
          if ($qtd_casa == 1) {
            $val = "0.000" . $explode[0];
            return number_format($val, 3, ',', '.');
          } else if ($qtd_casa == 2) {
            var_dump($explode);
            $val = "0." . $explode[0];
            return number_format($val, 3, ',', '.');
          } else {
            array_pop($explode);
            $milhares = array_pop($explode);
            $val = implode("", $explode) . '.' . $milhares;
            return number_format($val, 3, ',', '.');
          }
          break;
        default:
          if ($qtd_casa == 1) {
            $val = "0," . $explode[0];
            return number_format($val, 3, ',', '.');
          } else {
            array_pop($explode);
            $val = implode("", $explode);
            return number_format($val, 0, ',', '.');
          }
          break;
      }
    }
  }
	
	public static function converterTempoEmMinutos($tempo){
    $explode = explode(":",$tempo);
    $minutos = 0;
    if(isset($explode[0])){
      $minutos += $explode[0] * 60;
    }
    if(isset($explode[1])){
      $minutos += $explode[1];
    }
    return $minutos;
  }

}

?>
